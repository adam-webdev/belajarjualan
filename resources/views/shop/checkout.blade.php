@extends('layouts.frontend.master')

@section('title', 'Checkout - Apriori Shop')

@push('css')
<style>
    .payment-details, .shipping-details {
        display: none;
    }
    .bank-details, .e-wallet-details-info {
        display: none;
    }
    .payment-option, .shipping-option {
        display: none;
    }
    .payment-option + label, .shipping-option + label {
        display: inline-block;
        padding: 15px;
        margin: 5px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100px;
        text-align: center;
        background: white;
    }
    .payment-option + label:hover, .shipping-option + label:hover {
        border-color: #28a745;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .payment-option:checked + label, .shipping-option:checked + label {
        border-color: #28a745;
        background-color: #f8fff9;
        box-shadow: 0 2px 5px rgba(40,167,69,0.2);
    }
    .payment-option + label img, .shipping-option + label img {
        width: 25px;
        height: 25px;
        object-fit: contain;
        margin-bottom: 8px;
    }
    .payment-option + label span, .shipping-option + label span {
        display: block;
        font-size: 11px;
        color: #555;
        margin-top: 5px;
    }
    .payment-options-container, .shipping-options-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding: 10px 0;
    }
    .shipping-cost {
        font-size: 12px;
        color: #28a745;
        font-weight: bold;
        margin-top: 3px;
    }
</style>
@endpush

@section('js')
<script>
$(document).ready(function() {
    // Handle payment method change
    $('select[name="payment_method"]').on('change', function() {
        const method = $(this).val();
        $('#bankTransferOptions, #eWalletOptions').hide();
        $('#bankAccountInfo, #eWalletAccountInfo').hide();

        if (method === 'bank_transfer') {
            $('#bankTransferOptions').show();
        } else if (method === 'e_wallet') {
            $('#eWalletOptions').show();
        }
    });

    // Handle bank selection
    $('#bankSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const accountName = selectedOption.data('account-name');
        const accountNumber = selectedOption.data('account-number');

        if (accountName && accountNumber) {
            $('#accountName').text(accountName);
            $('#accountNumber').text(accountNumber);
            $('#bankAccountInfo').show();
        } else {
            $('#bankAccountInfo').hide();
        }
    });

    // Handle e-wallet selection
    $('#eWalletSelect').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const accountNumber = selectedOption.data('account-number');

        if (accountNumber) {
            $('#eWalletNumber').text(accountNumber);
            $('#eWalletAccountInfo').show();
        } else {
            $('#eWalletAccountInfo').hide();
        }
    });

    // Handle shipping method change
    $('select[name="shipping_method"]').on('change', function() {
        const option = $(this).find('option:selected');
        const shippingCost = parseInt(option.data('cost')) || 0;
        const subtotal = {{ $subtotal }};
        const discount = {{ $discount }};
        const total = subtotal + shippingCost - discount;

        // Update hidden input
        $('#shippingCostInput').val(shippingCost);

        $('#shippingCost').text('Rp ' + shippingCost.toLocaleString('id-ID'));
        $('#totalAmount').text('Rp ' + total.toLocaleString('id-ID'));
    });

    // Handle form submission
    $('#checkout-form').on('submit', function(e) {
        e.preventDefault();

        const shippingMethod = $('select[name="shipping_method"]').val();
        const paymentMethod = $('select[name="payment_method"]').val();
        const shippingCost = parseInt($('#shippingCostInput').val()) || 0;

        if (!shippingMethod) {
            alert('Please select a shipping method');
            return;
        }

        if (!paymentMethod) {
            alert('Please select a payment method');
            return;
        }

        if (paymentMethod === 'bank_transfer' && !$('select[name="bank_name"]').val()) {
            alert('Please select a bank');
            return;
        }

        if (paymentMethod === 'e_wallet' && !$('select[name="e_wallet_provider"]').val()) {
            alert('Please select an e-wallet provider');
            return;
        }

        if (shippingCost <= 0) {
            alert('Please select a valid shipping method');
            return;
        }

        // Disable submit button
        $('#placeOrderBtn').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...'
        );

        // Submit form
        this.submit();
    });
});
</script>
@endsection

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.cart') }}">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <form id="checkout-form" action="{{ route('shop.checkout.process') }}" method="POST">
        @csrf
        <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
        <div class="row">
            <!-- Left Column - Cart Items & Shipping -->
            <div class="col-lg-8">
                <!-- Cart Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Selected Items</h5>
                    </div>
                    <div class="card-body">
                        @foreach($selectedItems as $item)
                        <div class="d-flex mb-3">
                            @if($item->productCombination->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->productCombination->product->images->first()->image_path) }}"
                                     class="rounded me-3" width="80" height="80"
                                     alt="{{ $item->productCombination->product->name }}">
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $item->productCombination->product->name }}</h6>
                                @if($item->productCombination->optionValues->count() > 0)
                                    <div class="small text-muted mb-1">
                                        @foreach($item->productCombination->optionValues as $optionValue)
                                            <span class="badge bg-light text-dark me-1">
                                                {{ $optionValue->option->name }}: {{ $optionValue->value }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="text-muted">
                                    Quantity: {{ $item->quantity }}
                                </div>
                                <div class="fw-bold">
                                    Rp {{ number_format($item->productCombination->price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        @if($address)
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="shipping_address_id"
                                           id="address{{ $address->id }}" value="{{ $address->id }}" checked>
                                    <label class="form-check-label" for="address{{ $address->id }}">
                                        <strong>{{ $address->name }}</strong><br>
                                        {{ $address->phone }}<br>
                                        {{ $address->address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                    </label>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Please add a shipping address in your profile.
                                <a href="{{ route('shop.profile') }}" class="alert-link">Add Address</a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Shipping Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Select Shipping Method</label>
                            <select class="form-select" name="shipping_method" required>
                                <option value="">Choose shipping method...</option>
                                @foreach($shippingCosts as $courier => $services)
                                    @foreach($services as $service)
                                        <option value="{{ $courier }}-{{ $service['service'] }}"
                                                data-cost="{{ $service['cost'] }}"
                                                data-etd="{{ $service['etd'] }}">
                                            {{ strtoupper($courier) }} {{ $service['service'] }} -
                                            Rp {{ number_format($service['cost'], 0, ',', '.') }}
                                            ({{ $service['etd'] }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Select Payment Method</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="">Choose payment method...</option>
                                @foreach($paymentMethods as $code => $method)
                                    <option value="{{ $code }}">{{ $method['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Bank Transfer Options -->
                        <div id="bankTransferOptions" class="mb-3" style="display: none;">
                            <label class="form-label">Select Bank</label>
                            <select class="form-select" name="bank_name" id="bankSelect">
                                <option value="">Choose bank...</option>
                                @foreach($paymentMethods['bank_transfer']['banks'] as $code => $name)
                                    <option value="{{ $name }}" data-account-name="TrifShop" data-account-number="{{ $code === 'bca' ? '1234567890' : ($code === 'mandiri' ? '0987654321' : ($code === 'bni' ? '1122334455' : '5566778899')) }}">
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="bankAccountInfo" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Bank Account Information</h6>
                                    <p class="mb-1">Account Name: <strong id="accountName"></strong></p>
                                    <p class="mb-0">Account Number: <strong id="accountNumber"></strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- E-Wallet Options -->
                        <div id="eWalletOptions" class="mb-3" style="display: none;">
                            <label class="form-label">Select E-Wallet</label>
                            <select class="form-select" name="e_wallet_provider" id="eWalletSelect">
                                <option value="">Choose e-wallet...</option>
                                @foreach($paymentMethods['e_wallet']['providers'] as $code => $name)
                                    <option value="{{ $name }}" data-account-number="08998083333">
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>

                            <div id="eWalletAccountInfo" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">E-Wallet Account Information</h6>
                                    <p class="mb-0">Account Number: <strong id="eWalletNumber"></strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Order Notes (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <textarea class="form-control" name="notes" rows="3"
                                      placeholder="Add any special instructions or notes for your order..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping Cost</span>
                                <span id="shippingCost">Rp 0</span>
                            </div>

                            @if($discount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount</span>
                                    <span>- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span id="totalAmount">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="placeOrderBtn">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection