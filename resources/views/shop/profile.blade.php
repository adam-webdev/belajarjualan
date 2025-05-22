@extends('layouts.frontend.master')

@section('title', 'My Profile - Apriori Shop')

@section('styles')
<style>
    .avatar-circle {
        width: 80px;
        height: 80px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }

    .initials {
        font-size: 32px;
        font-weight: 600;
        color: #495057;
    }

    .list-group-item.active {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .list-group-item {
        border-left: none;
        border-right: none;
    }

    .list-group-item:first-child {
        border-top: none;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .btn-outline-primary:hover {
        color: #fff;
    }

    .badge {
        font-weight: 500;
    }

    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .product-card {
        transition: transform 0.2s;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mb-3">
                            <span class="initials">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="list-group">
                        <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                            <i class="bi bi-person me-2"></i> Profile
                        </a>
                        <a href="#addresses" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="bi bi-geo-alt me-2"></i> Addresses
                        </a>
                        <a href="#orders" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="bi bi-bag me-2"></i> Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('shop.profile.update') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>

                            <hr class="my-4">

                            <h5 class="mb-3">Change Password</h5>
                            <form action="{{ route('shop.profile.change-password') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation">
                                </div>

                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Addresses Tab -->
                <div class="tab-pane fade" id="addresses">
                    <div class="card">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">My Addresses</h5>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus"></i> Add New Address
                            </button>
                        </div>
                        <div class="card-body">
                            @if($addresses->count() > 0)
                                <div class="row">
                                    @foreach($addresses as $address)
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0">{{ $address->recipient_name }}</h6>
                                                        @if($address->is_default)
                                                            <span class="badge bg-primary">Default</span>
                                                        @endif
                                                    </div>
                                                    <p class="card-text mb-2">{{ $address->phone }}</p>
                                                    <p class="card-text small text-muted mb-3">
                                                        {{ $address->address_detail }}, {{ $address->district }},
                                                        {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                                    </p>
                                                    <div class="d-flex gap-2">
                                                        @if(!$address->is_default)
                                                            <form action="{{ route('shop.profile.address.set-default', $address->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                    Set as Default
                                                                </button>
                                                            </form>
                                                        @endif
                                                        <button type="button" class="btn btn-sm btn-outline-secondary edit-address"
                                                                data-address="{{ $address->id }}"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editAddressModal">
                                                            Edit
                                                        </button>
                                                        <form action="{{ route('shop.profile.address.delete', $address->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this address?')">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-geo-alt fs-1 text-muted"></i>
                                    <p class="mt-3">You haven't added any addresses yet.</p>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                        Add Your First Address
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div class="tab-pane fade" id="orders">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">My Orders</h5>
                        </div>
                        <div class="card-body">
                            @if($orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td>#{{ $order->order_number }}</td>
                                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'completed' ? 'success' : 'secondary')) }}">
                                                            {{ ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('shop.profile.order.detail', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i> View Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $orders->links() }}
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-bag fs-1 text-muted"></i>
                                    <p class="mt-3">You haven't placed any orders yet.</p>
                                    <a href="{{ url('/') }}" class="btn btn-primary">
                                        Start Shopping
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shop.profile.address.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient_name" class="form-label">Recipient Name</label>
                        <input type="text" class="form-control @error('recipient_name') is-invalid @enderror"
                               id="recipient_name" name="recipient_name" value="{{ old('recipient_name') }}">
                        @error('recipient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="province" class="form-label">Province</label>
                        <input type="text" class="form-control @error('province') is-invalid @enderror"
                               id="province" name="province" value="{{ old('province') }}">
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                               id="city" name="city" value="{{ old('city') }}">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="district" class="form-label">District</label>
                        <input type="text" class="form-control @error('district') is-invalid @enderror"
                               id="district" name="district" value="{{ old('district') }}">
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                               id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address_detail" class="form-label">Address Detail</label>
                        <textarea class="form-control @error('address_detail') is-invalid @enderror"
                                  id="address_detail" name="address_detail" rows="3">{{ old('address_detail') }}</textarea>
                        @error('address_detail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_default">Set as default address</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAddressForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_recipient_name" class="form-label">Recipient Name</label>
                        <input type="text" class="form-control @error('recipient_name') is-invalid @enderror"
                               id="edit_recipient_name" name="recipient_name">
                        @error('recipient_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="edit_phone" name="phone">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_province" class="form-label">Province</label>
                        <input type="text" class="form-control @error('province') is-invalid @enderror"
                               id="edit_province" name="province">
                        @error('province')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_city" class="form-label">City</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                               id="edit_city" name="city">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_district" class="form-label">District</label>
                        <input type="text" class="form-control @error('district') is-invalid @enderror"
                               id="edit_district" name="district">
                        @error('district')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_postal_code" class="form-label">Postal Code</label>
                        <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                               id="edit_postal_code" name="postal_code">
                        @error('postal_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_address_detail" class="form-label">Address Detail</label>
                        <textarea class="form-control @error('address_detail') is-invalid @enderror"
                                  id="edit_address_detail" name="address_detail" rows="3"></textarea>
                        @error('address_detail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="edit_is_default" name="is_default" value="1">
                            <label class="form-check-label" for="edit_is_default">Set as default address</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    // Handle edit address button click
    $('.edit-address').click(function() {
        var addressId = $(this).data('address');

        // Fetch address data
        $.get('/shop/profile/address/' + addressId + '/edit', function(data) {
            // Update form action
            $('#editAddressForm').attr('action', '/shop/profile/address/' + addressId);

            // Fill form fields
            $('#edit_recipient_name').val(data.recipient_name);
            $('#edit_phone').val(data.phone);
            $('#edit_province').val(data.province);
            $('#edit_city').val(data.city);
            $('#edit_district').val(data.district);
            $('#edit_postal_code').val(data.postal_code);
            $('#edit_address_detail').val(data.address_detail);
            $('#edit_is_default').prop('checked', data.is_default);
        });
    });
});
</script>
@endpush