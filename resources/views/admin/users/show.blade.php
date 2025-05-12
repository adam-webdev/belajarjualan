@extends('layouts.layoutmaster')

@section('title', 'User Details')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">User Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning me-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">Details</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="addresses-tab" data-bs-toggle="tab" data-bs-target="#addresses" type="button" role="tab">Addresses</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wishlist-tab" data-bs-toggle="tab" data-bs-target="#wishlist" type="button" role="tab">Wishlist</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Reviews</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label class="fw-bold">Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <p>{{ $user->name }}</p>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-bold">Email:</label>
                                </div>
                                <div class="col-md-8">
                                    <p>{{ $user->email }}</p>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-bold">Phone:</label>
                                </div>
                                <div class="col-md-8">
                                    <p>{{ $user->phone }}</p>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-bold">Role:</label>
                                </div>
                                <div class="col-md-8">
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-bold">Status:</label>
                                </div>
                                <div class="col-md-8">
                                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-bold">Created At:</label>
                                </div>
                                <div class="col-md-8">
                                    <p>{{ $user->created_at->format('d M Y H:i') }}</p>
                                </div>

                                <div class="col-md-4">
                                    <label class="fw-bold">Last Updated:</label>
                                </div>
                                <div class="col-md-8">
                                    <p>{{ $user->updated_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Addresses Tab -->
                        <div class="tab-pane fade" id="addresses" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Recipient Name</th>
                                            <th>Phone</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>Province</th>
                                            <th>Postal Code</th>
                                            <th>Default</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->addresses as $address)
                                        <tr>
                                            <td>{{ $address->recipient_name }}</td>
                                            <td>{{ $address->phone }}</td>
                                            <td>{{ $address->address_detail }}</td>
                                            <td>{{ $address->city }}</td>
                                            <td>{{ $address->province }}</td>
                                            <td>{{ $address->postal_code }}</td>
                                            <td>
                                                <span class="badge bg-{{ $address->is_default ? 'success' : 'secondary' }}">
                                                    {{ $address->is_default ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No addresses found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->orders as $order)
                                        <tr>
                                            <td>{{ $order->order_number }}</td>
                                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ $order->status_label }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $order->payment_status === 'Paid' ? 'success' : ($order->payment_status === 'Failed' ? 'danger' : 'warning') }}">
                                                    {{ $order->payment_status }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No orders found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="wishlist" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Added Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->wishlists as $item)
                                        <tr>
                                            <td>{{ $item->productCombination->product->name ?? 'Product not found' }}</td>
                                            <td>Rp {{ number_format($item->productCombination->price ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No wishlist items found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="reviews" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->reviews as $review)
                                        <tr>
                                            <td>{{ $review->product->name ?? 'Product not found' }}</td>
                                            <td>{{ $review->rating_stars }}</td>
                                            <td>{{ Str::limit($review->comment, 30) }}</td>
                                            <td>{{ $review->created_at->format('d M Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $review->is_verified ? 'success' : 'warning' }}">
                                                    {{ $review->is_verified ? 'Verified' : 'Pending' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No reviews found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection