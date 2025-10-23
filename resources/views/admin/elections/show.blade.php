@extends('layouts.app')

@section('title', $election->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-ballot-check text-primary me-2"></i>{{ $election->title }}
        </h1>
        <p class="text-muted mb-0">Election dashboard and management</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('admin.elections.edit', $election) }}" class="btn btn-outline-secondary">
            <i class="fas fa-edit me-1"></i>Edit
        </a>
        <form action="{{ route('admin.elections.toggle', $election) }}" method="POST">
            @csrf
            <button class="btn btn-outline-{{ $election->is_active ? 'warning' : 'success' }}" onclick="return confirm('Are you sure you want to {{ $election->is_active ? 'deactivate' : 'activate' }} this election?')">
                <i class="fas fa-power-off me-1"></i>{{ $election->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
        <form action="{{ route('admin.elections.publish', $election) }}" method="POST">
            @csrf
            <button class="btn btn-outline-info">
                <i class="fas fa-bullhorn me-1"></i>Publish Results
            </button>
        </form>
        <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><strong>Election Details</strong></div>
            <div class="card-body">
                <div class="mb-2">
                    <span class="text-muted">Status:</span>
                    @if($election->status === 'active')
                        <span class="badge bg-success">Active</span>
                    @elseif($election->status === 'completed')
                        <span class="badge bg-secondary">Completed</span>
                    @elseif($election->status === 'cancelled')
                        <span class="badge bg-danger">Cancelled</span>
                    @else
                        <span class="badge bg-warning">Draft</span>
                    @endif
                </div>
                <div class="mb-2"><span class="text-muted">Start:</span> {{ optional($election->start_date)->format('M d, Y H:i') ?? '—' }}</div>
                <div class="mb-2"><span class="text-muted">End:</span> {{ optional($election->end_date)->format('M d, Y H:i') ?? '—' }}</div>
                <div class="mb-2"><span class="text-muted">Allow Abstain:</span> {{ $election->allow_abstain ? 'Yes' : 'No' }}</div>
                <div class="mb-2"><span class="text-muted">Live Results:</span> {{ $election->show_live_results ? 'Yes' : 'No' }}</div>
                <div class="mb-2"><span class="text-muted">Results Published:</span> {{ $election->results_published ? 'Yes' : 'No' }}</div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><strong>Analytics</strong></div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Registered Voters</span>
                    <span class="fw-semibold">{{ $election->total_registered_voters }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Votes Cast</span>
                    <span class="fw-semibold">{{ $election->total_votes_cast }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Turnout</span>
                    <span class="fw-semibold">{{ number_format($election->voting_percentage, 2) }}%</span>
                </div>
                <div class="progress" style="height: 18px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $election->voting_percentage }}%">
                        {{ number_format($election->voting_percentage, 2) }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>Danger Zone</strong></div>
            <div class="card-body">
                <form action="{{ route('admin.elections.reset', $election) }}" method="POST" onsubmit="return confirm('Reset ALL votes for this election and mark users as not voted? This cannot be undone.');">
                    @csrf
                    <button class="btn btn-outline-danger w-100">
                        <i class="fas fa-redo me-1"></i>Reset All Votes
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Positions</strong>
                <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add Position</a>
            </div>
            <div class="card-body">
                @if($election->positions->isEmpty())
                    <div class="text-center text-muted py-3">No positions yet.</div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach($election->positions as $pos)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $pos->name }}</strong>
                                    <span class="text-muted small ms-2">Max winners: {{ $pos->max_winners }}</span>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                    <a href="#" class="btn btn-outline-danger"><i class="fas fa-trash"></i></a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Parties</strong>
                <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add Party</a>
            </div>
            <div class="card-body">
                @if($election->parties->isEmpty())
                    <div class="text-center text-muted py-3">No parties yet.</div>
                @else
                    <div class="row">
                        @foreach($election->parties as $party)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-header" style="background-color: {{ $party->color ?? '#e9ecef' }}22;">
                                        <strong>{{ $party->name }}</strong>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small">{{ $party->description }}</p>
                                        <a href="#" class="btn btn-outline-primary btn-sm">View Candidates</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Candidates</strong>
                <a href="#" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Add Candidate</a>
            </div>
            <div class="card-body">
                @if($election->candidates->isEmpty())
                    <div class="text-center text-muted py-3">No candidates yet.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Party</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($election->candidates as $c)
                                    <tr>
                                        <td>{{ $c->first_name }} {{ $c->last_name }}</td>
                                        <td>{{ optional($c->position)->name }}</td>
                                        <td>{{ optional($c->party)->name }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                                <a href="#" class="btn btn-outline-danger"><i class="fas fa-trash"></i></a>
                                            </div>
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
</div>
@endsection