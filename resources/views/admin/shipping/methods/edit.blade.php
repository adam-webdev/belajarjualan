@extends('layouts.layoutmaster')

@section('title', 'Edit Shipping Method')

@section('content')
<section id="basic-horizontal-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Shipping Method</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('admin.shipping.methods.update', $method) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $method->name) }}" placeholder="Enter shipping method name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Code</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="code" class="form-control @error('code') is-invalid @enderror"
                                            name="code" value="{{ old('code', $method->code) }}" placeholder="Enter shipping method code">
                                        <small class="text-muted">A unique code for this shipping method (e.g., jne, pos, tiki)</small>
                                        @error('code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Default Cost</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control @error('default_cost') is-invalid @enderror" id="default_cost" name="default_cost" value="{{ old('default_cost', $method->default_cost) }}" min="0" step="0.01">
                                        </div>
                                        @error('default_cost')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Default cost when no specific shipping cost is found.</div>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Status</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $method->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                        <a href="{{ route('admin.shipping.methods.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
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