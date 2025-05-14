@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Component</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('components.index') }}">Components</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Component</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-flex justify-content-end mb-2">
                    <a href="{{ route('components.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left me-2"></i>Back</a>
                </div>
                <!-- Edit Component Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('components.update', $component) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- First Column -->
                                <div class="col-md-6">
                                    <!-- Component Name -->
                                    <div class="form-group mb-3">
                                        <label>Component Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="component_name" value="{{ old('component_name', $component->component_name) }}" class="form-control" placeholder="Enter component name" required>
                                        @error('component_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Category Dropdown -->
                                    <div class="form-group mb-3">
                                        <label>Category<span class="text-danger"> *</span></label>
                                        <select name="category" class="form-control" required>
                                            <option value="" disabled>Select Category</option>
                                            @foreach($categories as $id => $category)
                                                <option value="{{ $category }}" {{ old('category', $component->category) == $category ? 'selected' : '' }}>
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Serial No -->
                                    <div class="form-group mb-3">
                                        <label>Serial No<span class="text-danger"> *</span></label>
                                        <input type="text" name="serial_no" value="{{ old('serial_no', $component->serial_no) }}" class="form-control" placeholder="Enter serial number" required>
                                        @error('serial_no')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Model No -->
                                    <div class="form-group mb-3">
                                        <label>Model No<span class="text-danger"> *</span></label>
                                        <input type="text" name="model_no" value="{{ old('model_no', $component->model_no) }}" class="form-control" placeholder="Enter model number" required>
                                        @error('model_no')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Associated Asset (New Field) -->
                                    <div class="form-group mb-3">
                                        <label>Associated Asset</label>
                                        <select name="inventory_id" class="form-control">
                                            <option value="">None (Independent Component)</option>
                                            @foreach($assets as $asset)
                                                <option value="{{ $asset->id }}" {{ (old('inventory_id', $component->inventory_id) == $asset->id) ? 'selected' : '' }}>
                                                    {{ $asset->item_name }} ({{ $asset->asset_tag }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('inventory_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Second Column -->
                                <div class="col-md-6">
                                    <!-- Manufacturer -->
                                    <div class="form-group mb-3">
                                        <label>Manufacturer<span class="text-danger"> *</span></label>
                                        <input type="text" name="manufacturer" value="{{ old('manufacturer', $component->manufacturer) }}" class="form-control" placeholder="Enter manufacturer" required>
                                        @error('manufacturer')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Assigned User Dropdown -->
                                    <div class="form-group mb-3">
                                        <label>Assigned To</label>
                                        <select name="users_id" class="form-control">
                                            <option value="">Not Assigned</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('users_id', $component->users_id) == $user->id ? 'selected' : '' }}>
                                                    {{ $user->first_name }} {{ $user->last_name }} ({{ $user->department->name ?? 'No Department' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('users_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Date Purchased -->
                                    <div class="form-group mb-3">
                                        <label>Date Purchased<span class="text-danger"> *</span></label>
                                        <input type="date" name="date_purchased" value="{{ old('date_purchased', $component->date_purchased) }}" class="form-control" required>
                                        @error('date_purchased')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Purchased From -->
                                    <div class="form-group mb-3">
                                        <label>Purchased From<span class="text-danger"> *</span></label>
                                        <input type="text" name="purchased_from" value="{{ old('purchased_from', $component->purchased_from) }}" class="form-control" placeholder="Enter purchased from" required>
                                        @error('purchased_from')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <!-- Log Note -->
                                    <div class="form-group mb-3">
                                        <label>Log Note</label>
                                        <textarea name="log_note" class="form-control" rows="5" placeholder="Enter log note">{{ old('log_note', $component->log_note) }}</textarea>
                                        @error('log_note')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end"><i class="bi bi-save me-2"></i>Update Component</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection