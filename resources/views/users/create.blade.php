@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add User</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add User</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">

                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('users.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left me-2"></i>Back</a>
                </div>

                <!-- Add User Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            
                            <!-- Start of row -->
                            <div class="row">

                                <!-- First Name -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>First Name <span class="text-danger"> *</span></label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="Enter first name">
                                        @error('first_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Last Name <span class="text-danger"> *</span></label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Enter last name">
                                        @error('last_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Email <span class="text-danger"> *</span></label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- User Role -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>User Role <span class="text-danger"> *</span></label>
                                        <select name="user_role" class="form-control">
                                            <option value="" disabled selected>Select a role</option>
                                            @if(session('user_role') != 'Admin')
                                                <option value="Super Admin" {{ old('user_role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                                            @endif
                                            <option value="Admin" {{ old('user_role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="Manager" {{ old('user_role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="Staff" {{ old('user_role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                        </select>
                                        @error('user_role')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Password <span class="text-danger"> *</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter password">
                                        @if ($errors->has('password') && $errors->first('password') !== 'The password confirmation does not match.')
                                            <small class="text-danger">{{ $errors->first('password') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Confirm Password <span class="text-danger"> *</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
                                        @if (!$errors->has('password') && $errors->has('password_confirmation'))
                                            <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                                        @elseif ($errors->has('password') && $errors->first('password') === 'The password confirmation does not match.')
                                            <small class="text-danger">{{ $errors->first('password') }}</small>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <!-- End of row -->

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end"><i class="bi bi-plus-lg me-2"></i>Add User</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection