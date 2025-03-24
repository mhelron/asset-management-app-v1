@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Asset</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Assets</a></li>
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

                <!-- Edit Inventory Form -->
                <div class="card">
                    <div class="card-body form-container">
                        <form action="{{ route('inventory.update', $inventoryItem->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <!-- Item Name Field -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="item_name">Item Name</label>
                                    <input type="text" name="item_name" id="item_name" class="form-control" 
                                        value="{{ $inventoryItem->item_name }}" required>
                                    @if ($errors->has('item_name'))
                                    <small class="text-danger">{{ $errors->first('item_name') }}</small>
                                    @endif
                                </div>

                                <!-- Category Selection -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="categories">Category</label>
                                    <select name="category_id" id="category_select" class="form-control">
                                        <option value="" disabled>Select a category</option>
                                        @foreach ($categories as $id => $name)
                                        <option value="{{ $id }}" 
                                            {{ isset($inventoryItem->category) && $inventoryItem->category->id == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                        @endforeach 
                                    </select>

                                    @if ($errors->has('category'))
                                    <small class="text-danger">{{ $errors->first('category') }}</small>
                                    @endif
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label>Status <span class="text-danger"> </span></label>
                                    <select name="status" class="form-control">
                                        <option value="Active" {{ old('status', $inventoryItem->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ old('status', $inventoryItem->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Dynamic Custom Fields Container -->
                            <div id="dynamic-fields-container" class="mb-3">
                                <!-- Custom fields will be loaded here -->
                            </div>

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end"><i class="bi bi-pencil-square me-2"></i>Update Asset</button>
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
    const existingValues = @json($inventoryItem['custom_fields'] ?? []);
    
    function loadCategoryFields(categoryId) {
        const fieldsContainer = document.getElementById('dynamic-fields-container');
        
        fieldsContainer.innerHTML = '';
        
        if (categoryId) {
            fieldsContainer.innerHTML = '<div class="text-center"><p>Loading fields...</p></div>';
            
            fetch(`/inventory/get-category-fields/${categoryId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(fields => {
                    fieldsContainer.innerHTML = '';
                    
                    if (fields && fields.length > 0) {                
                        const rowDiv = document.createElement('div');
                        rowDiv.className = 'row';
                        fieldsContainer.appendChild(rowDiv);
                        
                        fields.forEach(field => {
                            const colDiv = document.createElement('div');
                            colDiv.className = 'col-md-6';
                            rowDiv.appendChild(colDiv);
                            
                            const fieldGroup = document.createElement('div');
                            fieldGroup.className = 'form-group mb-3';
                            colDiv.appendChild(fieldGroup);
                            
                            const label = document.createElement('label');
                            label.innerHTML = field.name + (field.is_required ? '<span class="text-danger"></span>' : '');
                            fieldGroup.appendChild(label);
                            
                            let input;
                            const fieldValue = existingValues[field.name] || '';
                            
                            switch(field.type) {
                                case 'text':
                                    input = document.createElement('input');
                                    input.type = 'text';
                                    input.className = 'form-control';
                                    input.name = `custom_fields[${field.name}]`;
                                    input.placeholder = `Enter ${field.name.toLowerCase()}`;
                                    input.value = fieldValue;
                                    if (field.is_required) input.required = true;
                                    break;
                                    
                                case 'number':
                                    input = document.createElement('input');
                                    input.type = 'number';
                                    input.className = 'form-control';
                                    input.name = `custom_fields[${field.name}]`;
                                    input.placeholder = `Enter ${field.name.toLowerCase()}`;
                                    input.value = fieldValue;
                                    if (field.is_required) input.required = true;
                                    break;
                                    
                                case 'date':
                                    input = document.createElement('input');
                                    input.type = 'date';
                                    input.className = 'form-control';
                                    input.name = `custom_fields[${field.name}]`;
                                    input.value = fieldValue;
                                    if (field.is_required) input.required = true;
                                    break;
                                    
                                case 'textarea':
                                    input = document.createElement('textarea');
                                    input.className = 'form-control';
                                    input.name = `custom_fields[${field.name}]`;
                                    input.placeholder = `Enter ${field.name.toLowerCase()}`;
                                    input.rows = 3;
                                    input.textContent = fieldValue;
                                    if (field.is_required) input.required = true;
                                    break;
                                    
                                case 'image':
                                    const imageContainer = document.createElement('div');
                                    
                                    if (fieldValue && typeof fieldValue === 'object' && fieldValue.path) {
                                        const currentImage = document.createElement('div');
                                        currentImage.className = 'mb-2';
                                        
                                        const img = document.createElement('img');
                                        img.src = fieldValue.path;
                                        img.alt = fieldValue.original_name || 'Current image';
                                        img.className = 'img-thumbnail';
                                        img.style.maxHeight = '150px';
                                        
                                        const imageName = document.createElement('p');
                                        imageName.className = 'text-muted small';
                                        imageName.textContent = fieldValue.original_name || '';
                                        
                                        currentImage.appendChild(img);
                                        currentImage.appendChild(imageName);
                                        imageContainer.appendChild(currentImage);
                                        
                                        const hiddenInput = document.createElement('input');
                                        hiddenInput.type = 'hidden';
                                        hiddenInput.name = `existing_images[${field.name}]`;
                                        hiddenInput.value = JSON.stringify(fieldValue);
                                        imageContainer.appendChild(hiddenInput);
                                    }
                                    
                                    input = document.createElement('input');
                                    input.type = 'file';
                                    input.className = 'form-control-file';
                                    input.name = `custom_fields_files[${field.name}]`;
                                    input.accept = 'image/*';
                                    
                                    const newImageLabel = document.createElement('small');
                                    newImageLabel.className = 'text-muted';
                                    newImageLabel.textContent = ' Upload new image (leave empty to keep current)';
                                    
                                    imageContainer.appendChild(input);
                                    imageContainer.appendChild(newImageLabel);
                                    
                                    fieldGroup.appendChild(imageContainer);
                                    break;
                                
                                default:
                                    input = document.createElement('input');
                                    input.type = 'text';
                                    input.className = 'form-control';
                                    input.name = `custom_fields[${field.name}]`;
                                    input.placeholder = `Enter ${field.name.toLowerCase()}`;
                                    input.value = fieldValue;
                                    if (field.is_required) input.required = true;
                            }
                            
                            if (field.type !== 'image') {
                                fieldGroup.appendChild(input);
                            }
                            
                            const errorSpan = document.createElement('small');
                            errorSpan.className = 'text-danger';
                            errorSpan.id = `error-${field.name.replace(/\s+/g, '-').toLowerCase()}`;
                            fieldGroup.appendChild(errorSpan);
                        });
                    } else {
                        fieldsContainer.innerHTML = '<p class="text-info">This category does not have any custom fields defined.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching custom fields:', error);
                    fieldsContainer.innerHTML = '<p class="text-danger">Error loading custom fields. Please try again.</p>';
                });
        }
    }

    document.getElementById('category_select').addEventListener('change', function() {
        loadCategoryFields(this.value);
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_select');
        if (categorySelect.value) {
            loadCategoryFields(categorySelect.value);
        }
    });
</script>

@endsection