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
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customfields.index') }}">Custom Fields</a></li>
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
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Field Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="name" value="{{ old('name', $customField->name) }}" class="form-control" placeholder="Enter field name">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
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
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label>Description <span class="text-danger"> *</span></label>
                                        <textarea name="desc" class="form-control" placeholder="Enter description">{{ old('desc', $customField->desc) }}</textarea>
                                        @error('desc')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Field Type<span class="text-danger"> *</span></label>
                                        <select name="type" id="field_type" class="form-control">
                                            <option value="" disabled {{ old('type', $customField->type) ? '' : 'selected' }}>Select a field type</option>
                                            <option value="Text" {{ old('type', $customField->type) == 'Text' ? 'selected' : '' }}>Text</option>
                                            <option value="List" {{ old('type', $customField->type) == 'List' ? 'selected' : '' }}>List</option>
                                            <option value="Checkbox" {{ old('type', $customField->type) == 'Checkbox' ? 'selected' : '' }}>Checkbox</option>
                                            <option value="Radio" {{ old('type', $customField->type) == 'Radio' ? 'selected' : '' }}>Radio Button</option>
                                            <option value="Select" {{ old('type', $customField->type) == 'Select' ? 'selected' : '' }}>Select Dropdown</option>
                                        </select>
                                        @error('type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div id="text-type-container" class="col-md-6" style="display: none;">
                                    <div class="form-group mb-3">
                                        <label>Text Input Type<span class="text-danger"> *</span></label>
                                        <select name="text_type" class="form-control">
                                            <option value="" disabled {{ old('text_type', $customField->text_type) ? '' : 'selected' }}>Select a input type</option>
                                            <option value="Text" {{ old('text_type', $customField->text_type) == 'Text' ? 'selected' : '' }}>Text</option>
                                            <option value="Email" {{ old('text_type', $customField->text_type) == 'Email' ? 'selected' : '' }}>Email</option>
                                            <option value="Number" {{ old('text_type', $customField->text_type) == 'Number' ? 'selected' : '' }}>Number</option>
                                            <option value="Image" {{ old('text_type', $customField->text_type) == 'Image' ? 'selected' : '' }}>Image</option>
                                            <option value="Password" {{ old('text_type', $customField->text_type) == 'Password' ? 'selected' : '' }}>Password</option>
                                            <option value="Date" {{ old('text_type', $customField->text_type) == 'Date' ? 'selected' : '' }}>Date</option>
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
                                    @if(in_array($customField->type, ['List', 'Checkbox', 'Radio', 'Select']) && $customField->options)
                                        @php
                                        $options = json_decode($customField->options);
                                        @endphp
                                        
                                        @foreach($options as $index => $option)
                                            @if($index === 0)
                                            <div class="d-flex mb-2">
                                                <input type="text" name="options[]" value="{{ $option }}" class="form-control me-2" placeholder="Enter option">
                                                <button type="button" class="btn btn-success d-flex align-items-center" id="add-option">
                                                    <i class="bi bi-plus-lg me-2"></i> Add
                                                </button>
                                            </div>
                                            @else
                                            <div class="d-flex mb-2">
                                                <input type="text" name="options[]" value="{{ $option }}" class="form-control me-2" placeholder="Enter option">
                                                <button type="button" class="btn btn-danger d-flex align-items-center remove-option">
                                                    <i class="bi bi-x-lg me-2"></i> Remove
                                                </button>
                                            </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="d-flex mb-2">
                                            <input type="text" name="options[]" class="form-control me-2" placeholder="Enter option">
                                            <button type="button" class="btn btn-success d-flex align-items-center" id="add-option">
                                                <i class="bi bi-plus-lg me-2"></i> Add
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                @error('options')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Where to apply the custom field -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Apply to<span class="text-danger"> *</span></label>
                                    <div class="d-flex flex-wrap mt-2">
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Category" id="category-check" 
                                                {{ (is_array(old('applies_to')) && in_array('Category', old('applies_to'))) || 
                                                (old('applies_to') === null && is_array($customField->applies_to) && in_array('Category', $customField->applies_to)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category-check">
                                                Category
                                            </label>
                                        </div>
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Asset" id="asset-check"
                                                {{ (is_array(old('applies_to')) && in_array('Asset', old('applies_to'))) || 
                                                (old('applies_to') === null && is_array($customField->applies_to) && in_array('Asset', $customField->applies_to)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="asset-check">
                                                Asset
                                            </label>
                                        </div>
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Accessory" id="accessory-check"
                                                {{ (is_array(old('applies_to')) && in_array('Accessory', old('applies_to'))) || 
                                                (old('applies_to') === null && is_array($customField->applies_to) && in_array('Accessory', $customField->applies_to)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="accessory-check">
                                                Accessory
                                            </label>
                                        </div>
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Component" id="component-check"
                                                {{ (is_array(old('applies_to')) && in_array('Component', old('applies_to'))) || 
                                                (old('applies_to') === null && is_array($customField->applies_to) && in_array('Component', $customField->applies_to)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="component-check">
                                                Component
                                            </label>
                                        </div>
                                    </div>
                                    @error('applies_to')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end">
                                    <i class="bi bi-pencil-square me-2"></i>Update Custom Field
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

    // Initialize display on page load
    document.addEventListener('DOMContentLoaded', function() {
        const fieldType = document.getElementById('field_type').value;
        const textTypeContainer = document.getElementById('text-type-container');
        const optionsContainer = document.getElementById('options-container');

        if (fieldType === 'Text') {
            textTypeContainer.style.display = 'block';
            optionsContainer.style.display = 'none';
        } else if (['List', 'Checkbox', 'Radio', 'Select'].includes(fieldType)) {
            textTypeContainer.style.display = 'none';
            optionsContainer.style.display = 'block';
        } else {
            textTypeContainer.style.display = 'none';
            optionsContainer.style.display = 'none';
        }
    });
</script>

@endsection