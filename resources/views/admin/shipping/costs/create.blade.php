@extends('layouts.layoutmaster')

@section('title', 'Create Shipping Cost')

@section('content')
<section id="basic-horizontal-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Shipping Cost</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('admin.shipping.costs.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Shipping Method</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="choices form-select @error('shipping_method_id') is-invalid @enderror" name="shipping_method_id">
                                            <option value="">Select Shipping Method</option>
                                            @foreach($shippingMethods as $method)
                                                <option value="{{ $method->id }}" {{ old('shipping_method_id') == $method->id ? 'selected' : '' }}>
                                                    {{ $method->name }} ({{ $method->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('shipping_method_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Province</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="province" class="form-control @error('province') is-invalid @enderror"
                                            name="province" value="{{ old('province') }}" placeholder="Enter province name">
                                        @error('province')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>City</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="city" class="form-control @error('city') is-invalid @enderror"
                                            name="city" value="{{ old('city') }}" placeholder="Enter city name">
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Cost</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" id="cost" class="form-control @error('cost') is-invalid @enderror"
                                                name="cost" value="{{ old('cost') }}" placeholder="Enter shipping cost">
                                        </div>
                                        @error('cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                        <a href="{{ route('admin.shipping.costs.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select with Choices.js
        if (typeof Choices !== 'undefined') {
            new Choices(document.querySelector('select[name="shipping_method_id"]'), {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true
            });
        }
    });
</script>
@endsection