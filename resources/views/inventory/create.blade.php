@extends('layouts.app')
@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Asset</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Assets</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Asset</li>
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
                    <a href="{{ route('inventory.index') }}" class="btn btn-danger"><i class="bi bi-arrow-return-left me-2"></i>Back</a>
                </div>
                <!-- Add Inventory Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                 <!-- Item Name -->
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Item Name<span class="text-danger"> *</span></label>
                                        <input type="text" name="item_name" value="{{ old('item_name') }}" class="form-control" placeholder="Enter item name">
                                        @error('item_name', 'inventoryForm')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                    
                                <!-- Category -->
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="categories">Category</label>
                                        <select name="category_id" id="category_select" class="form-control">
                                            <option value="" disabled selected>Select a category</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->category }} 
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id', 'inventoryForm')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                                                    
                            <!-- Asset-specific Custom Fields -->
                            <div id="asset-fields-container" class="mb-3"></div>
                            
                            <!-- Category-specific Custom Fields -->
                            <div id="dynamic-fields-container" class="mb-3"></div>
                            
                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end"><i class="bi bi-plus-lg me-2"></i>Add Asset</button>
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
        const assetCustomFields = {!! json_encode($assetCustomFields) !!};
        const validationErrors = {!! $errors->customFields ? $errors->customFields->toJson() : '{}' !!};
    
        function renderAssetCustomFields() {
            const container = document.getElementById('asset-fields-container');
            
            if (assetCustomFields.length === 0) {
                return;
            }
    
            const rowDiv = document.createElement('div');
            rowDiv.className = 'row';
            container.appendChild(rowDiv);
            
            assetCustomFields.forEach(field => {
                const colDiv = document.createElement('div');
                colDiv.className = 'col-md-6';
                rowDiv.appendChild(colDiv);
                
                const fieldGroup = document.createElement('div');
                fieldGroup.className = 'form-group mb-3';
                colDiv.appendChild(fieldGroup);
                
                const label = document.createElement('label');
                label.innerHTML = field.name + (field.is_required ? '<span class="text-danger"> *</span>' : '');
                fieldGroup.appendChild(label);
                
                let input;
                const errorKey = `custom_fields.${field.name}`;
                const fileErrorKey = `custom_fields_files.${field.name}`;
    
                // Improved old value determination
                const oldValue = (validationErrors.old && 
                    (validationErrors.old[errorKey] || validationErrors.old[fileErrorKey])) || null;
    
                switch(field.type) {
                    case 'Text':
                        input = document.createElement('input');
                        input.type = field.text_type ? field.text_type.toLowerCase() : 'text';
                        
                        if (field.text_type === 'Image') {
                            input.type = 'file';
                            input.name = `custom_fields_files[${field.name}]`;
                            input.accept = 'image/*';
                        } else {
                            input.name = `custom_fields[${field.name}]`;
                        }
                        break;
                    
                    case 'Select':
                        input = document.createElement('select');
                        input.name = `custom_fields[${field.name}]`;
                        
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Select an option';
                        defaultOption.selected = true;
                        defaultOption.disabled = true;
                        input.appendChild(defaultOption);
                        
                        let selectOptions = typeof field.options === 'string' 
                            ? JSON.parse(field.options) 
                            : field.options;
                        
                        if (selectOptions && Array.isArray(selectOptions)) {
                            selectOptions.forEach(option => {
                                if (option) {
                                    const optionElement = document.createElement('option');
                                    optionElement.value = option;
                                    optionElement.textContent = option;
                                    // Set selected if matches old value
                                    if (oldValue === option) {
                                        optionElement.selected = true;
                                    }
                                    input.appendChild(optionElement);
                                }
                            });
                        }
                        break;
                    
                    case 'Checkbox':
                        input = document.createElement('div');
                        input.className = 'checkbox-container';
                        
                        let checkboxOptions = typeof field.options === 'string' 
                            ? JSON.parse(field.options) 
                            : field.options;
                        
                        if (checkboxOptions && Array.isArray(checkboxOptions)) {
                            checkboxOptions.forEach(option => {
                                if (option) {
                                    const checkDiv = document.createElement('div');
                                    checkDiv.className = 'form-check';
                                    
                                    const checkbox = document.createElement('input');
                                    checkbox.type = 'checkbox';
                                    checkbox.className = 'form-check-input';
                                    checkbox.name = `custom_fields[${field.name}][]`;
                                    checkbox.value = option;
                                    
                                    // Check if this option was previously selected
                                    if (Array.isArray(oldValue) && oldValue.includes(option)) {
                                        checkbox.checked = true;
                                    }
                                    
                                    const checkLabel = document.createElement('label');
                                    checkLabel.className = 'form-check-label';
                                    checkLabel.textContent = option;
                                    
                                    checkDiv.appendChild(checkbox);
                                    checkDiv.appendChild(checkLabel);
                                    input.appendChild(checkDiv);
                                }
                            });
                        }
                        break;
                    
                    case 'Radio':
                        input = document.createElement('div');
                        input.className = 'radio-container';
                        
                        let radioOptions = typeof field.options === 'string' 
                            ? JSON.parse(field.options) 
                            : field.options;
                        
                        if (radioOptions && Array.isArray(radioOptions)) {
                            radioOptions.forEach(option => {
                                if (option) {
                                    const radioDiv = document.createElement('div');
                                    radioDiv.className = 'form-check';
                                    
                                    const radio = document.createElement('input');
                                    radio.type = 'radio';
                                    radio.className = 'form-check-input';
                                    radio.name = `custom_fields[${field.name}]`;
                                    radio.value = option;
                                    
                                    // Set checked if matches old value
                                    if (oldValue === option) {
                                        radio.checked = true;
                                    }
                                    
                                    const radioLabel = document.createElement('label');
                                    radioLabel.className = 'form-check-label';
                                    radioLabel.textContent = option;
                                    
                                    radioDiv.appendChild(radio);
                                    radioDiv.appendChild(radioLabel);
                                    input.appendChild(radioDiv);
                                }
                            });
                        }
                        break;
                    
                    default:
                        input = document.createElement('input');
                        input.type = 'text';
                        input.name = `custom_fields[${field.name}]`;
                }
                
                // Apply form control class and handle validation
                if (input && input.tagName && input.tagName !== 'DIV') {
                    input.className = 'form-control';
                    
                    // Set old value if exists
                    if (oldValue && input.type !== 'file') {
                        input.value = oldValue;
                    }
                }
                
                // Append input to field group
                fieldGroup.appendChild(input);
                
                // Improved error handling
                let errorMessages = [];
                if (validationErrors[errorKey]) {
                    errorMessages = errorMessages.concat(validationErrors[errorKey]);
                }
                if (validationErrors[fileErrorKey]) {
                    errorMessages = errorMessages.concat(validationErrors[fileErrorKey]);
                }
                
                if (errorMessages.length > 0) {
                    const errorSpan = document.createElement('small');
                    errorSpan.className = 'text-danger';
                    errorSpan.textContent = errorMessages[0];
                    fieldGroup.appendChild(errorSpan);
                }
            });
        }
    
        // Call the function to render fields
        renderAssetCustomFields();
    
        // Optional: Add form-wide error handling
        function displayGlobalErrors() {
            const globalErrorContainer = document.createElement('div');
            globalErrorContainer.className = 'alert alert-danger';
            globalErrorContainer.style.display = 'none';

            // Collect all error messages
            const globalErrors = [];
            
            // Check validationErrors object thoroughly
            if (validationErrors) {
                for (const [key, errors] of Object.entries(validationErrors)) {
                    // Handle both array and string error formats
                    if (Array.isArray(errors)) {
                        globalErrors.push(...errors);
                    } else if (typeof errors === 'string') {
                        globalErrors.push(errors);
                    } else if (typeof errors === 'object') {
                        // Handle nested error objects
                        Object.values(errors).forEach(errorList => {
                            if (Array.isArray(errorList)) {
                                globalErrors.push(...errorList);
                            }
                        });
                    }
                }
            }
            
            if (globalErrors.length > 0) {
                globalErrorContainer.innerHTML = globalErrors.join('<br>');
                globalErrorContainer.style.display = 'block';
                
                const form = document.querySelector('form');
                if (form) {
                    form.insertBefore(globalErrorContainer, form.firstChild);
                }
            }
        }
    });
    </script>
 
@endsection