@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-tachometer-alt text-primary me-2"></i>Student Dashboard
        </h1>
        <p class="text-muted mb-0">Welcome, {{ auth()->user()->usn }}! Your voting portal</p>
    </div>
    <div class="text-end">
        <small class="text-muted">
            <i class="fas fa-clock me-1"></i>Last login: {{ now()->format('M d, Y H:i') }}
        </small>
    </div>
</div>

<!-- Voting Status Alert -->
<div class="row mb-4">
    <div class="col-12">
        @if(auth()->user()->has_voted)
            <div class="alert alert-success border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">Thank You for Voting!</h5>
                        <p class="mb-0">Your vote has been successfully recorded. You can view the results once they are published.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">You Haven't Voted Yet</h5>
                        <p class="mb-2">Don't miss your chance to make your voice heard in the election.</p>
                        <a href="{{ route('student.vote') }}" class="btn btn-warning">
                            <i class="fas fa-vote-yea me-2"></i>Cast Your Vote Now
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Current Election Info -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-vote-yea me-2"></i>Current Election
                </h5>
            </div>
            <div class="card-body">
                <!-- No Active Election -->
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Active Election</h5>
                    <p class="text-muted mb-0">There are currently no active elections. Please check back later.</p>
                </div>
                
                <!-- Active Election (Hidden until election is active) -->
                <div class="d-none" id="active-election">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">Student Council Election 2025</h6>
                            <p class="text-muted mb-2">Vote for your preferred candidates for student council positions.</p>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Voting Period: March 1-3, 2025
                                </small>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>Total Registered Voters: 1,250
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <div class="mb-2">
                                    <span class="badge bg-success fs-6">ACTIVE</span>
                                </div>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100">
                                        35% Voted
                                    </div>
                                </div>
                                <small class="text-muted">438 out of 1,250 students have voted</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                @if(!auth()->user()->has_voted)
                    <i class="fas fa-vote-yea fa-3x text-primary mb-3"></i>
                    <h5>Cast Your Vote</h5>
                    <p class="text-muted mb-3">Participate in the democratic process by voting for your preferred candidates.</p>
                    <a href="{{ route('student.vote') }}" class="btn btn-primary">
                        <i class="fas fa-vote-yea me-2"></i>Vote Now
                    </a>
                @else
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>Vote Submitted</h5>
                    <p class="text-muted mb-3">Your vote has been recorded successfully. Thank you for participating!</p>
                    <button class="btn btn-success" disabled>
                        <i class="fas fa-check me-2"></i>Vote Completed
                    </button>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                <h5>Election Results</h5>
                <p class="text-muted mb-3">View the results once they are published by the administration.</p>
                <button class="btn btn-outline-info" disabled>
                    <i class="fas fa-clock me-2"></i>Results Pending
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Student Info -->
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user text-primary me-2"></i>Your Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold text-muted">Student ID:</td>
                                <td>{{ auth()->user()->usn }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Account Type:</td>
                                <td><span class="badge bg-primary">{{ ucfirst(auth()->user()->user_type) }}</span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted">Voting Status:</td>
                                <td>
                                    @if(auth()->user()->has_voted)
                                        <span class="badge bg-success">Voted</span>
                                    @else
                                        <span class="badge bg-warning">Not Voted</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted">
                            <h6 class="fw-bold mb-2">Voting Guidelines</h6>
                            <ul class="small mb-0">
                                <li>You can only vote once per election</li>
                                <li>Your vote is anonymous and secure</li>
                                <li>Vote for one candidate per position</li>
                                <li>Review your choices before submitting</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection