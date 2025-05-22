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
.alert {
    margin-bottom: 1rem;
}
.required:after {
    content: " *";
    color: red;
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
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <strong>Mohon perbaiki kesalahan berikut:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label class="required">Kategori</label>
                                    </div>
                                    <div class="col-md-9">
                                        <select name="category_id" class="choices form-select @error('category_id') is-invalid @enderror" id="category_id">
                                            <option value="">Pilih Kategori</option>
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
                                        <label class="required">Nama Produk</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Masukkan nama produk">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label class="required">Harga Dasar</label>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" min="0" step="0.01" class="form-control @error('base_price') is-invalid @enderror" name="base_price" value="{{ old('base_price') }}" placeholder="Masukkan harga dasar">
                                        </div>
                                        @error('base_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Harga ini akan digunakan jika produk tidak memiliki varian.</small>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <div class="col-md-3">
                                        <label class="required">Deskripsi</label>
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
                                        <label>Gambar Produk</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" class="form-control @error('images.*') is-invalid @enderror" name="images[]" multiple accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/jfif">
                                        @error('images.*')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Anda dapat mengunggah beberapa gambar. Gambar pertama akan diatur sebagai gambar utama. Ukuran maksimal: 4MB. Format yang didukung: JPEG, PNG, JPG, GIF, WebP, JFIF.</small>
                                        <div id="image-preview" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title">Pengaturan Produk</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="has_variant" name="has_variant" value="1" {{ old('has_variant') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="has_variant">Memiliki Varian</label>
                                        </div>
                                        <small class="text-muted d-block mb-3">
                                            Aktifkan jika produk memiliki varian seperti ukuran, warna, dll.
                                        </small>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">Status Aktif</label>
                                        </div>
                                        <small class="text-muted d-block mb-3">
                                            Produk yang tidak aktif tidak akan ditampilkan ke pelanggan.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="form-group text-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">
                                        <i class="bi bi-save"></i> Simpan Produk
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

        // Image preview functionality
        const imageInput = document.querySelector('input[name="images[]"]');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function() {
            imagePreview.innerHTML = '';

            if (this.files) {
                Array.from(this.files).forEach(file => {
                    if (file.size > 4 * 1024 * 1024) { // 4MB in bytes
                        alert(`File ${file.name} is too large. Maximum size is 4MB.`);
                        this.value = '';
                        imagePreview.innerHTML = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'mb-2';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;">
                            <div class="small text-muted">${file.name} (${(file.size / 1024 / 1024).toFixed(2)}MB)</div>
                        `;
                        imagePreview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });

        // Form submission handling
        const form = document.getElementById('createProductForm');
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
        });
    });
</script>
@endsection