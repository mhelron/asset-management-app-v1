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
                                            <option value="Checkbox" {{ old('type', $customField->type) == 'Checkbox' ? 'selected' : '' }}>Checkbox</option>
                                            <option value="Radio" {{ old('type', $customField->type) == 'Radio' ? 'selected' : '' }}>Radio Button</option>
                                            <option value="Select" {{ old('type', $customField->type) == 'Select' ? 'selected' : '' }}>Select Dropdown</option>
                                        </select>
                                        @error('type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div id="text-type-container" class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Text Format<span class="text-danger"> *</span></label>
                                        <select name="text_type" class="form-control" id="text-type-select">
                                            <option value="" disabled {{ old('text_type') ? '' : 'selected' }}>Select a format</option>
                                            <option value="Any" {{ old('text_type', $customField->text_type) == 'Any' ? 'selected' : '' }}>Any (No Restrictions)</option>
                                            <option value="Email" {{ old('text_type', $customField->text_type) == 'Email' ? 'selected' : '' }}>Email</option>
                                            <option value="Image" {{ old('text_type', $customField->text_type) == 'Image' ? 'selected' : '' }}>Image File Path</option>
                                            <option value="Date" {{ old('text_type', $customField->text_type) == 'Date' ? 'selected' : '' }}>Date</option>
                                            <option value="Alpha-Dash" {{ old('text_type', $customField->text_type) == 'Alpha-Dash' ? 'selected' : '' }}>Alpha-Dash</option>
                                            <option value="Numeric" {{ old('text_type', $customField->text_type) == 'Numeric' ? 'selected' : '' }}>Numeric</option>
                                            <option value="Custom"{{ old('text_type', $customField->text_type) == 'Custom' ? 'selected' : '' }}>Custom Format</option>
                                        </select>
                                        
                                        <div id="text-type-hint" class="text-muted mt-2">
                                            <!-- Hints will be dynamically populated here -->
                                        </div>
                                        
                                        @error('text_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">

                                </div>

                                <div class="col-md-6">
                                    <!-- Custom Regex Container -->
                                    <div id="custom-regex-container" style="display: none;">
                                        <div class="form-group mb-3">
                                            <label>Custom Regex Pattern<span class="text-danger"> *</span></label>
                                            <input type="text" name="custom_regex" class="form-control" 
                                                placeholder="Enter your custom regex pattern" 
                                                value="{{ old('custom_regex', isset($customField) ? $customField->custom_regex : '') }}"
                                                id="custom-regex-input">
                                             <small class="text-muted">
                                                Example patterns:
                                                <ul class="mb-0">
                                                    <li><code>^\d{3}-\d{2}-\d{4}$</code> (SSN format: 123-45-6789)</li>
                                                    <li><code>^\d{15}$</code> (IMEI Code: 123456789012345)</li>
                                                </ul>
                                            </small>
                                            @error('custom_regex')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Regex Container -->
                            <div id="custom-regex-container" class="col-md-6" style="display: none;">
                                <div class="form-group mb-3">
                                    <label>Custom Regex Pattern<span class="text-danger"> *</span></label>
                                    <input type="text" name="custom_regex" class="form-control" 
                                        placeholder="Enter your custom regex pattern" 
                                        value="{{ old('custom_regex', isset($customField) ? $customField->custom_regex : '') }}"
                                        id="custom-regex-input">
                                    <small class="text-muted">
                                        Example patterns:
                                        <ul class="mb-0">
                                            <li><code>^\d{3}-\d{2}-\d{4}$</code> (SSN format: 123-45-6789)</li>
                                            <li><code>^\d{15}$</code> (IMEI Code: 123456789012345)</li>
                                        </ul>
                                    </small>
                                    @error('custom_regex')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Options for List, Checkbox, Radio, Select -->
                            <div id="options-container" class="mb-3" style="display: none;">
                                <label>Options<span class="text-danger"> *</span></label>
                                <div id="options-list">
                                    @php
                                        $existingOptions = old('options', isset($customField) 
                                            ? (is_string($customField->options) ? json_decode($customField->options, true) : $customField->options) 
                                            : []
                                        );
                                    @endphp

                                    
                                    @if ($errors->has('options'))
                                        <small class="text-danger">{{ $errors->first('options') }}</small>
                                    @endif
                                    
                                    @foreach ($existingOptions as $index => $option)
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
                                    
                                    {{-- Always provide at least one empty input for new option --}}
                                    <div class="d-flex mb-2">
                                        <input type="text" name="options[]" class="form-control me-2" placeholder="Enter option">
                                        <button type="button" class="btn btn-success d-flex align-items-center" id="add-option">
                                            <i class="bi bi-plus-lg me-2"></i> Add
                                        </button>
                                    </div>                             
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fieldTypeSelect = document.getElementById('field_type');
        const textTypeContainer = document.getElementById('text-type-container');
        const textTypeSelect = document.getElementById('text-type-select');
        const textTypeHint = document.getElementById('text-type-hint');
        const customRegexContainer = document.getElementById('custom-regex-container');
        const customRegexInput = document.getElementById('custom-regex-input');
        const optionsContainer = document.getElementById('options-container');
        const optionsList = document.getElementById('options-list');

        // Retrieve old values and errors
        const oldOptions = @json(old('options', isset($customField) && $customField->options ? $customField->options : []));
        const optionsErrors = @json($errors->get('options.*'));

        const hasCustomRegexError = @json($errors->has('custom_regex'));
        const oldTextType = @json(old('text_type', isset($customField) ? $customField->text_type : ''));
        const oldCustomRegex = @json(old('custom_regex', isset($customField) ? $customField->custom_regex : ''));

        // Hint dictionary
        const hints = {
            'Any': 'No input restrictions. Example: "Hello World 123"',
            'Email': 'Must be a valid email address. Example: "user@example.com"',
            'Image': 'File path for images. Example: "/uploads/profile.jpg"',
            'Date': 'Date in YYYY-MM-DD format. Example: "2024-03-27"',
            'Alpha-Dash': 'Allows letters, numbers, underscores, and hyphens. Example: "user-profile_123"',
            'Numeric': 'Only numbers (integer or decimal). Example: "12345" or "3.14"',
            'Custom': 'Define your own validation pattern using regex. Provide a custom validation rule.'
        };

        function toggleContainers() {
            const selectedType = fieldTypeSelect.value;

            // Show/hide text type container
            textTypeContainer.style.display = selectedType === 'Text' ? 'block' : 'none';

            // Reset text type dropdown and custom regex if not Text
            if (selectedType !== 'Text') {
                textTypeSelect.selectedIndex = 0;
                resetCustomRegex();
            }

            // Show/hide options container
            if (['Checkbox', 'Radio', 'Select'].includes(selectedType)) {
                optionsContainer.style.display = 'block';
                renderOptions();
            } else {
                optionsContainer.style.display = 'none';
                optionsList.innerHTML = ''; // Clear options
            }
        }

        function toggleCustomRegexContainer() {
            const selectedType = textTypeSelect.value;

            // Update hint text
            textTypeHint.textContent = hints[selectedType] || '';

            // Show/hide custom regex container
            if (selectedType === 'Custom') {
                customRegexContainer.style.display = 'block';
            } else {
                resetCustomRegex();
            }
        }

        function resetCustomRegex() {
            customRegexContainer.style.display = 'none';
            customRegexInput.value = ''; // Clear custom regex input
        }

        function renderOptions() {
            // Clear existing options
            optionsList.innerHTML = '';

            if (['Checkbox', 'Radio', 'Select'].includes(fieldTypeSelect.value)) {
                if (oldOptions.length > 0) {
                    oldOptions.forEach((option, index) => {
                        addOption(option || '', optionsErrors[`options.${index}`] || '');
                    });
                } else {
                    addOption('', '');
                }
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

            // Insert before add button or append
            const addButton = optionsList.querySelector('#add-option-wrapper');
            if (addButton) {
                optionsList.insertBefore(optionWrapper, addButton);
            } else {
                optionsList.appendChild(optionWrapper);
            }
        }

        function addAddButton() {
            const existingAddButton = document.getElementById('add-option-wrapper');
            if (existingAddButton) existingAddButton.remove();

            const addButtonWrapper = document.createElement('div');
            addButtonWrapper.id = 'add-option-wrapper';
            addButtonWrapper.className = 'd-flex justify-content-end mt-2';
            addButtonWrapper.innerHTML = `
                <button type="button" class="btn btn-success d-flex align-items-center" id="add-option">
                    <i class="bi bi-plus-lg me-2"></i> Add
                </button>
            `;
            optionsList.appendChild(addButtonWrapper);

            document.getElementById('add-option').addEventListener('click', function() {
                addOption('', '');
            });
        }

        // Ensure at least one option input remains
        optionsList.addEventListener('click', function(event) {
            if (event.target.closest('.remove-option')) {
                event.target.closest('.option-wrapper').remove();
                if (optionsList.querySelectorAll('.option-wrapper').length === 0) {
                    addOption('', '');
                }
            }
        });

        function restoreCustomRegexState() {
            if (oldTextType === 'Custom' || hasCustomRegexError || oldCustomRegex) {
                textTypeSelect.value = 'Custom';
                customRegexContainer.style.display = 'block';
                textTypeHint.textContent = hints['Custom'];
                customRegexInput.value = oldCustomRegex || '';
            }

            if (textTypeSelect.value) {
                textTypeHint.textContent = hints[textTypeSelect.value] || '';
            }
        }

        // Event Listeners
        textTypeSelect.addEventListener('change', toggleCustomRegexContainer);
        fieldTypeSelect.addEventListener('change', function() {
            oldOptions.length = 0; // Clear old options
            resetCustomRegex();
            toggleContainers();
        });

        // Initialize on page load
        toggleContainers();
        restoreCustomRegexState();
    });
</script>



@endsection