@extends('layouts.layoutmaster')

@section('title', 'Create Flash Sale')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{asset('assets/vendors/flatpickr/flatpickr.min.css')}}">
<style>
    .btn i {
        font-size: 1rem;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<section id="basic-horizontal-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Flash Sale</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('admin.flash-sales.store') }}" method="POST">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title">Basic Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label>Name</label>
                                                    </div>
                                                    <div class="col-md-9 form-group">
                                                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                                            name="name" value="{{ old('name') }}" placeholder="Enter flash sale name">
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label>Description</label>
                                                    </div>
                                                    <div class="col-md-9 form-group">
                                                        <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                                            name="description" rows="3" placeholder="Enter flash sale description">{{ old('description') }}</textarea>
                                                        @error('description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label>Start Time</label>
                                                    </div>
                                                    <div class="col-md-9 form-group">
                                                        <input type="text" id="start_time" class="form-control flatpickr-datetime @error('start_time') is-invalid @enderror"
                                                            name="start_time" value="{{ old('start_time') }}" placeholder="YYYY-MM-DD HH:MM (e.g., 2025-03-04 08:00)">
                                                        <small class="text-muted">When the flash sale starts</small>
                                                        @error('start_time')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label>End Time</label>
                                                    </div>
                                                    <div class="col-md-9 form-group">
                                                        <input type="text" id="end_time" class="form-control flatpickr-datetime @error('end_time') is-invalid @enderror"
                                                            name="end_time" value="{{ old('end_time') }}" placeholder="YYYY-MM-DD HH:MM (e.g., 2025-03-04 20:00)">
                                                        <small class="text-muted">When the flash sale ends</small>
                                                        @error('end_time')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <label>Status</label>
                                                    </div>
                                                    <div class="col-md-9 form-group">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                                {{ old('is_active', true) ? 'checked' : '' }}>
                                                            <label class="form-check-label">Active</label>
                                                        </div>
                                                        <small class="text-muted">Flash sale will only be visible during the specified time period if active</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 d-flex justify-content-end mt-3">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Create Flash Sale</button>
                                        <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
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
<script src="{{asset('assets/vendors/flatpickr/flatpickr.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize flatpickr datetime pickers
        flatpickr(".flatpickr-datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            altInput: true,
            altFormat: "F j, Y at H:i",
            minDate: "today"
        });
    });
</script>
@endsection