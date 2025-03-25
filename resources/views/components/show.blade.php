@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Component Details</h1>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">{{ $component->component_name }}</h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('components.edit', $component) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('components.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Category:</strong> {{ $component->category }}</p>
                    <p><strong>Department:</strong> {{ $component->department }}</p>
                    <p><strong>Serial No:</strong> {{ $component->serial_no }}</p>
                    <p><strong>Model No:</strong> {{ $component->model_no }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Manufacturer:</strong> {{ $component->manufacturer }}</p>
                    <p><strong>Assigned:</strong> {{ $component->assigned ?? 'Not assigned' }}</p>
                    <p><strong>Date Purchased:</strong> {{ $component->date_purchased ? $component->date_purchased->format('F d, Y') : 'N/A' }}</p>
                    <p><strong>Purchased From:</strong> {{ $component->purchased_from }}</p>
                </div>
            </div>
            @if($component->log_note)
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6>Log Note</h6>
                    <p>{{ $component->log_note }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection