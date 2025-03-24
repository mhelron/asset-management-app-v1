@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Custom Fields</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Custom Fields</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <!-- Alert Box for Success -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Alert Box for Errors-->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-diamond me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="d-flex justify-content-end">
                    <a href="{{ route('customfields.create') }}" class="btn btn-dark mb-2 float-end"><i class="bi bi-plus-lg me-2"></i>Add Custom Fields</a>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Field Name</th>
                                        <th scope="col">Field Type</th>
                                        <th scope="col">Field Input Type</th>
                                        <th scope="col">Value/s</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Is Required?</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customFields as $field)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $field->name }}</td>
                                            <td>{{ ucfirst($field->type) }}</td>
                                            <td>
                                                @if ($field->type === 'Text')
                                                    {{ ucfirst($field->text_type) }}
                                                    @elseif (in_array($field->type, ['Select', 'Checkbox', 'Radio', 'List']))
                                                    User Input 
                                                @else
                                                @endif
                                            </td>
                                            <td>
                                                @if ($field->type === 'Text')
                                                    User Input
                                                @elseif (in_array($field->type, ['List', 'Checkbox', 'Radio', 'Select']) && !empty($field->options))
                                                    {{ implode(', ', json_decode($field->options, true)) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $field->desc }}</td>
                                            <td>
                                                {{ $field->is_required ? 'Required' : 'Optional' }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('customfields.edit', $field->id) }}" class="btn btn-sm btn-success me-2"><i class="bi bi-pencil-square me-2"></i>Edit</a>
                                                    
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#archiveModal" 
                                                        data-id="{{ $field->id }}" data-name="{{ $field->name }}">
                                                        <i class="bi bi-archive me-2"></i>Archive
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No custom fields found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveModalLabel">Confirm Archive</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to archive <strong id="fieldName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="archiveForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Archive</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Handle Archive Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const archiveButtons = document.querySelectorAll('[data-bs-target="#archiveModal"]');
        const fieldNameElement = document.getElementById('fieldName');
        const archiveForm = document.getElementById('archiveForm');

        archiveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const fieldName = this.getAttribute('data-name');
                const fieldId = this.getAttribute('data-id');
                fieldNameElement.textContent = fieldName;

                // Fix URL construction
                archiveForm.action = "{{ url('custom-fields/archive-custom-field/') }}/" + fieldId;
            });
        });
    });
</script>

@endsection