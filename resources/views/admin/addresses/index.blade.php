@extends('layouts.layoutmaster')

@section('title', 'Addresses Management')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Addresses List</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.addresses.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New Address
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="addresses-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Recipient Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Province</th>
                                    <th>Postal Code</th>
                                    <th>Default</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($addresses as $address)
                                <tr>
                                    <td>{{ $address->id }}</td>
                                    <td>{{ $address->user->name }}</td>
                                    <td>{{ $address->recipient_name }}</td>
                                    <td>{{ $address->phone }}</td>
                                    <td>{{ $address->address_detail }}</td>
                                    <td>{{ $address->city }}</td>
                                    <td>{{ $address->province }}</td>
                                    <td>{{ $address->postal_code }}</td>
                                    <td>
                                        <span class="badge bg-{{ $address->is_default ? 'success' : 'secondary' }}">
                                            {{ $address->is_default ? 'Yes' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.addresses.show', $address) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.addresses.edit', $address) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.addresses.destroy', $address) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#addresses-table').DataTable();
    });
</script>
@endsection