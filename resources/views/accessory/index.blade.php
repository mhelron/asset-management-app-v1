@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Accessories</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Accessories</li>
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
                    <a href="{{ route('components.create') }}" class="btn btn-dark mb-2 float-end">
                        <i class="bi bi-plus-lg me-2"></i>Add Accessory
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
                                        <th>Department</th>
                                        <th>Serial No</th>
                                        <th>Manufacturer</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse ($accessory as $component)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $accessory->accessory_name }}</td>
                                        <td>{{ $accessory->category }}</td>
                                        <td>{{ $accessory->department }}</td>
                                        <td>{{ $accessory->serial_no }}</td>
                                        <td>{{ $accessory->manufacturer }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('components.show', $accessory) }}" class="btn btn-sm btn-dark me-2">
                                                    <i class="bi bi-eye me-2"></i>View Details
                                                </a>
                                                <a href="{{ route('components.edit', $accessory) }}" class="btn btn-sm btn-success me-2">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit
                                                </a>
                                                <button type="button" class="btn btn-sm btn-secondary archive-btn"
                                                    data-id="{{ $accessory->id }}" 
                                                    data-name="{{ $accessory->accessory_name }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#archiveAccessoryModal">
                                                    <i class="bi bi-archive me-2"></i>Archive
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No accessory found</td>
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
<div class="modal fade" id="archiveAccessoryModal" tabindex="-1" aria-labelledby="archiveAccessoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveAccessoryModalLabel">Confirm Archive</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to archive <strong id="accessoryName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="archiveAccessoryModal" method="POST" action="">
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
            const accessoryId = this.getAttribute('data-id');
            const accessoryName = this.getAttribute('data-name');
            document.getElementById('componentName').textContent = accessoryName;
            document.getElementById('archiveArchiveForm').action = `/accessories/archive-accessory/${accessoryId}`;
        });
    });
});
</script>
@endsection 