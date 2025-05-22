@extends('layouts.layoutmaster')

@section('title', 'Categories Management')

@section('css')
<style>
    .batch-action-bar {
        display: none;
        background-color: #f8f9fa;
        padding: 10px 15px;
        border-radius: 4px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Categories List</h4>
                <div class="d-flex justify-content-end">
                    <div class="btn-group me-2">
                        <a href="{{ route('admin.categories.trash') }}" class="btn btn-secondary">
                            <i class="bi bi-trash"></i> View Trash
                            @if($trashedCount > 0)
                                <span class="badge bg-danger">{{ $trashedCount }}</span>
                            @endif
                        </a>
                        @if($trashedCount > 0)
                            <a href="{{ route('admin.categories.trash') }}" class="btn btn-danger">
                                <i class="bi bi-trash-fill"></i> Empty Trash
                            </a>
                        @endif
                    </div>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New Category
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <!-- Batch Actions Bar -->
                    <div class="batch-action-bar mb-3" id="batchActionBar">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="selected-count">0</span> categories selected
                            </div>
                            <div>
                                <form action="{{ route('admin.categories.batch-delete') }}" method="POST" id="batchDeleteForm">
                                    @csrf
                                    <div class="form-check form-switch d-inline-block me-3">
                                        <input class="form-check-input" type="checkbox" id="forceDeleteCheck" name="force_delete">
                                        <label class="form-check-label" for="forceDeleteCheck">Force Delete (remove category references from products)</label>
                                    </div>
                                    <button type="submit" class="btn btn-danger" id="batchDeleteBtn" onclick="return confirm('Are you sure you want to delete the selected categories?')">
                                        <i class="bi bi-trash"></i> Delete Selected
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="table1">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Description</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input category-checkbox" type="checkbox"
                                                   value="{{ $category->id }}"
                                                   name="category_checkbox"
                                                   data-has-products="{{ $category->products()->exists() ? 'true' : 'false' }}">
                                        </div>
                                    </td>
                                    <td>{{ $category->id }}</td>
                                    <td>
                                        @if($category->image)
                                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-width: 50px;">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ Str::limit($category->description, 50) }}</td>
                                    <td>
                                        <span class="badge bg-light-secondary">
                                            {{ $category->products()->count() }} products
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-{{ $category->is_active ? 'secondary' : 'success' }}" title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-toggle-{{ $category->is_active ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this category?'
                                                        + (this.form.querySelector('.force-delete-single').value === '1'
                                                           ? ' Products will be updated to have no category.'
                                                           : ''))">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                                <input type="hidden" name="force_delete" class="force-delete-single" value="0">
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
        const batchActionBar = document.getElementById('batchActionBar');
        const selectedCountEl = document.querySelector('.selected-count');
        const batchDeleteForm = document.getElementById('batchDeleteForm');
        const forceDeleteCheck = document.getElementById('forceDeleteCheck');

        // Function to update selected count and show/hide batch action bar
        function updateSelectedCount() {
            const selectedCount = document.querySelectorAll('.category-checkbox:checked').length;
            selectedCountEl.textContent = selectedCount;

            if (selectedCount > 0) {
                batchActionBar.style.display = 'block';
            } else {
                batchActionBar.style.display = 'none';
            }
        }

        // Select all checkbox
        selectAllCheckbox.addEventListener('change', function() {
            categoryCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedCount();
        });

        // Individual checkboxes
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Update "select all" checkbox state
                const allChecked = document.querySelectorAll('.category-checkbox:checked').length === categoryCheckboxes.length;
                selectAllCheckbox.checked = allChecked;

                updateSelectedCount();
            });
        });

        // Handle batch delete form submission
        batchDeleteForm.addEventListener('submit', function(e) {
            const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                e.preventDefault();
                alert('Please select at least one category to delete.');
                return;
            }

            // Check if any selected categories have products
            let hasProducts = false;
            selectedCheckboxes.forEach(checkbox => {
                if (checkbox.getAttribute('data-has-products') === 'true') {
                    hasProducts = true;
                }
            });

            // If categories have products and force delete is not checked, confirm
            if (hasProducts && !forceDeleteCheck.checked) {
                if (!confirm('Some selected categories have products. Enable force delete to remove these categories?')) {
                    e.preventDefault();
                    return;
                }
                forceDeleteCheck.checked = true;
            }

            // Add selected category IDs as hidden inputs
            selectedCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'categories[]';
                input.value = checkbox.value;
                batchDeleteForm.appendChild(input);
            });
        });

        // Handle force delete for single category
        forceDeleteCheck.addEventListener('change', function() {
            document.querySelectorAll('.force-delete-single').forEach(input => {
                input.value = forceDeleteCheck.checked ? '1' : '0';
            });
        });

        // Initialize state
        updateSelectedCount();
    });
</script>
@endsection