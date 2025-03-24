@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
	<div class="container">
        <div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Edit User</h1>
			</div>
			<div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
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

				<!-- Edit User Form -->
				<div class="card">
					<div class="card-body">
						<form action="{{ url('users/update-user/'.$key) }}" method="POST">
							@csrf
							@method('PUT')

							<!-- Start of row -->
							<div class="row">
								
								<!-- First Name -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>First Name</label>
										<input type="text" name="first_name" value="{{ old('first_name', $editdata['first_name']) }}" class="form-control">
										@error('first_name')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- Last Name -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>Last Name</label>
										<input type="text" name="last_name" value="{{ old('last_name', $editdata['last_name']) }}" class="form-control">
										@error('last_name')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- Email -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>Email</label>
										<input type="text" name="email" value="{{ old('email', $editdata['email']) }}" class="form-control">
										@error('email')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- User Role -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>User Role</label>
										<select name="user_role" class="form-control">
											<option value="" disabled selected>Select a role</option>
											@if(session('user_role') != 'Admin')
                                                <option value="Super Admin" {{ old('user_role', $editdata['user_role']) == 'Super Admin'? 'selected' : '' }}>Super Admin</option>
											@endif
											<option value="Admin" {{ old('user_role', $editdata['user_role']) == 'Admin'? 'selected' : '' }}>Admin</option>
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
										<label>New Password</label>
										<input type="password" name="password" class="form-control">
										@error('password')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

								<!-- Confirm Password -->
								<div class="col-md-6">
									<div class="form-group mb-3">
										<label>Confirm New Password</label>
										<input type="password" name="password_confirmation" class="form-control">
										@error('password_confirmation')
											<small class="text-danger">{{ $message }}</small>
										@enderror
									</div>
								</div>

							</div>
							<!-- End of row -->

							<div class="form-group mb-3">
								<button type="submit" class="btn btn-dark float-end"><i class="bi bi-pencil-square me-2"></i>Update User</button>
							</div>

						</form>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>

@endsection