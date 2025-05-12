@extends('layouts.layoutmaster')

@section('title', 'Edit User')

@section('content')
<section id="basic-horizontal-layouts">
    <div class="row match-height">
        <div class="col-md-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit User</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('admin.users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Name</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $user->name) }}" placeholder="Enter name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Email</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $user->email) }}" placeholder="Enter email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Phone</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Enter phone number">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Password</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" placeholder="Leave blank to keep current password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Confirm Password</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <input type="password" id="password_confirmation" class="form-control"
                                            name="password_confirmation" placeholder="Confirm new password">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Role</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <select class="choices form-select @error('role') is-invalid @enderror" name="role">
                                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label>Status</label>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label">Active</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancel</a>
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
<script src="{{asset('assets/vendors/choices.js/choices.min.js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize select with Choices.js
        if (typeof Choices !== 'undefined') {
            new Choices(document.querySelector('select[name="role"]'), {
                searchEnabled: true,
                itemSelectText: '',
                allowHTML: true
            });
        }
    });
</script>
@endsection