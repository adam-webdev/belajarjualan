@extends('layouts.layoutmaster')

@section('title', 'Edit Category')

@section('content')
<section id="basic-horizontal-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Category</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $category->name) }}" placeholder="Enter category name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Slug</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="slug" class="form-control @error('slug') is-invalid @enderror"
                                            name="slug" value="{{ old('slug', $category->slug) }}" placeholder="Enter category slug">
                                        @error('slug')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Description</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                            name="description" rows="3" placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Image</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        @if($category->image)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        @endif
                                        <input type="file" id="image" class="form-control @error('image') is-invalid @enderror"
                                            name="image" accept="image/*">
                                        <small class="text-muted">Leave empty to keep current image</small>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Status</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
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
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        let slug = this.value
            .toLowerCase()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        document.getElementById('slug').value = slug;
    });
</script>
@endsection