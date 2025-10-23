@extends('layouts.app')

@section('title', 'Candidates Management')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-user-tie text-primary me-2"></i>Candidates Management
        </h1>
        <p class="text-muted mb-0">Manage candidates for elections and positions</p>
    </div>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Candidate
        </button>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label for="election_filter" class="form-label">
                    <i class="fas fa-vote-yea me-2"></i>Election
                </label>
                <select class="form-select" id="election_filter">
                    <option value="">All Elections</option>
                    <!-- Elections will be populated here -->
                </select>
            </div>
            <div class="col-md-3">
                <label for="party_filter" class="form-label">
                    <i class="fas fa-flag me-2"></i>Party
                </label>
                <select class="form-select" id="party_filter">
                    <option value="">All Parties</option>
                    <!-- Parties will be populated based on election -->
                </select>
            </div>
            <div class="col-md-3">
                <label for="position_filter" class="form-label">
                    <i class="fas fa-award me-2"></i>Position
                </label>
                <select class="form-select" id="position_filter">
                    <option value="">All Positions</option>
                    <!-- Positions will be populated based on election -->
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary w-100" onclick="filterCandidates()">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Candidates List -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>All Candidates
                </h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search candidates...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Empty State -->
        <div class="text-center py-5" id="empty-state">
            <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No Candidates Found</h5>
            <p class="text-muted mb-4">Start by adding candidates to your elections</p>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add First Candidate
            </button>
        </div>
        
        <!-- Candidates Table (Hidden until data exists) -->
        <div class="table-responsive d-none" id="candidates-table">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Candidate Name</th>
                        <th>Election</th>
                        <th>Position</th>
                        <th>Party</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sample Row Template -->
                    <tr>
                        <td>
                            <img src="https://via.placeholder.com/40x40" class="rounded-circle" alt="Candidate Photo">
                        </td>
                        <td>
                            <div>
                                <strong>John Doe</strong>
                                <br>
                                <small class="text-muted">USN: 2021-001234</small>
                            </div>
                        </td>
                        <td><span class="badge bg-primary">Student Election 2025</span></td>
                        <td><span class="badge bg-success">President</span></td>
                        <td><span class="badge bg-warning">Unity Party</span></td>
                        <td>Computer Science</td>
                        <td>4th Year</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>View</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <nav aria-label="Candidates pagination" class="d-none">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
function filterCandidates() {
    const election = document.getElementById('election_filter').value;
    const party = document.getElementById('party_filter').value;
    const position = document.getElementById('position_filter').value;
    
    console.log('Filtering candidates:', { election, party, position });
    // Implementation will filter candidates based on selected criteria
}

// Update party and position dropdowns based on selected election
document.getElementById('election_filter').addEventListener('change', function() {
    const electionId = this.value;
    // Load parties and positions for selected election
    console.log('Loading filters for election:', electionId);
});
</script>
@endsection