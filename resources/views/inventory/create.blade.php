@extends('layouts.app')

@section('content')

<!-- Content Header -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Inventory</h1>
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

                            <!-- Custom Fields (Will be loaded dynamically) -->
                            <div id="dynamic-fields-container" class="mb-3"></div>

                            <!-- Submit button -->
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-dark float-end"><i class="bi bi-plus-lg me-2"></i>Add Item</button>
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
    document.getElementById('category_select').addEventListener('change', function() {
        const categoryId = this.value;
        const fieldsContainer = document.getElementById('dynamic-fields-container');

        // Clear previous fields
        fieldsContainer.innerHTML = '';

        if (categoryId) {
            // Show loading indicator
            fieldsContainer.innerHTML = '<div class="text-center"><p>Loading fields...</p></div>';

            // Fetch custom fields for selected category
            fetch(`/inventory/get-category-fields/${categoryId}`)
                .then(response => response.json())
                .then(fields => {
                    fieldsContainer.innerHTML = '';

                    if (fields.length > 0) {
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
                            label.innerHTML = field.name + (field.is_required ? '<span class="text-danger"> *</span>' : '');
                            fieldGroup.appendChild(label);

                            let input;

                            switch(field.type) {
                                case 'text':
                                    input = document.createElement('input');
                                    input.type = 'text';
                                    break;
                                case 'number':
                                    input = document.createElement('input');
                                    input.type = 'number';
                                    break;
                                case 'date':
                                    input = document.createElement('input');
                                    input.type = 'date';
                                    break;
                                case 'textarea':
                                    input = document.createElement('textarea');
                                    input.rows = 3;
                                    break;
                                case 'image':
                                    input = document.createElement('input');
                                    input.type = 'file';
                                    input.accept = 'image/*';
                                    break;
                                default:
                                    input = document.createElement('input');
                                    input.type = 'text';
                            }

                            input.className = 'form-control';
                            input.name = `custom_fields[${field.name}]`;
                            if (field.is_required) input.required = true;
                            fieldGroup.appendChild(input);
                        });
                    } else {
                        fieldsContainer.innerHTML = '<p class="text-info">No custom fields available for this category.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching custom fields:', error);
                    fieldsContainer.innerHTML = '<p class="text-danger">Error loading custom fields. Please try again.</p>';
                });
        }
    });
</script>

@endsection
