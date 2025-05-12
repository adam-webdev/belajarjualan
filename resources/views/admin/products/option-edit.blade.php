@extends('layouts.layoutmaster')

@section('title', 'Edit Product Option')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Option: {{ $option->name }}</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.products.show', $option->product) }}#options" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Product
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Update Option</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.products.options.update', $option) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="option_name">Option Name</label>
                                            <input type="text" class="form-control @error('option_name') is-invalid @enderror"
                                                id="option_name" name="option_name" value="{{ old('option_name', $option->name) }}" required>
                                            @error('option_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">This is the name of the option (e.g. Size, Color)</small>
                                        </div>

                                        <div class="mb-3">
                                            <label>Existing Values</label>
                                            @foreach($option->values as $value)
                                            <div class="input-group mb-2">
                                                <input type="text" class="form-control"
                                                    name="existing_values[{{ $value->id }}]"
                                                    value="{{ $value->value }}" required>
                                                <a href="#" onclick="event.preventDefault(); document.getElementById('delete-value-{{ $value->id }}').submit();"
                                                    class="btn btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                            <form id="delete-value-{{ $value->id }}"
                                                action="{{ route('admin.products.option-values.destroy', $value->id) }}"
                                                method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endforeach
                                        </div>

                                        <div class="mb-3">
                                            <label>Add New Values</label>
                                            <div id="option_values_container">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="option_values[]" placeholder="New value">
                                                    <button type="button" class="btn btn-outline-secondary add-option-value">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted">Add new values here (optional)</small>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Update Option</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Add Single Value</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.products.option-values.store', $option) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="value">New Value</label>
                                            <input type="text" class="form-control @error('value') is-invalid @enderror"
                                                id="value" name="value" placeholder="Enter new value" required>
                                            @error('value')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Value</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card shadow-sm mt-4">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Option Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Product:</strong>
                                        <a href="{{ route('admin.products.show', $option->product) }}">
                                            {{ $option->product->name }}
                                        </a>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Total Values:</strong> {{ $option->values->count() }}
                                    </div>
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        Editing option values will not affect existing product combinations.
                                        However, deleting values used in combinations is not allowed.
                                    </div>
                                </div>
                            </div>
                        </div>
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
        // Handle adding option values
        document.querySelectorAll('.add-option-value').forEach(button => {
            button.addEventListener('click', function() {
                const container = document.getElementById('option_values_container');
                const newRow = document.createElement('div');
                newRow.className = 'input-group mb-2';
                newRow.innerHTML = `
                    <input type="text" class="form-control" name="option_values[]" placeholder="New value">
                    <button type="button" class="btn btn-outline-danger remove-option-value">
                        <i class="bi bi-dash"></i>
                    </button>
                `;
                container.appendChild(newRow);

                // Add event listener to the newly created remove button
                newRow.querySelector('.remove-option-value').addEventListener('click', function() {
                    container.removeChild(newRow);
                });
            });
        });
    });
</script>
@endsection