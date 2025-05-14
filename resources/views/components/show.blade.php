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
                <h1 class="m-0">Component Details</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('components.index') }}">Components</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Component Details</li>
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
                    <a href="{{ route('components.edit', $component) }}" class="btn btn-success me-2"><i class="bi bi-pencil-square me-2"></i>Edit</a>
                    <a href="{{ route('components.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left me-2"></i>Back</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="component_profile" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true"><i class="bi bi-cpu me-2"></i>Component Profile</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="component_asset" data-bs-toggle="tab" data-bs-target="#asset" type="button" role="tab" aria-controls="asset" aria-selected="false"><i class="bi-box-seam me-2"></i>Associated Asset</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="component_history" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false"><i class="bi bi-clock-history me-2"></i>History</button>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="myTabContent">
                            <!-- Component Profile Tab -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="component_profile">
                                <div class="row align-items-center">
                                    <!-- Component Image (Left) -->
                                    <div class="col-md-3 text-center">
                                        <img src="{{ asset('images/component-default.png') }}" alt="Component Image" class="img-fluid rounded" width="150">
                                        <h4 class="mt-3">{{ $component->component_name }}</h4>
                                        <p class="text-muted">{{ $component->category }}</p>
                                    </div>
                                    <!-- Component Information (Right) -->
                                    <div class="col-md-9">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>Serial Number</th>
                                                <td>{{ $component->serial_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Model Number</th>
                                                <td>{{ $component->model_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Manufacturer</th>
                                                <td>{{ $component->manufacturer }}</td>
                                            </tr>
                                            <tr>
                                                <th>Assigned To</th>
                                                <td>
                                                    @if($component->user)
                                                        <span class="badge bg-success">
                                                            {{ $component->user->first_name }} {{ $component->user->last_name }}
                                                            ({{ $component->user->department->name ?? 'No Department' }})
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Not assigned</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Date Purchased</th>
                                                <td>{{ $component->date_purchased ? $component->date_purchased->format('F d, Y') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Purchased From</th>
                                                <td>{{ $component->purchased_from }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if($component->log_note)
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5 class="card-title">Log Note</h5>
                                    </div>
                                    <div class="card-body">
                                        <p>{{ $component->log_note }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Associated Asset Tab -->
                            <div class="tab-pane fade" id="asset" role="tabpanel" aria-labelledby="component_asset">
                                @if($component->inventory)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Asset Information</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <table class="table">
                                                                <tr>
                                                                    <th>Asset Name</th>
                                                                    <td>{{ $component->inventory->item_name }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Asset Tag</th>
                                                                    <td>{{ $component->inventory->asset_tag }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Category</th>
                                                                    <td>{{ $component->inventory->category->category ?? 'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Department</th>
                                                                    <td>{{ $component->inventory->department->name ?? 'N/A' }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <table class="table">
                                                                <tr>
                                                                    <th>Serial Number</th>
                                                                    <td>{{ $component->inventory->serial_no }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Model Number</th>
                                                                    <td>{{ $component->inventory->model_no }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <td>{{ $component->inventory->status }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>&nbsp;</th>
                                                                    <td>
                                                                        <a href="{{ route('inventory.show', $component->inventory->id) }}" class="btn btn-sm btn-primary">
                                                                            <i class="bi bi-eye me-1"></i> View Asset
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        This component is not associated with any asset.
                                    </div>
                                @endif
                            </div>

                            <!-- History Tab -->
                            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="component_history">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    No history records found for this component.
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