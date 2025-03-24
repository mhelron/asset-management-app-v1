@extends('layouts.app')

@section('content')

<!-- Content Header -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Custom Field</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Custom Fields</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Custom Field</li>
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
                    <a href="{{ route('customfields.index') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-return-left me-2"></i>Back
                    </a>
                </div>

                <!-- Edit Custom Field Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('customfields.update', $customField->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!-- Field Name -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Field Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="name" value="{{ old('name', $customField->name) }}" class="form-control" placeholder="Enter field name">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Required Select -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Is Required?<span class="text-danger"> *</span></label>
                                        <select name="is_required" class="form-control">
                                            <option value="0" {{ old('is_required', $customField->is_required) == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('is_required', $customField->is_required) == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('is_required')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Description <span class="text-danger"> *</span></label>
                                        <textarea type="text" name="desc" class="form-control" placeholder="Enter description">{{ old('desc', $customField->desc) }}</textarea>
                                        @error('desc')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Field Type -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Field Type<span class="text-danger"> *</span></label>
                                        <select name="type" class="form-control">
                                            <option value="text" {{ old('type', $customField->type) == 'text' ? 'selected' : '' }}>Text</option>
                                            <option value="list" {{ old('type', $customField->type) == 'list' ? 'selected' : '' }}>List</option>
                                            <option value="checkbox" {{ old('type', $customField->type) == 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                            <option value="radio" {{ old('type', $customField->type) == 'radio' ? 'selected' : '' }}>Radio Button</option>
                                            <option value="select" {{ old('type', $customField->type) == 'select' ? 'selected' : '' }}>Select Dropdown</option>
                                        </select>
                                        @error('type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end">
                                    <i class="bi bi-save me-2"></i>Update Custom Field
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection