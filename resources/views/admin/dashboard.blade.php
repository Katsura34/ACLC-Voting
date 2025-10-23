@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-tachometer-alt text-primary me-2"></i>Admin Dashboard
        </h1>
        <p class="text-muted mb-0">Manage elections, parties, candidates and voting system</p>
    </div>
    <div class="text-end">
        <small class="text-muted">
            <i class="fas fa-clock me-1"></i>Last updated: {{ now()->format('M d, Y H:i') }}
        </small>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Elections</h6>
                        <h3 class="mb-0">0</h3>
                        <small>Active & Completed</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-vote-yea fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Students</h6>
                        <h3 class="mb-0">0</h3>
                        <small>Registered Users</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Parties</h6>
                        <h3 class="mb-0">0</h3>
                        <small>All Elections</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-flag fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Candidates</h6>
                        <h3 class="mb-0">0</h3>
                        <small>All Positions</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-tie fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.elections.index') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Create Election
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.parties.index') }}" class="btn btn-success w-100">
                            <i class="fas fa-users me-2"></i>Manage Parties
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.candidates.index') }}" class="btn btn-info w-100">
                            <i class="fas fa-user-tie me-2"></i>Manage Candidates
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('register') }}" class="btn btn-warning w-100">
                            <i class="fas fa-user-plus me-2"></i>Register User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity & System Status -->
<div class="row">
    <!-- Current Election Status -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line text-success me-2"></i>Current Election Status
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">No Active Election</h6>
                    <p class="text-muted mb-3">Create a new election to start the voting process</p>
                    <a href="{{ route('admin.elections.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Election
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Overview -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cogs text-primary me-2"></i>System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-database text-primary me-2"></i>
                            Database Connection
                        </div>
                        <span class="badge bg-success">Connected</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            Security Status
                        </div>
                        <span class="badge bg-success">Secure</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-users text-info me-2"></i>
                            User Sessions
                        </div>
                        <span class="badge bg-info">1 Active</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-server text-warning me-2"></i>
                            System Status
                        </div>
                        <span class="badge bg-success">Operational</span>
                    </div>
                </div>
                
                <hr>
                
                <!-- Admin Tools -->
                <h6 class="fw-bold text-muted mb-2">Admin Tools</h6>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-warning btn-sm" onclick="resetAllVotes()">
                        <i class="fas fa-redo me-2"></i>Reset All Votes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetAllVotes() {
    if (confirm('Are you sure you want to reset ALL user votes? This will:\n\n• Mark all users as "not voted"\n• Clear all vote records\n• Allow users to vote again\n\nThis action cannot be undone!')) {
        // Implementation will be added later
        alert('Reset votes functionality will be implemented in the next phase.');
    }
}
</script>
@endsection