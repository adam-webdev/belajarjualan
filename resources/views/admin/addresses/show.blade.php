@extends('layouts.layoutmaster')

@section('title', 'Address Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Address Details</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.addresses.edit', $address) }}" class="btn btn-warning me-1">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="fw-bold">User:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->user->name }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Recipient Name:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->recipient_name }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Phone:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->phone }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Province:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->province }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">City:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->city }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">District:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->district }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Postal Code:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->postal_code }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Address Detail:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->address_detail }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Default Address:</label>
                        </div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $address->is_default ? 'success' : 'secondary' }}">
                                {{ $address->is_default ? 'Yes' : 'No' }}
                            </span>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Created At:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->created_at->format('d M Y H:i') }}</p>
                        </div>

                        <div class="col-md-4">
                            <label class="fw-bold">Last Updated:</label>
                        </div>
                        <div class="col-md-8">
                            <p>{{ $address->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection