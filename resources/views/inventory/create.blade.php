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
                                        @error('item_name')
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
                                        @error('category_id')
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
        console.log('Asset Custom Fields:', {!! json_encode($assetCustomFields) !!});
        
        // Log each field details
        {!! json_encode($assetCustomFields) !!}.forEach(field => {
            console.log('Field Name:', field.name);
            console.log('Field Type:', field.type);
            console.log('Field Options:', field.options);
        });

        // Function to render asset-specific custom fields
        function renderAssetCustomFields() {
            const container = document.getElementById('asset-fields-container');
            
            // Get asset custom fields from PHP
            const assetCustomFields = {!! json_encode($assetCustomFields) !!};
            
            if (assetCustomFields.length > 0) {
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
                    
                    // Handle different field types
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
                            
                            // Add default option
                            const defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = 'Select an option';
                            defaultOption.selected = true;
                            defaultOption.disabled = true;
                            input.appendChild(defaultOption);
                            
                            // Add options from field
                            let selectOptions = field.options;
                            if (typeof selectOptions === 'string') {
                                try {
                                    selectOptions = JSON.parse(selectOptions);
                                } catch (e) {
                                    console.error('Invalid JSON in options:', selectOptions);
                                    selectOptions = [];
                                }
                            }
                            
                            if (selectOptions && Array.isArray(selectOptions)) {
                                selectOptions.forEach(option => {
                                    if (option) {
                                        const optionElement = document.createElement('option');
                                        optionElement.value = option;
                                        optionElement.textContent = option;
                                        input.appendChild(optionElement);
                                    }
                                });
                            }
                            break;
                        case 'Checkbox':
                            const checkboxContainer = document.createElement('div');
                            input = checkboxContainer;
                            
                            let checkboxOptions = field.options;
                            if (typeof checkboxOptions === 'string') {
                                try {
                                    checkboxOptions = JSON.parse(checkboxOptions);
                                } catch (e) {
                                    console.error('Invalid JSON in options:', checkboxOptions);
                                    checkboxOptions = [];
                                }
                            }
                            
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
                                        
                                        const checkLabel = document.createElement('label');
                                        checkLabel.className = 'form-check-label ms-2';
                                        checkLabel.textContent = option;
                                        
                                        checkDiv.appendChild(checkbox);
                                        checkDiv.appendChild(checkLabel);
                                        checkboxContainer.appendChild(checkDiv);
                                    }
                                });
                            }
                            break;
                        case 'Radio':
                            const radioContainer = document.createElement('div');
                            input = radioContainer;
                            
                            let radioOptions = field.options;
                            if (typeof radioOptions === 'string') {
                                try {
                                    radioOptions = JSON.parse(radioOptions);
                                } catch (e) {
                                    console.error('Invalid JSON in options:', radioOptions);
                                    radioOptions = [];
                                }
                            }
                            
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
                                        
                                        const radioLabel = document.createElement('label');
                                        radioLabel.className = 'form-check-label ms-2';
                                        radioLabel.textContent = option;
                                        
                                        radioDiv.appendChild(radio);
                                        radioDiv.appendChild(radioLabel);
                                        radioContainer.appendChild(radioDiv);
                                    }
                                });
                            }
                            break;
                        default:
                            input = document.createElement('input');
                            input.type = 'text';
                            input.name = `custom_fields[${field.name}]`;
                    }
                    
                    if (input.tagName !== 'DIV') {
                        input.className = 'form-control';
                        if (field.is_required) input.required = true;
                    }
                    
                    fieldGroup.appendChild(input);
                });
            } else {
                
            }
        }

        // Call the function
        renderAssetCustomFields();
    });
</script>
 
@endsection