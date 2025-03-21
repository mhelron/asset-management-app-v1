@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Category</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Categories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Category</li>
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
                    <a href="{{ route('categories.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left me-2"></i>Back</a>
                </div>

                <!-- Add User Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('categories.store') }}" method="POST">
                            @csrf
                    
                            <!-- Category Name -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Category Name<span class="text-danger"> *</span></label>
                                    <input type="text" name="category" value="{{ old('category') }}" class="form-control" placeholder="Enter category name">
                                    @error('category')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Description <span class="text-danger"> *</span></label>
                                    <textarea type="text" name="desc" class="form-control" placeholder="Enter description">{{ old('desc') }}</textarea>
                                    @error('desc')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Custom Fields Section -->
                            <div class="col-md-12 mt-4">
                                <h4>Custom Fields</h4>
                                <p class="text-muted">Define fields that will be available when creating inventory items in this category</p>
                                
                                <div id="custom-fields-container">
                                    <!-- Custom fields will be added here -->
                                </div>
                                
                                <button type="button" id="add-field-btn" class="btn btn-secondary mt-2 mb-3">
                                    <i class="bi bi-plus-lg me-2"></i> Add Custom Field
                                </button>
                            </div>

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end"><i class="bi bi-plus-lg me-2"></i>Add Category</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for handling dynamic fields -->
<script>
    let fieldCount = 0;
    
    // Add field button handler
    document.getElementById('add-field-btn').addEventListener('click', function() {
        const fieldHTML = `
            <div class="card mb-3 custom-field-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Field Name<span class="text-danger"> *</span></label>
                                <input type="text" name="field_names[${fieldCount}]" class="form-control" placeholder="e.g. Serial Number" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Field Type<span class="text-danger"> *</span></label>
                                <select name="field_types[${fieldCount}]" class="form-control">
                                    <option value="text">Text</option>
                                    <option value="number">Number</option>
                                    <option value="date">Date</option>
                                    <option value="image">Image Upload</option>
                                    <option value="textarea">Text Area</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mt-4">
                                <div class="form-check">
                                    <input type="checkbox" id="field_required_${fieldCount}" name="field_required[${fieldCount}]" class="form-check-input">
                                    <label class="form-check-label" for="field_required_${fieldCount}">Required Field</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger mt-4 remove-field">
                                <i class="bi bi-trash me-2"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        const container = document.getElementById('custom-fields-container');
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = fieldHTML;
        container.appendChild(tempDiv.firstElementChild);
        
        // Add remove button handler
        const removeButtons = document.querySelectorAll('.remove-field');
        removeButtons[removeButtons.length - 1].addEventListener('click', function() {
            this.closest('.custom-field-card').remove();
        });
        
        fieldCount++;
    });
</script>

@endsection