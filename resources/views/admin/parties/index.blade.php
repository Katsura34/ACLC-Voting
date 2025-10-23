@extends('layouts.app')

@section('title', 'Parties Management')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-users text-primary me-2"></i>Parties Management
        </h1>
        <p class="text-muted mb-0">Manage political parties for elections</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Party
        </button>
    </div>
</div>

<!-- Election Selection -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4">
                <label for="election_select" class="form-label mb-0">
                    <i class="fas fa-vote-yea me-2"></i>Select Election
                </label>
            </div>
            <div class="col-md-6">
                <select class="form-select" id="election_select">
                    <option value="">Choose an election to manage parties</option>
                    <!-- Elections will be populated here -->
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100" onclick="loadParties()">
                    <i class="fas fa-sync me-1"></i>Load
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Parties List -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">
                    <i class="fas fa-flag me-2"></i>Political Parties
                </h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search parties...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- No Election Selected -->
        <div class="text-center py-5" id="no-election-state">
            <i class="fas fa-arrow-up fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Select an Election</h5>
            <p class="text-muted mb-0">Choose an election above to view and manage its parties</p>
        </div>
        
        <!-- Empty Parties State -->
        <div class="text-center py-5 d-none" id="empty-parties-state">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No Parties Found</h5>
            <p class="text-muted mb-4">Add the first political party for this election</p>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add First Party
            </button>
        </div>
        
        <!-- Parties Grid (Hidden until data exists) -->
        <div class="row d-none" id="parties-grid">
            <!-- Sample Party Card Template -->
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-flag me-2"></i>Party Name
                            </h6>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-light" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-users me-2"></i>View Candidates</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">Party description goes here...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-user-tie me-1"></i>0 Candidates
                            </small>
                            <span class="badge" style="background-color: #007bff;">#PartyColor</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadParties() {
    const electionId = document.getElementById('election_select').value;
    if (!electionId) {
        alert('Please select an election first.');
        return;
    }
    
    // Implementation will load parties for selected election
    console.log('Loading parties for election:', electionId);
}
</script>
@endsection