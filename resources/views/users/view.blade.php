@extends('layouts.app')

@section('content')

<style>
    .nav-link{
        color:black;
    }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">User Profile</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Users</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('users.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left me-2"></i>Back</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active  " id="user_profile" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"><i class="bi bi-person-circle me-2"></i>Profile</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="user_assets" data-bs-toggle="tab" data-bs-target="#asset" type="button" role="tab" aria-controls="asset" aria-selected="false"><i class="bi-box-seam me-2"></i>Assets</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="user_accessories" data-bs-toggle="tab" data-bs-target="#accessories" type="button" role="tab" aria-controls="accessories" aria-selected="false"><i class="bi bi-headphones me-2"></i>Accessories</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="user_history" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false"><i class="bi bi-clock-history me-2"></i>History</button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="user_profile">
                                <div class="row align-items-center">
                                    <!-- Profile Picture (Left) -->
                                    <div class="col-md-3 text-center">
                                        <img src="{{ asset('images/default-user.jpg') }}" alt="User Avatar" class="img-fluid rounded-circle" width="150">
                                        <h4 class="mt-3">{{ $user->first_name }} {{ $user->last_name }}</h4>
                                        <p class="text-muted">{{ $user->email }}</p>
                                    </div>
                                    <!-- User Information (Right) -->
                                    <div class="col-md-9">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Full Name</th>
                                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Role</th>
                                                <td>{{ $user->user_role }}</td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Updated At</th>
                                                <td>{{ $user->updated_at->format('d M Y') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade " id="asset" role="tabpanel" aria-labelledby="user_assets">
                                <div class="alert alert-warning fade show">
                                    <i class="bi bi-check-circle me-2"></i>
                                    No asset found
                                </div>
                            </div>

                            <div class="tab-pane fade " id="accessories" role="tabpanel" aria-labelledby="user_accessories">
                                <div class="alert alert-warning fade show">
                                    <i class="bi bi-check-circle me-2"></i>
                                    No accessory found
                                </div>
                            </div>

                            <div class="tab-pane fade " id="history" role="tabpanel" aria-labelledby="user_history">
                                <div class="alert alert-warning fade show">
                                    <i class="bi bi-check-circle me-2"></i>
                                    No history found
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