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
                                        <label>Required or Optional?<span class="text-danger"> *</span></label>
                                        <select name="is_required" class="form-control">
                                            <option value="" disabled {{ old('is_required') ? '' : 'selected' }}>Select an answer</option>
                                            <option value="0" {{ old('is_required') == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('is_required') == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('is_required')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Where to apply the custom field -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Apply to<span class="text-danger"> *</span></label>
                                    <div class="d-flex flex-wrap mt-2">
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Category" id="category-check" {{ is_array(old('applies_to')) && in_array('Category', old('applies_to')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category-check">
                                                Category
                                            </label>
                                        </div>
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Asset" id="asset-check" {{ is_array(old('applies_to')) && in_array('Asset', old('applies_to')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="asset-check">
                                                Asset
                                            </label>
                                        </div>
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Accessory" id="accessory-check" {{ is_array(old('applies_to')) && in_array('Accessory', old('applies_to')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="accessory-check">
                                                Accessory
                                            </label>
                                        </div>
                                        <div class="form-check me-4 mb-2">
                                            <input class="form-check-input" type="checkbox" name="applies_to[]" value="Component" id="component-check" {{ is_array(old('applies_to')) && in_array('Component', old('applies_to')) ? 'checked' : '' }}>
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
                                            <option value="" disabled {{ old('text_type') ? '' : 'selected' }}>Select a input type</option>
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

                            <div id="options-container" class="mb-3" style="display: none;">
                                <label>Options<span class="text-danger"> *</span></label>
                                <div id="options-list">
                                    @php
                                        $oldOptions = old('options', []);
                                    @endphp
                            
                                    @if ($errors->has('options'))
                                        <small class="text-danger">{{ $errors->first('options') }}</small>
                                    @endif
                            
                                    @foreach ($oldOptions as $index => $option)
                                        <div class="d-flex mb-2">
                                            <input type="text" name="options[]" class="form-control me-2" value="{{ $option }}" placeholder="Enter option">
                                            <button type="button" class="btn btn-danger remove-option d-flex align-items-center">
                                                <i class="bi bi-x-lg me-2"></i> Remove
                                            </button>
                                        </div>
                                        @if ($errors->has("options.$index"))
                                            <small class="text-danger">{{ $errors->first("options.$index") }}</small>
                                        @endif
                                    @endforeach
                            
                                    <div class="d-flex mb-2">
                                        <input type="text" name="options[]" class="form-control me-2" placeholder="Enter option">
                                        <button type="button" class="btn btn-success d-flex align-items-center" id="add-option">
                                            <i class="bi bi-plus-lg me-2"></i> Add
                                        </button>
                                    </div>
                                </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fieldTypeSelect = document.getElementById('field_type');
    const textTypeContainer = document.getElementById('text-type-container');
    const textTypeSelect = document.querySelector('select[name="text_type"]');
    const optionsContainer = document.getElementById('options-container');
    const optionsList = document.getElementById('options-list');

    // Retrieve old values and errors
    const oldOptions = @json(old('options', []));
    const optionsErrors = @json($errors->get('options.*'));

    function toggleContainers() {
        const selectedType = fieldTypeSelect.value;

        // Reset text type dropdown if not Text
        if (selectedType !== 'Text') {
            textTypeSelect.selectedIndex = 0;
        }

        // Show text type container only if "Text" is selected
        textTypeContainer.style.display = selectedType === 'Text' ? 'block' : 'none';

        // Show/hide options container based on field type
        if (['List', 'Checkbox', 'Radio', 'Select'].includes(selectedType)) {
            optionsContainer.style.display = 'block';
            renderOptions();
        } else {
            optionsContainer.style.display = 'none';
            optionsList.innerHTML = ''; // Clear options completely
        }
    }

    function renderOptions() {
        // Clear existing options
        optionsList.innerHTML = '';

        // If current type is List, Checkbox, Radio, or Select, always add at least one option
        if (['List', 'Checkbox', 'Radio', 'Select'].includes(fieldTypeSelect.value)) {
            // If there are old options, render them with error handling
            if (oldOptions.length > 0) {
                oldOptions.forEach((option, index) => {
                    addOption(option || '', optionsErrors[`options.${index}`] || '');
                });
            } else {
                // If no old options exist, add one blank input field
                addOption('', '');
            }

            // Add the "Add" button at the end
            addAddButton();
        }
    }

    function addOption(value = '', error = '') {
        const optionWrapper = document.createElement('div');
        optionWrapper.className = 'option-wrapper d-flex flex-column mb-2';
        optionWrapper.innerHTML = `
            <div class="d-flex align-items-center">
                <input type="text" name="options[]" class="form-control me-2" placeholder="Enter option" value="${value}">
                <button type="button" class="btn btn-danger remove-option d-flex align-items-center">
                    <i class="bi bi-x-lg"></i> Remove
                </button>
            </div>
            ${error ? `<small class="text-danger">${error}</small>` : ''}
        `;

        // Always insert before the add button (if it exists)
        const addButton = optionsList.querySelector('#add-option-wrapper');
        if (addButton) {
            optionsList.insertBefore(optionWrapper, addButton);
        } else {
            optionsList.appendChild(optionWrapper);
        }
    }

    function addAddButton() {
        // Remove existing add button if any
        const existingAddButton = document.getElementById('add-option-wrapper');
        if (existingAddButton) existingAddButton.remove();

        // Create new add button wrapper
        const addButtonWrapper = document.createElement('div');
        addButtonWrapper.id = 'add-option-wrapper';
        addButtonWrapper.className = 'd-flex justify-content-end mt-2';
        addButtonWrapper.innerHTML = `
            <button type="button" class="btn btn-success d-flex align-items-center" id="add-option">
                <i class="bi bi-plus-lg me-2"></i> Add
            </button>
        `;
        optionsList.appendChild(addButtonWrapper);

        // Attach event listener
        document.getElementById('add-option').addEventListener('click', function() {
            addOption('', '');
        });
    }

    // Handle option removal
    optionsList.addEventListener('click', function(event) {
        if (event.target.closest('.remove-option')) {
            const optionWrapper = event.target.closest('.option-wrapper');
            optionWrapper.remove();

            // Ensure at least one option input remains
            if (optionsList.querySelectorAll('.option-wrapper').length === 0) {
                addOption('', '');
            }
        }
    });

    // Ensure proper container visibility on load
    toggleContainers();

    // Add change event listener for field type selection
    fieldTypeSelect.addEventListener('change', function() {
        // Reset options when changing field type
        oldOptions.length = 0; // Clear old options
        toggleContainers();
    });
});
</script>

@endsection
