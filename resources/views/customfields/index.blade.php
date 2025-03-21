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
                            <table class="table table-hover">

                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Field Name</th>
                                        <th scope="col">Field Type</th>
                                        <th scope="col">Input Type</th>
                                        <th scope="col">Is Required?</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Status</th>
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
                                                @if ($field->type === 'text')
                                                    {{ ucfirst($field->text_type) }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                {{ $field->is_required ? 'Required' : 'Optional' }}
                                            </td>
                                            <td>
                                                @if (in_array($field->type, ['list', 'checkbox', 'radio', 'select']) && is_array($field->options))
                                                    {{ implode(', ', $field->options) }}
                                                @else
                                                    N/A xd
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No custom fields found.</td>
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

@endsection