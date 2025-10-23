@extends('layouts.app')

@section('title', 'Elections Management')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-vote-yea text-primary me-2"></i>Elections Management
        </h1>
        <p class="text-muted mb-0">Create and manage voting elections</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Election
        </button>
    </div>
</div>

<!-- Elections List -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>All Elections
                </h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search elections...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="fas fa-vote-yea fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No Elections Found</h5>
            <p class="text-muted mb-4">Get started by creating your first election</p>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create First Election
            </button>
        </div>
        
        <!-- Elections Table (Hidden until data exists) -->
        <div class="table-responsive d-none">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Election Title</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Votes Cast</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows will go here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection