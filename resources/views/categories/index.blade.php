@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0">Category</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

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
                    <a href="{{ route('categories.add') }}" class="btn btn-dark mb-2 float-end"><i class="bi bi-plus-lg me-2"></i>Add Category</a>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Category Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Options</th>
                                    </tr>
                                </thead>

                                @php $i = 1; @endphp
                                @forelse ($categories as $item)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->desc }}</td>
                                    <td>{{ $item->status }}</td>

                                    <td>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-sm btn-primary me-2 view-details-btn"
                                                data-id="{{ $item->id }}" data-name="{{ $item->category }}">
                                                <i class="bi bi-eye me-2"></i>View Details
                                            </button>
                                            <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-sm btn-success me-2">
                                                <i class="bi bi-pencil-square me-2"></i>Edit
                                            </a>
                                    
                                            <form action="{{ route('categories.delete', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-secondary">
                                                    <i class="bi bi-archive me-2"></i>Archive
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No record found</td>
                                </tr>
                                @endforelse

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.Main content -->

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailsModalLabel">Custom Fields</h5>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Field Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Required</th>
                                </tr>
                            </thead>
                            <tbody id="customFieldsTable">
                                <!-- Custom fields will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal"><i class="bi bi-x-lg me-2"></i>Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveCategoryModal" tabindex="-1" aria-labelledby="archiveCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveCategoryModalLabel">Confirm Archive</h5>
            </div>
            <div class="modal-body">
                Are you sure you want to archive <strong id="categoryName"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="archiveCategoryForm" method="POST" action="">
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
        const archiveButtons = document.querySelectorAll('[data-bs-target="#archiveCategoryModal"]');
        const categoryNameField = document.getElementById('categoryName');
        const archiveCategoryForm = document.getElementById('archiveCategoryForm');

        archiveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const categoryName = this.getAttribute('data-name');
                const categoryId = this.getAttribute('data-id');
                categoryNameField.textContent = categoryName;
                
                archiveCategoryForm.action = "{{ url('categories/archive-category') }}/" + categoryId;
            });
        });
    });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-id');

            // Fetch custom fields via AJAX
            fetch(`/categories/get-custom-fields/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    const customFieldsTable = document.getElementById('customFieldsTable');
                    customFieldsTable.innerHTML = ''; // Clear previous content

                    if (data.length > 0) {
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

                    // Show the modal
                    const viewModal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
                    viewModal.show();
                })
                .catch(error => {
                    console.error('Error fetching custom fields:', error);
                });
        });
    });
});
</script>


@endsection