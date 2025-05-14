@extends('layouts.app')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Asset Details</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Assets</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Asset Details</li>
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
                    <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-success me-2">
                        <i class="bi bi-pencil-square me-2"></i>Edit Asset
                    </a>
                    <a href="{{ route('inventory.index') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-return-left me-2"></i>Back
                    </a>
                </div>

                <!-- Asset Details Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Left Column -->
                                <div class="mb-3">
                                    <strong>Asset Name:</strong>
                                    <p>{{ $inventory->item_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Category:</strong>
                                    <p>{{ $inventory->category->category ?? 'N/A' }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Department:</strong>
                                    <p>{{ $inventory->department->name ?? 'N/A' }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Assigned To:</strong>
                                    <p>
                                        @if($inventory->user)
                                            {{ $inventory->user->first_name }} {{ $inventory->user->last_name }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong>Asset Tag:</strong>
                                    <p>{{ $inventory->asset_tag }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Right Column -->
                                <div class="mb-3">
                                    <strong>Serial Number:</strong>
                                    <p>{{ $inventory->serial_no }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Model Number:</strong>
                                    <p>{{ $inventory->model_no }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Manufacturer:</strong>
                                    <p>{{ $inventory->manufacturer }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Date Purchased:</strong>
                                    <p>{{ \Carbon\Carbon::parse($inventory->date_purchased)->format('F d, Y') }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Purchased From:</strong>
                                    <p>{{ $inventory->purchased_from }}</p>
                                </div>
                            </div>
                        </div>

                        @if($inventory->image_path)
                        <div class="row mt-3">
                            <div class="col-12">
                                <strong>Asset Image:</strong>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $inventory->image_path) }}" alt="{{ $inventory->item_name }}" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($inventory->log_note)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Log Note</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $inventory->log_note }}</p>
                    </div>
                </div>
                @endif

                <!-- Custom Fields Card -->
                @if($inventory->custom_fields && count($inventory->custom_fields) > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Custom Fields</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($inventory->custom_fields as $key => $value)
                            <div class="col-md-6 mb-3">
                                <strong>{{ $key }}:</strong>
                                @if(is_array($value))
                                    @if(isset($value['path']))
                                    <p>
                                        <a href="{{ asset('storage/' . $value['path']) }}" target="_blank">
                                            {{ $value['original_name'] ?? 'View File' }}
                                        </a>
                                    </p>
                                    @else
                                    <p>{{ json_encode($value) }}</p>
                                    @endif
                                @else
                                <p>{{ $value }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Components Card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Associated Components</h5>
                        <a href="{{ route('components.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg"></i> Add Component
                        </a>
                    </div>
                    <div class="card-body">
                        @if($inventory->components && $inventory->components->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Component Name</th>
                                        <th>Category</th>
                                        <th>Serial No</th>
                                        <th>Model No</th>
                                        <th>Manufacturer</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventory->components as $index => $component)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $component->component_name }}</td>
                                        <td>{{ $component->category }}</td>
                                        <td>{{ $component->serial_no }}</td>
                                        <td>{{ $component->model_no }}</td>
                                        <td>{{ $component->manufacturer }}</td>
                                        <td>
                                            <a href="{{ route('components.show', $component->id) }}" class="btn btn-sm btn-dark">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('components.edit', $component->id) }}" class="btn btn-sm btn-success">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info" style="border-left: 4px solid #0dcaf0; padding: 10px;">
                            <i class="bi bi-info-circle me-2"></i>
                            No components are associated with this asset yet. Click "Add Component" to add one.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 