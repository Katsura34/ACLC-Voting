@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 mb-0">
      <i class="fas fa-gauge text-primary me-2"></i>Admin Dashboard
    </h1>
    <p class="text-muted mb-0">Overview and quick actions</p>
  </div>
  <div>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-primary">
      <i class="fas fa-vote-yea me-2"></i>Manage Elections
    </a>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i class="fas fa-vote-yea fa-2x text-primary"></i>
          </div>
          <div>
            <div class="text-muted small">Total Elections</div>
            <div class="h4 mb-0">{{ $totalElections }}</div>
            <small class="text-muted">Active & Completed</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i class="fas fa-users fa-2x text-success"></i>
          </div>
          <div>
            <div class="text-muted small">Total Students</div>
            <div class="h4 mb-0">{{ $totalStudents }}</div>
            <small class="text-muted">Registered Users</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i class="fas fa-flag fa-2x text-warning"></i>
          </div>
          <div>
            <div class="text-muted small">Total Parties</div>
            <div class="h4 mb-0">{{ $totalParties }}</div>
            <small class="text-muted">All Elections</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <i class="fas fa-user-tie fa-2x text-info"></i>
          </div>
          <div>
            <div class="text-muted small">Total Candidates</div>
            <div class="h4 mb-0">{{ $totalCandidates }}</div>
            <small class="text-muted">All Positions</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-vote-yea me-2"></i>Active Election</h5>
        <div>
          <a href="{{ route('admin.elections.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-list me-1"></i>All Elections
          </a>
        </div>
      </div>
      <div class="card-body">
        @if(!$activeElection)
          <div class="text-center py-4 text-muted">
            <i class="fas fa-calendar-times fa-3x mb-3"></i>
            <div>No active election at the moment.</div>
          </div>
        @else
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h5 class="mb-1">{{ $activeElection->title }}</h5>
              <div class="text-muted small">
                @if($activeElection->start_date) Starts {{ $activeElection->start_date->format('M d, Y H:i') }} • @endif
                @if($activeElection->end_date) Ends {{ $activeElection->end_date->format('M d, Y H:i') }} @endif
              </div>
              <div class="mt-2">
                @if($activeElection->status === 'active')
                  <span class="badge bg-success">ACTIVE</span>
                @elseif($activeElection->status === 'completed')
                  <span class="badge bg-secondary">COMPLETED</span>
                @elseif($activeElection->status === 'cancelled')
                  <span class="badge bg-danger">CANCELLED</span>
                @else
                  <span class="badge bg-warning">DRAFT</span>
                @endif
              </div>
            </div>
            <div class="text-end">
              <div class="small text-muted mb-1">
                {{ $activeElection->total_votes_cast }} / {{ $activeElection->total_registered_voters }} voted
              </div>
              <div class="progress" style="height: 20px; width: 260px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $activeElection->voting_percentage }}%">
                  {{ number_format($activeElection->voting_percentage, 2) }}%
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.elections.show', $activeElection) }}" class="btn btn-outline-primary">
              <i class="fas fa-eye me-1"></i>Open Election
            </a>
            <form action="{{ route('admin.elections.toggle', $activeElection) }}" method="POST">
              @csrf
              <button class="btn btn-outline-warning" onclick="return confirm('Toggle active status for this election?')">
                <i class="fas fa-power-off me-1"></i>{{ $activeElection->is_active ? 'Deactivate' : 'Activate' }}
              </button>
            </form>
            @if(!$activeElection->results_published)
              <form action="{{ route('admin.elections.publish', $activeElection) }}" method="POST">
                @csrf
                <button class="btn btn-outline-info">
                  <i class="fas fa-bullhorn me-1"></i>Publish Results
                </button>
              </form>
            @endif
            <form action="{{ route('admin.elections.reset', $activeElection) }}" method="POST" onsubmit="return confirm('Reset ALL votes and mark all users as not voted?')">
              @csrf
              <button class="btn btn-outline-danger">
                <i class="fas fa-redo me-1"></i>Reset Votes
              </button>
            </form>
          </div>
        @endif
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-clock-rotate-left me-2"></i>Recent Elections</h5>
      </div>
      <div class="card-body">
        @if($recentElections->isEmpty())
          <div class="text-muted">No elections created yet.</div>
        @else
          <div class="table-responsive">
            <table class="table align-middle">
              <thead class="table-light">
                <tr>
                  <th>Title</th>
                  <th>Status</th>
                  <th>Turnout</th>
                  <th>Created</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              @foreach($recentElections as $e)
                <tr>
                  <td class="fw-semibold">{{ $e->title }}</td>
                  <td>
                    @if($e->status === 'active')
                      <span class="badge bg-success">Active</span>
                    @elseif($e->status === 'completed')
                      <span class="badge bg-secondary">Completed</span>
                    @elseif($e->status === 'cancelled')
                      <span class="badge bg-danger">Cancelled</span>
                    @else
                      <span class="badge bg-warning">Draft</span>
                    @endif
                  </td>
                  <td>{{ number_format($e->voting_percentage ?? 0, 2) }}%</td>
                  <td class="text-muted small">{{ $e->created_at?->diffForHumans() }}</td>
                  <td class="text-end">
                    <a href="{{ route('admin.elections.show', $e) }}" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-eye"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
      </div>
      <div class="list-group list-group-flush">
        <a href="{{ route('admin.elections.create') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-plus me-2 text-primary"></i>Create Election
        </a>
        <a href="{{ route('admin.elections.index') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-list me-2 text-secondary"></i>List Elections
        </a>
        <a href="{{ route('admin.parties.index') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-people-group me-2 text-info"></i>Parties
        </a>
        <a href="{{ route('admin.candidates.index') }}" class="list-group-item list-group-item-action">
          <i class="fas fa-user-tie me-2 text-success"></i>Candidates
        </a>
      </div>
    </div>
  </div>
</div>
@endsection