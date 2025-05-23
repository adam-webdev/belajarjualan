<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use App\Models\Address;
use App\Models\ProductCombination;
use App\Models\Coupon;
use App\Models\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ShippingCost;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'payment'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Get dashboard data with proper payment status handling
     */
    public function getDashboardData()
    {
        // Get orders with eager loading
        $orders = Order::with(['user', 'payment'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                // Ensure payment status is always available
                $paymentStatus = $order->payment ? $order->payment->status : 'pending';
                return [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name,
                    'total' => $order->total,
                    'status' => $order->status,
                    'payment_status' => $paymentStatus
                ];
            });

        // Get payment statistics
        $totalOrders = Order::count();
        $paidOrders = Order::whereHas('payment', function($q) {
            $q->where('status', 'paid');
        })->count();
        $pendingOrders = Order::whereHas('payment', function($q) {
            $q->where('status', 'pending');
        })->count();

        // Get order status counts
        $pendingCount = Order::where('status', 'pending')->count();
        $processingCount = Order::where('status', 'processing')->count();
        $deliveredCount = Order::where('status', 'delivered')->count();

        return [
            'recent_orders' => $orders,
            'total_orders' => $totalOrders,
            'paid_orders' => $paidOrders,
            'pending_orders' => $pendingOrders,
            'order_status' => [
                'pending' => $pendingCount,
                'processing' => $processingCount,
                'delivered' => $deliveredCount
            ]
        ];
    }

    public function create()
    {
        $users = User::all();
        $products = Product::with(['combinations.combinationValues.optionValue.option'])
            ->where('is_active', true)
            ->get();
        $shippingMethods = ShippingMethod::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.orders.create', compact('users', 'products', 'shippingMethods'));
    }

    public function store(Request $request)
    {
        $messages = [
            'user_id.required' => 'Pilih pelanggan terlebih dahulu',
            'user_id.exists' => 'Pelanggan yang dipilih tidak valid',
            'address_id.required' => 'Pilih alamat pengiriman terlebih dahulu',
            'address_id.exists' => 'Alamat pengiriman yang dipilih tidak valid',
            'items.required' => 'Tambahkan minimal satu produk ke pesanan',
            'items.array' => 'Format data produk tidak valid',
            'items.min' => 'Tambahkan minimal satu produk ke pesanan',
            'items.*.product_combination_id.required_without' => 'Pilih varian produk',
            'items.*.product_combination_id.exists' => 'Varian produk yang dipilih tidak valid',
            'items.*.product_id.required_without' => 'Pilih produk',
            'items.*.product_id.exists' => 'Produk yang dipilih tidak valid',
            'items.*.quantity.required' => 'Masukkan jumlah produk',
            'items.*.quantity.integer' => 'Jumlah produk harus berupa angka bulat',
            'items.*.quantity.min' => 'Jumlah produk minimal 1',
            'shipping_cost.required' => 'Biaya pengiriman harus diisi',
            'shipping_cost.numeric' => 'Biaya pengiriman harus berupa angka',
            'shipping_cost.min' => 'Biaya pengiriman minimal 0',
            'status.required' => 'Status pesanan harus dipilih',
            'status.in' => 'Status pesanan tidak valid'
        ];

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:addresses,id',
            'items' => 'required|array|min:1',
            'items.*.product_combination_id' => 'required_without:items.*.product_id|exists:product_combinations,id',
            'items.*.product_id' => 'required_without:items.*.product_combination_id|exists:products,id',
            'items.*.is_default' => 'nullable|boolean',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_cost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ], $messages);

        DB::beginTransaction();
        try {


            // Calculate order values
            $subtotal = 0;
            $items = [];

            foreach ($validated['items'] as $item) {
                if (isset($item['product_combination_id'])) {
                    // Process combination product
                    $combination = ProductCombination::findOrFail($item['product_combination_id']);
                    $price = $combination->price;
                    $itemSubtotal = $price * $item['quantity'];
                    $subtotal += $itemSubtotal;

                    $items[] = [
                        'product_combination_id' => $item['product_combination_id'],
                        'quantity' => $item['quantity'],
                        'price' => $price,
                        'subtotal' => $itemSubtotal,
                    ];
                } else {
                    // Process default product
                    $product = Product::findOrFail($item['product_id']);
                    $price = $product->base_price;
                    $itemSubtotal = $price * $item['quantity'];
                    $subtotal += $itemSubtotal;

                    // Create a virtual combination if product has no combinations
                    $combination = ProductCombination::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'sku' => 'DEFAULT-' . $product->id
                        ],
                        [
                            'price' => $product->base_price,
                            'stock' => $product->stock ?? 10,
                            'weight' => $product->weight ?? 0
                        ]
                    );

                    $items[] = [
                        'product_combination_id' => $combination->id,
                        'quantity' => $item['quantity'],
                        'price' => $price,
                        'subtotal' => $itemSubtotal,
                    ];
                }
            }

            // Calculate total
            $total = $subtotal + $validated['shipping_cost'];

            // Create order
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'address_id' => $validated['address_id'],
                'order_number' => 'ORD-' . Str::upper(Str::random(8)),
                'subtotal' => $subtotal,
                'shipping_cost' => $validated['shipping_cost'],
                'discount_amount' => 0,
                'total' => $total,
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            // Create payment record
            $paymentMethod = 'pending';
            if ($request->payment_type === 'bank_transfer' && $request->bank_name) {
                $paymentMethod = 'bank_transfer - ' . $request->bank_name;
            } elseif ($request->payment_type === 'e_wallet' && $request->e_wallet_name) {
                $paymentMethod = 'e_wallet - ' . $request->e_wallet_name;
            }


               // Create payment record
            $payment = payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod,
                'amount' => $order->total,
                'status' => 'pending'
            ]);


             // Add payment details based on method
            if ($request->payment_type === 'bank_transfer') {
                $bankAccountNumbers = [
                    'Bank Central Asia (BCA)' => '1234567890',
                    'Bank Mandiri' => '0987654321',
                    'Bank Negara Indonesia (BNI)' => '1122334455',
                    'Bank Rakyat Indonesia (BRI)' => '5566778899'
                ];

                $payment->update([
                    'payment_proof' => json_encode([
                        'bank_name' => $request->bank_name,
                        'account_number' => $bankAccountNumbers[$request->bank_name] ?? '1234567890',
                        'account_name' => 'THRIFT SHOP'
                    ])
                ]);
            } else if ($request->payment_type === 'e_wallet') {
                $payment->update([
                    'payment_proof' => json_encode([
                        'e_wallet_name' => $request->e_wallet_name,
                        'account_number' => '08998083333',
                        'account_name' => 'THRIFT SHOP'
                    ])
                ]);
            }

            // Create order details
            foreach ($items as $item) {
                $order->details()->create($item);

                // Decrease stock
                $combination = ProductCombination::find($item['product_combination_id']);
                $combination->decreaseStock($item['quantity']);
            }

            DB::commit();
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'address',
            'details.productCombination.product.images',
            'details.productCombination.combinationValues.optionValue.option',
            'payment',
            'shippingMethod'
        ]);
        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $order->load(['user', 'address', 'details.productCombination.product', 'payment']);
        return view('admin.orders.edit', compact('order', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        // Check if status changed to cancelled and it was not cancelled before
        $wasStatusChanged = $order->status !== $validated['status'];
        $isCancellingOrder = $wasStatusChanged && $validated['status'] === 'cancelled' && $order->status !== 'cancelled';

        DB::beginTransaction();
        try {
            // If order is being cancelled, increase stock for all items
            if ($isCancellingOrder) {
                foreach ($order->details as $detail) {
                    $combination = $detail->productCombination;
                    if ($combination) {
                        $combination->increaseStock($detail->quantity);
                    }
                }
            }

            // Update order
            $order->update($validated);

            DB::commit();
            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        // Check if order has payment
        if ($order->payment && $order->payment->status === 'paid') {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Cannot delete an order with paid payment.');
        }

        DB::beginTransaction();
        try {
            // If order is not cancelled, increase stock for all items
            if ($order->status !== 'cancelled') {
                foreach ($order->details as $detail) {
                    $combination = $detail->productCombination;
                    if ($combination) {
                        $combination->increaseStock($detail->quantity);
                    }
                }
            }

            // Delete related records
            $order->details()->delete();
            $order->couponUsage()->delete();
            $order->payment()->delete();

            // Delete order
            $order->delete();

            DB::commit();
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.orders.index')
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function getUserAddresses(Request $request)
    {
        try {
            $userId = $request->user_id;
            if (!$userId) {
                \Log::warning('getUserAddresses called without user_id');
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required'
                ], 400);
            }

            $user = User::find($userId);
            if (!$user) {
                \Log::warning("getUserAddresses called with non-existent user_id: {$userId}");
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $addresses = $user->addresses()
                ->select('id', 'recipient_name', 'phone', 'province', 'city', 'district', 'postal_code', 'address_detail')
                ->get()
                ->map(function ($address) {
                    $address->full_address = $address->address_detail . ', ' . $address->district . ', ' . $address->city . ', ' . $address->province . ' ' . $address->postal_code;
                    return $address;
                });

            \Log::info("Successfully fetched {$addresses->count()} addresses for user {$userId}");
            return response()->json([
                'success' => true,
                'addresses' => $addresses
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching addresses: ' . $e->getMessage(), [
                'user_id' => $request->user_id,
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error loading addresses. Please try again.'
            ], 500);
        }
    }

    public function getProductPrice(Request $request)
    {
        try {
            $combinationId = $request->combination_id;

            if (!$combinationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Combination ID is required'
                ], 400);
            }

            $combination = ProductCombination::with('product')->findOrFail($combinationId);

            return response()->json([
                'success' => true,
                'price' => $combination->price,
                'stock' => $combination->stock,
                'product_name' => $combination->product->name,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting product price: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveAddress(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'recipient_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'province' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'district' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'address_detail' => 'required|string',
                'is_default' => 'boolean'
            ]);

            DB::beginTransaction();

            // If this is set as default, unset any existing default address
            if ($validated['is_default']) {
                Address::where('user_id', $validated['user_id'])
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            // Create new address
            $address = Address::create($validated);

            DB::commit();

            // Format full address for display
            $address->full_address = $address->address_detail . ', ' .
                $address->district . ', ' .
                $address->city . ', ' .
                $address->province . ' ' .
                $address->postal_code;

            return response()->json([
                'success' => true,
                'message' => 'Address saved successfully',
                'address' => $address
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving address: ' . $e->getMessage(), [
                'user_id' => $request->user_id,
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error saving address: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProductCombinations(Request $request)
    {
        try {
            $productId = $request->product_id;
            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ], 400);
            }

            $product = Product::with(['combinations.optionValues.option'])->findOrFail($productId);

            // If product has no variants, return default option
            if (!$product->has_variant) {
                // Create or get default combination
                $defaultCombination = ProductCombination::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'sku' => 'DEFAULT-' . $product->id
                    ],
                    [
                        'price' => $product->base_price,
                        'stock' => $product->stock ?? 10,
                        'weight' => $product->weight ?? 0
                    ]
                );

                return response()->json([
                    'success' => true,
                    'base_price' => $product->base_price,
                    'combinations' => [[
                        'id' => $defaultCombination->id,
                        'sku' => $defaultCombination->sku,
                        'name' => 'Default Product',
                        'price' => $defaultCombination->price,
                        'stock' => $defaultCombination->stock
                    ]]
                ]);
            }

            // Get combinations with their values
            $combinations = $product->combinations()
                ->with(['optionValues.option'])
                ->get()
                ->map(function ($combination) {
                    // Build combination name from option values
                    $optionValues = $combination->optionValues
                        ->map(function ($ov) {
                            return $ov->option->name . ': ' . $ov->value;
                        })
                        ->join(', ');

                    return [
                        'id' => $combination->id,
                        'sku' => $combination->sku,
                        'name' => $optionValues ?: $combination->sku,
                        'price' => $combination->price,
                        'stock' => $combination->stock
                    ];
                });

            return response()->json([
                'success' => true,
                'combinations' => $combinations
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting product combinations: ' . $e->getMessage(), [
                'product_id' => $request->product_id,
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error loading product combinations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate shipping cost based on shipping method and address
     */
    public function calculateShippingCost(Request $request)
    {
        try {
            $messages = [
                'shipping_method_id.required' => 'Pilih metode pengiriman terlebih dahulu',
                'shipping_method_id.exists' => 'Metode pengiriman yang dipilih tidak valid',
                'address_id.required' => 'Pilih alamat pengiriman terlebih dahulu',
                'address_id.exists' => 'Alamat pengiriman yang dipilih tidak valid'
            ];

            $request->validate([
                'shipping_method_id' => 'required|exists:shipping_methods,id',
                'address_id' => 'required|exists:addresses,id',
            ], $messages);

            $methodId = $request->shipping_method_id;
            $address = Address::findOrFail($request->address_id);

            // Get shipping cost based on location
            $cost = ShippingCost::where('shipping_method_id', $methodId)
                ->where(function ($query) use ($address) {
                    $query->where('city', $address->city)
                        ->orWhere('province', $address->province)
                        ->orWhere('district', $address->district);
                })
                ->first();

            if ($cost) {
                return response()->json([
                    'success' => true,
                    'cost' => $cost->cost,
                    'method_name' => $cost->shippingMethod->name
                ]);
            }

            // If no specific cost found, get default cost for the method
            $method = ShippingMethod::findOrFail($methodId);

            return response()->json([
                'success' => true,
                'cost' => $method->default_cost,
                'method_name' => $method->name
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error calculating shipping cost: ' . $e->getMessage(), [
                'shipping_method_id' => $request->shipping_method_id,
                'address_id' => $request->address_id,
                'exception' => $e
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung biaya pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }
}