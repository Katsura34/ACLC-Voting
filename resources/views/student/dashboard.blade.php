@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
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

@php
    $activeElection = \App\Models\Election::where('is_active', true)->first();
@endphp

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
                        @if($activeElection)
                            <a href="{{ route('student.vote') }}" class="btn btn-warning">
                                <i class="fas fa-vote-yea me-2"></i>Cast Your Vote Now
                            </a>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="fas fa-ban me-2"></i>No Active Election
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-vote-yea me-2"></i>Current Election
                </h5>
            </div>
            <div class="card-body">
                @if(!$activeElection)
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Active Election</h5>
                        <p class="text-muted mb-0">There are currently no active elections. Please check back later.</p>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary">{{ $activeElection->title }}</h6>
                            <p class="text-muted mb-2">{{ $activeElection->description }}</p>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    @if($activeElection->start_date) Starts {{ $activeElection->start_date->format('M d, Y H:i') }} • @endif
                                    @if($activeElection->end_date) Ends {{ $activeElection->end_date->format('M d, Y H:i') }} @endif
                                </small>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>Total Registered Voters: {{ $activeElection->total_registered_voters ?? 0 }}
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-end">
                                <div class="mb-2">
                                    @if($activeElection->is_active)
                                        <span class="badge bg-success fs-6">ACTIVE</span>
                                    @else
                                        <span class="badge bg-secondary fs-6">{{ strtoupper($activeElection->status) }}</span>
                                    @endif
                                </div>
                                <div class="progress mb-2" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $activeElection->voting_percentage ?? 0 }}%"
                                         aria-valuenow="{{ $activeElection->voting_percentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ number_format($activeElection->voting_percentage ?? 0, 2) }}% Voted
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $activeElection->total_votes_cast ?? 0 }} out of {{ $activeElection->total_registered_voters ?? 0 }} students have voted
                                </small>
                            </div>
                        </div>
                    </div>
                    @if(!auth()->user()->has_voted)
                        <div class="mt-3">
                            <a href="{{ route('student.vote') }}" class="btn btn-primary">
                                <i class="fas fa-vote-yea me-2"></i>Go to Voting
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center py-4">
                @if(!auth()->user()->has_voted && $activeElection)
                    <i class="fas fa-vote-yea fa-3x text-primary mb-3"></i>
                    <h5>Cast Your Vote</h5>
                    <p class="text-muted mb-3">Participate in the democratic process by voting for your preferred candidates.</p>
                    <a href="{{ route('student.vote') }}" class="btn btn-primary">
                        <i class="fas fa-vote-yea me-2"></i>Vote Now
                    </a>
                @elseif(!auth()->user()->has_voted && !$activeElection)
                    <i class="fas fa-ban fa-3x text-muted mb-3"></i>
                    <h5>No Active Election</h5>
                    <p class="text-muted mb-3">Please check back when an election starts.</p>
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-clock me-2"></i>Waiting
                    </button>
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
                @php $pub = $activeElection && $activeElection->results_published; @endphp
                @if($pub)
                    <p class="text-muted mb-3">Results are published. Check the results page.</p>
                    <a href="{{ route('student.vote') }}" class="btn btn-info">
                        <i class="fas fa-chart-line me-2"></i>View Results
                    </a>
                @else
                    <p class="text-muted mb-3">View the results once they are published by the administration.</p>
                    <button class="btn btn-outline-info" disabled>
                        <i class="fas fa-clock me-2"></i>Results Pending
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

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
                                <li>Vote per the allowed choices for each position</li>
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