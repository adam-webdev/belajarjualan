@extends('layouts.layoutmaster')

@section('title', 'Create New Product')

@section('css')
<style>
.choices__inner {
    background-color: #fff;
    border-radius: 0.3rem;
    border: 1px solid #dce7f1;
    min-height: 40px;
}
.choices__list--dropdown {
    z-index: 999;
}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create New Product</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label>Category</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="category_id" class="choices form-select @error('category_id') is-invalid @enderror" id="category_id">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Product Name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label>Base Price</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" min="0" step="0.01" class="form-control @error('base_price') is-invalid @enderror" name="base_price" value="{{ old('base_price') }}" placeholder="Base Price">
                                        </div>
                                        @error('base_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">This price will be used if the product has no variants.</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label>Description</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description" rows="5">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label>Product Images</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" name="images[]" multiple accept="image/*">
                                        @error('images.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">You can upload multiple images. The first one will be set as the primary image.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title">Product Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="has_variant" name="has_variant" {{ old('has_variant') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_variant">Has Variants</label>
                                        </div>
                                        <small class="text-muted d-block mb-3">
                                            Enable this if your product has variants like size, color, etc.
                                        </small>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active Status</label>
                                        </div>
                                        <small class="text-muted d-block mb-3">
                                            Inactive products won't be shown to customers.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">
                                        <i class="bi bi-save"></i> Save Product
                                    </button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">
                                        <i class="bi bi-x"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
<script src="{{asset('assets/vendors/summernote/summernote-lite.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize editor
        $('#summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Initialize select with Choices.js
        if (typeof Choices !== 'undefined') {
            new Choices(document.getElementById('category_id'), {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true
            });
        }
    });
</script>
@endsection