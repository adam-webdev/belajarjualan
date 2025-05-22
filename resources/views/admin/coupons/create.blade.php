@extends('layouts.layoutmaster')

@section('title', 'Create Coupon')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{asset('assets/vendors/flatpickr/flatpickr.min.css')}}">
<style>
    .btn i {
        font-size: 1rem;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Coupon</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('admin.coupons.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Basic Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Code</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <div class="input-group">
                                                            <input type="text" id="code" class="form-control @error('code') is-invalid @enderror"
                                                                name="code" value="{{ old('code') }}" placeholder="Enter coupon code">
                                                            <button class="btn btn-outline-secondary" type="button" id="generate-code">
                                                                <i class="bi bi-magic"></i> Generate
                                                            </button>
                                                        </div>
                                                        @error('code')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Name</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name') }}" placeholder="Enter coupon name">
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Description</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                                            name="description" rows="3" placeholder="Enter coupon description">{{ old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Type</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="form-select @error('type') is-invalid @enderror" name="type" id="type">
                                                            <option value="">Select Type</option>
                                                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                                                            <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                                        </select>
                                                        @error('type')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Value</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="value-label">Rp</span>
                                                            <input type="text" id="value" class="form-control @error('value') is-invalid @enderror"
                                                                name="value" value="{{ old('value') }}" placeholder="Enter coupon value">
                                                        </div>
                                                        @error('value')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Status</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                                {{ old('is_active', true) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Active</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Usage Restrictions</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Min. Purchase</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" id="min_purchase" class="form-control @error('min_purchase') is-invalid @enderror"
                                                                name="min_purchase" value="{{ old('min_purchase') }}" placeholder="Enter minimum purchase amount">
                                                        </div>
                                                        <small class="text-muted">Leave empty for no minimum</small>
                                                        @error('min_purchase')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3" id="max-discount-container" style="display: none;">
                                                    <div class="col-md-4">
                                                        <label>Max. Discount</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="text" id="max_discount" class="form-control @error('max_discount') is-invalid @enderror"
                                                                name="max_discount" value="{{ old('max_discount') }}" placeholder="Enter maximum discount amount">
                                                        </div>
                                                        <small class="text-muted">For percentage coupons only</small>
                                                        @error('max_discount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Max. Uses</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="number" id="max_uses" class="form-control @error('max_uses') is-invalid @enderror"
                                                            name="max_uses" value="{{ old('max_uses') }}" placeholder="Enter maximum number of uses">
                                                        <small class="text-muted">Leave empty for unlimited</small>
                                                        @error('max_uses')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Max. Uses Per User</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="number" id="max_uses_per_user" class="form-control @error('max_uses_per_user') is-invalid @enderror"
                                                            name="max_uses_per_user" value="{{ old('max_uses_per_user') }}" placeholder="Enter maximum uses per user">
                                                        <small class="text-muted">Leave empty for unlimited</small>
                                                        @error('max_uses_per_user')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Valid From</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" id="starts_at" class="form-control flatpickr-date @error('starts_at') is-invalid @enderror"
                                                            name="starts_at" value="{{ old('starts_at') }}" placeholder="YYYY-MM-DD (e.g., 2025-03-04)">
                                                        <small class="text-muted">Leave empty to start immediately</small>
                                                        @error('starts_at')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Valid Until</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <input type="text" id="expires_at" class="form-control flatpickr-date @error('expires_at') is-invalid @enderror"
                                                            name="expires_at" value="{{ old('expires_at') }}" placeholder="YYYY-MM-DD (e.g., 2025-03-04)">
                                                        <small class="text-muted">Leave empty for no expiry</small>
                                                        @error('expires_at')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Applicable Categories</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="choices form-select @error('categories') is-invalid @enderror" name="categories[]" multiple>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted">Select none for all categories</small>
                                                        @error('categories')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-4">
                                                        <label>Applicable Products</label>
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <select class="choices form-select @error('products') is-invalid @enderror" name="products[]" multiple>
                                                            @foreach($products as $product)
                                                                <option value="{{ $product->id }}" {{ in_array($product->id, old('products', [])) ? 'selected' : '' }}>
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <small class="text-muted">Select none for all products</small>
                                                        @error('products')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="{{asset('assets/vendors/flatpickr/flatpickr.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize flatpickr date pickers
        flatpickr(".flatpickr-date", {
            enableTime: false,
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
        });

        // Initialize Choices.js for multi-select
        if (typeof Choices !== 'undefined') {
            const choicesElements = document.querySelectorAll('.choices');
            if (choicesElements.length > 0) {
                choicesElements.forEach(element => {
                    new Choices(element, {
                        removeItemButton: true,
                        searchEnabled: true,
                        itemSelectText: '',
                    });
                });
            }
        }

        // Handle type change to show/hide max discount
        const typeSelect = document.getElementById('type');
        const maxDiscountContainer = document.getElementById('max-discount-container');
        const valueLabel = document.getElementById('value-label');

        function updateDiscountVisibility() {
            if (typeSelect.value === 'percentage') {
                maxDiscountContainer.style.display = 'flex';
                valueLabel.innerText = '%';
            } else {
                maxDiscountContainer.style.display = 'none';
                valueLabel.innerText = 'Rp';
            }
        }

        typeSelect.addEventListener('change', updateDiscountVisibility);

        // Call once on page load
        updateDiscountVisibility();

        // Handle generate code button
        document.getElementById('generate-code').addEventListener('click', function() {
            fetch('{{ route('admin.coupons.generate-code') }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('code').value = data.code;
                })
                .catch(error => {
                    console.error('Error generating code:', error);
                });
        });
    });
</script>
@endsection