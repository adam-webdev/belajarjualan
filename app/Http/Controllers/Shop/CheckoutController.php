<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Address;
use App\Models\ShippingMethod;
use App\Models\Coupon;
use App\Models\Payment;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Display the checkout page
     */
    public function index()
    {
        try {
            // Get selected items from session
            $selectedItemIds = session('checkout_items');

            if (!$selectedItemIds) {
                return redirect()->route('shop.cart')
                    ->with('error', 'Please select items to checkout first');
            }

            // Get selected cart items with relations
            $selectedItems = CartItem::whereIn('id', $selectedItemIds)
                ->whereHas('cart', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with([
                    'productCombination.product',
                    'productCombination.optionValues'
                ])
                ->get();

            if ($selectedItems->isEmpty()) {
                return redirect()->route('shop.cart')
                    ->with('error', 'Selected items not found');
            }

            // Get user's default address
            $address = Address::where('user_id', Auth::id())
                ->where('is_default', true)
                ->first();

            if (!$address) {
                return redirect()->route('shop.profile')
                    ->with('error', 'Please add a shipping address before proceeding to checkout');
            }

            // Calculate total weight
            $totalWeight = $selectedItems->sum(function($item) {
                return $item->productCombination->product->weight * $item->quantity;
            });

            // Static shipping costs for testing
            $shippingCosts = [
                'jne' => [
                    [
                        'service' => 'REG',
                        'description' => 'Regular Service',
                        'cost' => 15000,
                        'etd' => '2-3 days'
                    ],
                    [
                        'service' => 'YES',
                        'description' => 'Express Service',
                        'cost' => 25000,
                        'etd' => '1-2 days'
                    ]
                ],
                'pos' => [
                    [
                        'service' => 'REG',
                        'description' => 'Regular Service',
                        'cost' => 12000,
                        'etd' => '3-4 days'
                    ],
                    [
                        'service' => 'KILAT',
                        'description' => 'Express Service',
                        'cost' => 20000,
                        'etd' => '1-2 days'
                    ]
                ],
                'tiki' => [
                    [
                        'service' => 'REG',
                        'description' => 'Regular Service',
                        'cost' => 18000,
                        'etd' => '2-3 days'
                    ],
                    [
                        'service' => 'ONS',
                        'description' => 'Express Service',
                        'cost' => 28000,
                        'etd' => '1-2 days'
                    ]
                ]
            ];

            // Get active payment methods
            $paymentMethods = PaymentMethod::active()->get()->groupBy('type')->map(function($methods) {
                return $methods->mapWithKeys(function($method) {
                    return [$method->code => [
                        'name' => $method->name,
                        'description' => $method->description,
                        'config' => $method->config
                    ]];
                });
            })->toArray();

            // Calculate totals
            $subtotal = $selectedItems->sum(function($item) {
                return $item->productCombination->price * $item->quantity;
            });

            $discount = 0; // You can implement coupon logic here
            $total = $subtotal - $discount;

            return view('shop.checkout', compact(
                'selectedItems',
                'address',
                'shippingCosts',
                'paymentMethods',
                'discount',
                'subtotal',
                'total'
            ));

        } catch (\Exception $e) {
            \Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('shop.cart')
                ->with('error', 'Failed to load checkout page: ' . $e->getMessage());
        }
    }

    /**
     * Get shipping cost from RajaOngkir
     */
    public function getShippingCost(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'weight' => 'required|numeric',
            'courier' => 'required|in:jne,pos,tiki'
        ]);

        $origin = config('services.rajaongkir.store.city_id');
        $destination = $request->destination;
        $weight = $request->weight;
        $courier = $request->courier;

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.rajaongkir.api_key'),
                'Accept' => 'application/json',
            ])->post(config('services.rajaongkir.tariff_url') . '/cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to get shipping cost'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request)
    {
        $request->validate([
            'province_id' => 'required'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.rajaongkir.api_key')
            ])->get(config('services.rajaongkir.destination_url') . '/cities', [
                'province_id' => $request->province_id
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to get cities'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cities: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get districts by city
     */
    public function getDistricts(Request $request)
    {
        $request->validate([
            'city_id' => 'required'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.rajaongkir.api_key')
            ])->get(config('services.rajaongkir.destination_url') . '/districts', [
                'city_id' => $request->city_id
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()['data']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to get districts'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get districts: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process the checkout and create order
     */
    public function process(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate request
            $request->validate([
                'shipping_address_id' => 'required|exists:addresses,id',
                'shipping_method' => 'required|string',
                'shipping_cost' => 'required|numeric|min:0',
                'payment_method' => 'required|exists:payment_methods,code',
                'notes' => 'nullable|string|max:500'
            ]);

            // Get selected items from session
            $selectedItemIds = session('checkout_items');
            if (!$selectedItemIds) {
                return redirect()->route('shop.cart')
                    ->with('error', 'No items selected for checkout');
            }

            // Get cart items
            $cartItems = CartItem::whereIn('id', $selectedItemIds)
                ->whereHas('cart', function($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with(['productCombination.product'])
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('shop.cart')
                    ->with('error', 'No items found in cart');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->productCombination->price * $item->quantity;
            });

            // Get shipping cost from request
            $shippingCost = (int) $request->shipping_cost;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'address_id' => $request->shipping_address_id,
                'order_number' => 'ORD-' . time(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $subtotal + $shippingCost,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            // Create order details and reduce stock
            foreach ($cartItems as $item) {
                $combination = $item->productCombination;

                // Create order detail
                $order->details()->create([
                    'product_combination_id' => $combination->id,
                    'quantity' => $item->quantity,
                    'price' => $combination->price,
                    'subtotal' => $combination->price * $item->quantity
                ]);

                // Reduce stock
                $combination->decreaseStock($item->quantity);
            }

            // Get payment method
            $paymentMethod = PaymentMethod::where('code', $request->payment_method)->firstOrFail();

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $paymentMethod->code,
                'amount' => $order->total,
                'status' => 'pending'
            ]);

            // Add payment details based on method
            $payment->update([
                'payment_proof' => json_encode([
                    'account_name' => $paymentMethod->getConfigValue('account_name'),
                    'account_number' => $paymentMethod->getConfigValue('account_number')
                ])
            ]);

            // Clear selected cart items
            CartItem::whereIn('id', $selectedItemIds)->delete();

            // Clear checkout items from session
            session()->forget('checkout_items');

            DB::commit();

            return redirect()->route('shop.order.success', $order->id)
                ->with('success', 'Order placed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout process error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to process order: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function success($orderId)
    {
        $order = Order::with(['payment', 'address', 'details.productCombination.product'])
                     ->where('user_id', Auth::id())
                     ->findOrFail($orderId);

        return view('shop.order-success', compact('order'));
    }
}