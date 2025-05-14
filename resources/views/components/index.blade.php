@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Components</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Components</li>
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
                    <div class="alert alert-success alert-dismissible fade show" style="border-left: 4px solid #28a745; padding: 10px;">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- Alert Box for Errors-->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" style="border-left: 4px solid #dc3545; padding: 10px;">
                        <i class="bi bi-exclamation-diamond me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="d-flex justify-content-end">
                    <a href="{{ route('components.create') }}" class="btn btn-dark mb-2 float-end">
                        <i class="bi bi-plus-lg me-2"></i>Add Component
                    </a>
                </div>
                <!-- Components Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Component Name</th>
                                        <th>Category</th>
                                        <th>Serial No</th>
                                        <th>Manufacturer</th>
                                        <th>Assigned To</th>
                                        <th>Associated Asset</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse ($components as $component)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $component->component_name }}</td>
                                        <td>{{ $component->category }}</td>
                                        <td>{{ $component->serial_no }}</td>
                                        <td>{{ $component->manufacturer }}</td>
                                        <td>
                                            @if($component->user)
                                                <span class="badge bg-success">
                                                    {{ $component->user->first_name }} {{ $component->user->last_name }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($component->inventory)
                                                <span class="badge bg-info">
                                                    {{ $component->inventory->item_name }} 
                                                    ({{ $component->inventory->asset_tag }})
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('components.show', $component) }}" class="btn btn-sm btn-dark me-2">
                                                    <i class="bi bi-eye me-2"></i>View Details
                                                </a>
                                                <a href="{{ route('components.edit', $component) }}" class="btn btn-sm btn-success me-2">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-secondary archive-btn"
                                                    data-id="{{ $component->id }}" 
                                                    data-name="{{ $component->component_name }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#archiveComponentModal">
                                                    <i class="bi bi-archive me-2"></i>Archive
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No components found</td>
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
<div class="modal fade" id="archiveComponentModal" tabindex="-1" aria-labelledby="archiveComponentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveComponentModalLabel">Confirm Archive</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to archive <strong id="componentName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="archiveComponentForm" method="POST" action="">
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
    document.querySelectorAll('.archive-btn').forEach(button => {
        button.addEventListener('click', function() {
            const componentId = this.getAttribute('data-id');
            const componentName = this.getAttribute('data-name');
            document.getElementById('componentName').textContent = componentName;
            document.getElementById('archiveComponentForm').action = `/components/archive-component/${componentId}`;
        });
    });
});
</script>
@endsection 