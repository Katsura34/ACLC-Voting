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
                        <h3 class="mb-0">{{ \App\Models\Election::count() }}</h3>
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
                        <h3 class="mb-0">{{ \App\Models\User::students()->count() }}</h3>
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
                        <h3 class="mb-0">{{ \App\Models\Party::count() }}</h3>
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
                        <h3 class="mb-0">{{ \App\Models\Candidate::count() }}</h3>
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
                        <a href="{{ route('admin.elections.create') }}" class="btn btn-primary w-100">
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