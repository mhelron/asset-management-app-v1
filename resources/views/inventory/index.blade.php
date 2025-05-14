@extends('layouts.app')

@section('content')

<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Assets</h1>
            </div>
            <div class="col-sm-6" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb float-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Assets</li>
                </ol>
            </div>
        </div>  
    </div>
</div>

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
                    <a href="{{ route('inventory.create') }}" class="btn btn-dark mb-2"><i class="bi bi-plus-lg me-2"></i>Add Asset</a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Asset</th>
                                        <th>Image</th>
                                        <th>Asset Tag</th>
                                        <th>Serial No.</th>
                                        <th>Model</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($inventory as $index => $item)
                                    <tr class="align-middle">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td>
                                            @if($item->image_path)
                                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="Asset Image" width="100">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->asset_tag }}</td>
                                        <td>{{ $item->serial_no }}</td>
                                        <td>{{ $item->model_no }}</td>
                                        <td>{{ $item->category->category ?? 'N/A' }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-sm btn-dark me-2 view-details-btn" 
                                                    data-id="{{ $item->id }}" data-name="{{ $item->item_name }}">
                                                    <i class="bi bi-eye me-2"></i>View
                                                </button>
                                                <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-sm btn-success me-2">
                                                    <i class="bi bi-pencil-square me-2"></i>Edit</a>
                                                <button type="button" class="btn btn-sm btn-secondary archive-btn" 
                                                    data-bs-toggle="modal" data-bs-target="#archiveModal" 
                                                    data-id="{{ $item->id }}" data-name="{{ $item->item_name }}">
                                                    <i class="bi bi-archive me-2"></i>Archive
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No item found</td>
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
                Are you sure you want to archive <strong id="inventoryName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="archiveInventoryForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Archive</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.archive-btn').forEach(button => {
            button.addEventListener('click', function() {
                const inventoryId = this.getAttribute('data-id');
                const inventoryName = this.getAttribute('data-name');
    
                document.getElementById('inventoryName').textContent = inventoryName;
                document.getElementById('archiveInventoryForm').action = `/inventory/archive-item/${inventoryId}`;
            });
        });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get all view details buttons
    const viewButtons = document.querySelectorAll('.view-details-btn');
    
    // Debug check
    console.log('Found view buttons:', viewButtons.length);
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const itemName = this.getAttribute('data-name');
            
            console.log('Viewing details for item:', itemId, itemName);
            
            // Update modal title to include the item name
            document.getElementById('viewDetailsModalLabel').textContent = `Custom Fields - ${itemName}`;
            
            // Show loading state in the table
            const customFieldsTable = document.getElementById('customFieldsTable');
            customFieldsTable.innerHTML = '<tr><td colspan="3" class="text-center">Loading...</td></tr>';
            
            // Show the modal immediately to improve perceived performance
            const viewModal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
            viewModal.show();
            
            // Correct URL path that matches your route
            const url = `/inventory/get-custom-fields/${itemId}`;
            console.log('Fetching from URL:', url);
            
            // Then fetch the data
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    customFieldsTable.innerHTML = ''; // Clear loading message
                    
                    if (data && data.length > 0) {
                        data.forEach(field => {
                            let row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${field.name}</td>
                                <td>${field.type}</td>
                                <td>${field.is_required ? 'Yes' : 'No'}</td>
                            `;
                            customFieldsTable.appendChild(row);
                        });
                    } else {
                        customFieldsTable.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center text-muted">No custom fields available</td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching custom fields:', error);
                    customFieldsTable.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center text-danger">Error loading custom fields: ${error.message}</td>
                        </tr>
                    `;
                });
        });
    });
});
    </script>
    

@endsection