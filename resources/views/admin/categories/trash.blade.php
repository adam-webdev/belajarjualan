@extends('layouts.layoutmaster')

@section('title', 'Trashed Categories')

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
                <h4 class="card-title">Trashed Categories</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Back to Categories
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
                            <div class="d-flex gap-2">
                                <form action="{{ route('admin.categories.batch-restore') }}" method="POST" id="batchRestoreForm">
                                    @csrf
                                    <button type="submit" class="btn btn-success" id="batchRestoreBtn" onclick="return confirm('Are you sure you want to restore the selected categories?')">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restore Selected
                                    </button>
                                </form>
                                <form action="{{ route('admin.categories.batch-force-delete') }}" method="POST" id="batchForceDeleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" id="batchForceDeleteBtn" onclick="return confirm('Are you sure you want to permanently delete the selected categories? This action cannot be undone.')">
                                        <i class="bi bi-x-circle"></i> Permanently Delete Selected
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($trashedCategories->isEmpty())
                    <div class="alert alert-info">
                        <p class="mb-0">No deleted categories found.</p>
                    </div>
                    @else
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
                                    <th>Products</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trashedCategories as $category)
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input category-checkbox" type="checkbox"
                                                   value="{{ $category->id }}"
                                                   name="category_checkbox">
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
                                    <td>
                                        <span class="badge bg-light-secondary">
                                            {{ $category->products()->count() }} products
                                        </span>
                                    </td>
                                    <td>{{ $category->deleted_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Restore">
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to permanently delete this category? This action cannot be undone.')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
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
        const batchRestoreForm = document.getElementById('batchRestoreForm');
        const batchForceDeleteForm = document.getElementById('batchForceDeleteForm');

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
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                categoryCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateSelectedCount();
            });
        }

        // Individual checkboxes
        categoryCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Update "select all" checkbox state
                if (selectAllCheckbox) {
                    const allChecked = document.querySelectorAll('.category-checkbox:checked').length === categoryCheckboxes.length;
                    selectAllCheckbox.checked = allChecked;
                }
                updateSelectedCount();
            });
        });

        // Function to add selected categories to a form
        function addSelectedCategoriesToForm(form) {
            const selectedCheckboxes = document.querySelectorAll('.category-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                return false;
            }

            // Add selected category IDs as hidden inputs
            selectedCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'categories[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });

            return true;
        }

        // Handle batch restore form submission
        if (batchRestoreForm) {
            batchRestoreForm.addEventListener('submit', function(e) {
                if (!addSelectedCategoriesToForm(batchRestoreForm)) {
                    e.preventDefault();
                    alert('Please select at least one category to restore.');
                }
            });
        }

        // Handle batch force delete form submission
        if (batchForceDeleteForm) {
            batchForceDeleteForm.addEventListener('submit', function(e) {
                if (!addSelectedCategoriesToForm(batchForceDeleteForm)) {
                    e.preventDefault();
                    alert('Please select at least one category to permanently delete.');
                }
            });
        }

        // Initialize state
        updateSelectedCount();
    });
</script>
@endsection