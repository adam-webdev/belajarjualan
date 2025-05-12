@extends('layouts.layoutmaster')

@section('title', 'Edit Address')

@section('content')
<section id="basic-horizontal-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Address</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('admin.addresses.update', $address) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>User</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="choices form-select @error('user_id') is-invalid @enderror" name="user_id">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id', $address->user_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Recipient Name</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror"
                                            name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" placeholder="Enter recipient name">
                                        @error('recipient_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Phone</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone', $address->phone) }}" placeholder="Enter phone number">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Province</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="province" class="form-control @error('province') is-invalid @enderror"
                                            name="province" value="{{ old('province', $address->province) }}" placeholder="Enter province">
                                        @error('province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>City</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="city" class="form-control @error('city') is-invalid @enderror"
                                            name="city" value="{{ old('city', $address->city) }}" placeholder="Enter city">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>District</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="district" class="form-control @error('district') is-invalid @enderror"
                                            name="district" value="{{ old('district', $address->district) }}" placeholder="Enter district">
                                        @error('district')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Postal Code</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror"
                                            name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" placeholder="Enter postal code">
                                        @error('postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Address Detail</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <textarea id="address_detail" class="form-control @error('address_detail') is-invalid @enderror"
                                            name="address_detail" rows="3" placeholder="Enter address detail">{{ old('address_detail', $address->address_detail) }}</textarea>
                                        @error('address_detail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Default Address</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_default" value="1"
                                                {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                                            <label class="form-check-label">Set as default address</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                        <a href="{{ route('admin.addresses.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
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
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select with Choices.js
        if (typeof Choices !== 'undefined') {
            new Choices(document.querySelector('select[name="user_id"]'), {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true
            });
        }
    });
</script>
@endsection