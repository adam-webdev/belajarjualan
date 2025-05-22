@extends('layouts.layoutmaster')

@section('title', 'Flash Sales')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
<style>
    .btn i {
        font-size: 1rem;
        display: inline-block;
    }
    .progress {
        height: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Flash Sales List</h4>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Add New Flash Sale
                    </a>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="table1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>Items</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($flashSales as $flashSale)
                                <tr>
                                    <td>{{ $flashSale->id }}</td>
                                    <td>{{ $flashSale->name }}</td>
                                    <td>
                                        {{ $flashSale->start_time->format('d M Y H:i') }} -
                                        {{ $flashSale->end_time->format('d M Y H:i') }}
                                    </td>
                                    <td>
                                        @if($flashSale->isActiveNow)
                                            <span class="badge bg-success">Active Now</span>
                                        @elseif($flashSale->isUpcoming)
                                            <span class="badge bg-info">Upcoming</span>
                                        @elseif($flashSale->isExpired)
                                            <span class="badge bg-secondary">Expired</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" data-bs-toggle="tooltip" title="{{ $flashSale->timeRemaining }}">
                                            <div class="progress-bar
                                                @if($flashSale->isActiveNow) bg-success
                                                @elseif($flashSale->isUpcoming) bg-info
                                                @else bg-secondary @endif"
                                                role="progressbar"
                                                style="width: {{ $flashSale->progressPercent }}%"
                                                aria-valuenow="{{ $flashSale->progressPercent }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $flashSale->timeRemaining }}</small>
                                    </td>
                                    <td>
                                        {{ $flashSale->items_count ?? $flashSale->items->count() }} items
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.flash-sales.show', $flashSale) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.flash-sales.edit', $flashSale) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.flash-sales.toggle-status', $flashSale) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-{{ $flashSale->is_active ? 'secondary' : 'success' }}" title="{{ $flashSale->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-toggle-{{ $flashSale->is_active ? 'on' : 'off' }}"></i>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.flash-sales.destroy', $flashSale) }}" method="POST" class="d-inline">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection