@extends('layouts.app')

@section('content')

<!-- Content Header -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Custom Field</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customfields.index') }}">Custom Fields</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Custom Field</li>
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

                <!-- Add Custom Field Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('customfields.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <!-- Field Name -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Field Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter field name">
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
                                            <option value="0" {{ old('is_required') == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('is_required') == '1' ? 'selected' : '' }}>Yes</option>
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
                                        <textarea type="text" name="desc" class="form-control" placeholder="Enter description">{{ old('desc') }}</textarea>
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
                                        <select name="type" id="field_type" class="form-control">
                                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select a field type</option>
                                            <option value="Text" {{ old('type') == 'Text' ? 'selected' : '' }}>Text</option>
                                            <option value="List" {{ old('type') == 'List' ? 'selected' : '' }}>List</option>
                                            <option value="Checkbox" {{ old('type') == 'Checkbox' ? 'selected' : '' }}>Checkbox</option>
                                            <option value="Radio" {{ old('type') == 'Radio' ? 'selected' : '' }}>Radio Button</option>
                                            <option value="Select" {{ old('type') == 'Select' ? 'selected' : '' }}>Select Dropdown</option>
                                        </select>
                                        @error('type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Text Input Type (Only visible when "Text" is selected) -->
                                <div id="text-type-container" class="col-md-6" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label>Text Input Type<span class="text-danger"> *</span></label>
                                        <select name="text_type" class="form-control">
                                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select a input type</option>
                                            <option value="Text" {{ old('text_type') == 'Text' ? 'selected' : '' }}>Text</option>
                                            <option value="Email" {{ old('text_type') == 'Email' ? 'selected' : '' }}>Email</option>
                                            <option value="Number" {{ old('text_type') == 'Number' ? 'selected' : '' }}>Number</option>
                                            <option value="Image" {{ old('text_type') == 'Image' ? 'selected' : '' }}>Image</option>
                                            <option value="Password" {{ old('text_type') == 'Password' ? 'selected' : '' }}>Password</option>
                                            <option value="Date" {{ old('text_type') == 'Date' ? 'selected' : '' }}>Date</option>
                                        </select>
                                        @error('text_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Options for List, Checkbox, Radio, Select -->
                            <div id="options-container" class="mb-3" style="display: none;">
                                <label>Options<span class="text-danger"> *</span></label>
                                <div id="options-list">
                                    <div class="d-flex mb-2">
                                        <input type="text" name="options[]" class="form-control me-2" placeholder="Enter option">
                                        <button type="button" class="btn btn-success d-flex align-items-center" id="add-option">
                                            <i class="bi bi-plus-lg me-2"></i> Add
                                        </button>
                                    </div>
                                </div>
                                @error('options')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end">
                                    <i class="bi bi-plus-lg me-2"></i>Add Custom Field
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Handle Field Type Selection -->
<script>
    document.getElementById('field_type').addEventListener('change', function() {
        const optionsContainer = document.getElementById('options-container');
        if (['List', 'Checkbox', 'Radio', 'Select'].includes(this.value)) {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    });

    document.getElementById('add-option').addEventListener('click', function() {
        const optionsList = document.getElementById('options-list');
        const newOption = document.createElement('div');
        newOption.className = 'd-flex mb-2';
        newOption.innerHTML = `
            <input type="text" name="options[]" class="form-control me-2" placeholder="Enter option">
            <button type="button" class="btn btn-danger d-flex align-items-center remove-option">
                <i class="bi bi-x-lg me-2"></i> Remove
            </button>
        `;
        optionsList.appendChild(newOption);
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-option')) {
            event.target.parentElement.remove();
        }
    });
</script>

<!-- JavaScript to Handle Field Type Selection -->
<script>
    document.getElementById('field_type').addEventListener('change', function() {
        const textTypeContainer = document.getElementById('text-type-container');
        const optionsContainer = document.getElementById('options-container');

        if (this.value === 'Text') {
            textTypeContainer.style.display = 'block'; // Show text type selection
            optionsContainer.style.display = 'none'; // Hide options (since text doesn't need options)
        } else if (['List', 'Checkbox', 'Radio', 'Select'].includes(this.value)) {
            textTypeContainer.style.display = 'none'; // Hide text type selection
            optionsContainer.style.display = 'block'; // Show options container
        } else {
            textTypeContainer.style.display = 'none'; // Hide both if another type is selected
            optionsContainer.style.display = 'none';
        }
    });
</script>

@endsection
