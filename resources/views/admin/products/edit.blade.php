@extends('layouts.layoutmaster')

@section('title', 'Edit Product')

@section('css')
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Product</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-1">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info">
                        <i class="bi bi-eye"></i> View Details
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
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
                                                <option value="{{ $category->id }}" {{ (old('category_id', $product->category_id) == $category->id) ? 'selected' : '' }}>
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
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name) }}" placeholder="Product Name">
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
                                            <input type="text" class="form-control @error('base_price') is-invalid @enderror"
                                                name="base_price" value="{{ old('base_price', $product->base_price) }}"
                                                placeholder="Base Price">
                                            @error('base_price')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">This price will be used if the product has no variants.</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label>Description</label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea id="summernote" class="form-control @error('description') is-invalid @enderror" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title">Product Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="has_variant" name="has_variant" value="1" {{ old('has_variant', $product->has_variant) == 1 ? 'checked' : '' }} {{ $product->options()->exists() ? 'disabled' : '' }}>
                                            <label class="form-check-label" for="has_variant">Has Variants</label>
                                        </div>
                                        @if($product->options()->exists())
                                        <div class="alert alert-info">
                                            <small>This product has options/variants. To disable variants, please remove all options first.</small>
                                        </div>
                                        @else
                                        <small class="text-muted d-block mb-3">
                                            Enable this if your product has variants like size, color, etc.
                                        </small>
                                        @endif

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Active Status</label>
                                        </div>
                                        <small class="text-muted d-block mb-3">
                                            Inactive products won't be shown to customers.
                                        </small>
                                    </div>
                                </div>

                                <!-- Current Images Preview -->
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title">Current Images</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @forelse($product->images as $image)
                                            <div class="col-6 mb-2">
                                                <div class="border rounded p-1">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid" alt="{{ $product->name }}">
                                                    @if($image->is_primary)
                                                    <div class="text-center mt-1">
                                                        <span class="badge bg-success">Primary</span>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @empty
                                            <div class="col-12">
                                                <p class="text-muted">No images for this product</p>
                                            </div>
                                            @endforelse
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('admin.products.show', $product) }}#images" class="btn btn-sm btn-outline-primary">
                                                Manage Images
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">
                                        <i class="bi bi-save"></i> Update Product
                                    </button>
                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-light-secondary me-1 mb-1">
                                        <i class="bi bi-x"></i> Cancel
                                    </a>
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

        // Check for session error messages
        @if(session('error'))
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: "#ff6b6b",
            }).showToast();
        @endif

        @if(session('success'))
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "center",
                backgroundColor: "#4fbe87",
            }).showToast();
        @endif
    });
</script>
@endsection