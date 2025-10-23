@extends('layouts.app')

@section('title', 'Cast Your Vote')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-vote-yea text-primary me-2"></i>Cast Your Vote
        </h1>
        <p class="text-muted mb-0">Select your preferred candidates for each position</p>
    </div>
    <div>
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>
</div>

@if(auth()->user()->has_voted)
    <!-- Already Voted State -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
            <h3 class="text-success">You Have Already Voted!</h3>
            <p class="text-muted mb-4">Thank you for participating in the election. Your vote has been securely recorded.</p>
            <a href="{{ route('student.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-tachometer-alt me-2"></i>Return to Dashboard
            </a>
        </div>
    </div>
@else
    <!-- No Active Election -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
            <h3 class="text-muted">No Active Election</h3>
            <p class="text-muted mb-4">There are currently no active elections available for voting. Please check back later.</p>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
    
    <!-- Voting Form (Hidden until active election exists) -->
    <div class="d-none" id="voting-form">
        <form id="voteForm" method="POST" action="#">
            @csrf
            
            <!-- Election Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Student Council Election 2025
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">Vote for your preferred candidates in each position. You can only vote once, so please review your choices carefully before submitting.</p>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>Voting Period: March 1-3, 2025
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-users me-1"></i>Registered Voters: 1,250
                            </small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-chart-bar me-1"></i>Votes Cast: 438
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Position: President -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-crown text-warning me-2"></i>President
                        <small class="text-muted ms-2">(Choose 1)</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Candidate 1 -->
                        <div class="col-md-6 mb-3">
                            <div class="card border candidate-card" data-position="president" data-candidate="1">
                                <div class="card-body text-center">
                                    <input type="radio" name="president" value="1" id="president_1" class="d-none">
                                    <label for="president_1" class="w-100 cursor-pointer">
                                        <img src="https://via.placeholder.com/80x80" class="rounded-circle mb-3" alt="Candidate Photo">
                                        <h6 class="fw-bold">John Doe</h6>
                                        <p class="text-primary mb-1">Unity Party</p>
                                        <p class="text-muted small">Computer Science • 4th Year</p>
                                        <p class="small">"Building bridges, creating unity, fostering excellence in our student community."</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Candidate 2 -->
                        <div class="col-md-6 mb-3">
                            <div class="card border candidate-card" data-position="president" data-candidate="2">
                                <div class="card-body text-center">
                                    <input type="radio" name="president" value="2" id="president_2" class="d-none">
                                    <label for="president_2" class="w-100 cursor-pointer">
                                        <img src="https://via.placeholder.com/80x80" class="rounded-circle mb-3" alt="Candidate Photo">
                                        <h6 class="fw-bold">Jane Smith</h6>
                                        <p class="text-success mb-1">Progress Party</p>
                                        <p class="text-muted small">Business Administration • 3rd Year</p>
                                        <p class="small">"Innovation, transparency, and student empowerment for a better tomorrow."</p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Position: Vice President -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie text-info me-2"></i>Vice President
                        <small class="text-muted ms-2">(Choose 1)</small>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- VP Candidates would go here -->
                        <div class="col-12 text-center text-muted py-3">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>Candidate information will be loaded here</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Submit Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>Important Notice
                            </h6>
                            <ul class="small text-muted mb-0">
                                <li>You can only vote once in this election</li>
                                <li>Please review all your selections before submitting</li>
                                <li>Your vote is anonymous and cannot be changed after submission</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-outline-secondary me-2" onclick="reviewVotes()">
                                <i class="fas fa-eye me-2"></i>Review Votes
                            </button>
                            <button type="submit" class="btn btn-success" id="submitVote" disabled>
                                <i class="fas fa-check me-2"></i>Submit Vote
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endif

<!-- Vote Confirmation Modal -->
<div class="modal fade" id="confirmVoteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle text-success me-2"></i>Confirm Your Vote
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Please confirm your vote selections:</p>
                <div id="vote-summary">
                    <!-- Vote summary will be populated here -->
                </div>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Once submitted, your vote cannot be changed.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-edit me-2"></i>Edit Votes
                </button>
                <button type="button" class="btn btn-success" onclick="confirmSubmit()">
                    <i class="fas fa-check me-2"></i>Confirm & Submit
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.candidate-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.candidate-card:hover {
    border-color: #0d6efd !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.candidate-card.selected {
    border-color: #0d6efd !important;
    background-color: #f8f9ff;
}

.cursor-pointer {
    cursor: pointer;
}
</style>

<script>
// Handle candidate selection
document.addEventListener('DOMContentLoaded', function() {
    const candidateCards = document.querySelectorAll('.candidate-card');
    const submitButton = document.getElementById('submitVote');
    
    candidateCards.forEach(card => {
        card.addEventListener('click', function() {
            const position = this.dataset.position;
            const input = this.querySelector('input[type="radio"]');
            
            // Remove selected class from other cards in same position
            document.querySelectorAll(`[data-position="${position}"]`).forEach(c => {
                c.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            this.classList.add('selected');
            input.checked = true;
            
            // Check if all positions have selections
            checkFormCompletion();
        });
    });
    
    function checkFormCompletion() {
        const positions = ['president']; // Add more positions as needed
        let allSelected = true;
        
        positions.forEach(position => {
            const selected = document.querySelector(`input[name="${position}"]:checked`);
            if (!selected) allSelected = false;
        });
        
        submitButton.disabled = !allSelected;
    }
});

function reviewVotes() {
    // Populate vote summary modal
    const summary = document.getElementById('vote-summary');
    summary.innerHTML = '<p class="text-muted">President: <strong>John Doe (Unity Party)</strong></p>';
    
    new bootstrap.Modal(document.getElementById('confirmVoteModal')).show();
}

function confirmSubmit() {
    // Submit the form
    document.getElementById('voteForm').submit();
}
</script>
@endsection