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
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPositionModal"><i class="fas fa-plus me-1"></i>Add Position</button>
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
                                    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPositionModal-{{ $pos->id }}"><i class="fas fa-edit"></i></button>
                                    <form action="{{ route('admin.positions.destroy', [$election, $pos]) }}" method="POST" onsubmit="return confirm('Delete this position?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </li>

                            <!-- Edit Position Modal -->
                            <div class="modal fade" id="editPositionModal-{{ $pos->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.positions.update', [$election, $pos]) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Position</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $pos->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Max Winners</label>
                                                    <input type="number" name="max_winners" class="form-control" value="{{ $pos->max_winners }}" min="1" max="25" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Order</label>
                                                    <input type="number" name="order" class="form-control" value="{{ $pos->order }}" min="0">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Parties</strong>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPartyModal"><i class="fas fa-plus me-1"></i>Add Party</button>
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
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong>{{ $party->name }}</strong>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPartyModal-{{ $party->id }}"><i class="fas fa-edit"></i></button>
                                                <form action="{{ route('admin.parties.destroy', [$election, $party]) }}" method="POST" onsubmit="return confirm('Delete this party?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small">{{ $party->description }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Party Modal -->
                            <div class="modal fade" id="editPartyModal-{{ $party->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('admin.parties.update', [$election, $party]) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Party</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $party->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Color (hex or name)</label>
                                                    <input type="text" name="color" class="form-control" value="{{ $party->color }}" placeholder="#0d6efd">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" rows="3">{{ $party->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </form>
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
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCandidateModal"><i class="fas fa-plus me-1"></i>Add Candidate</button>
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
                                        <td>{{ optional($c->party)->name ?? 'Independent' }}</td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCandidateModal-{{ $c->id }}"><i class="fas fa-edit"></i></button>
                                                <form action="{{ route('admin.candidates.destroy', [$election, $c]) }}" method="POST" onsubmit="return confirm('Delete this candidate?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Candidate Modal -->
                                    <div class="modal fade" id="editCandidateModal-{{ $c->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('admin.candidates.update', [$election, $c]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Candidate</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">First Name</label>
                                                                    <input type="text" name="first_name" class="form-control" value="{{ $c->first_name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Last Name</label>
                                                                    <input type="text" name="last_name" class="form-control" value="{{ $c->last_name }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Position</label>
                                                                    <select name="position_id" class="form-select" required>
                                                                        @foreach($election->positions as $pos)
                                                                            <option value="{{ $pos->id }}" {{ $c->position_id == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Party (optional)</label>
                                                                    <select name="party_id" class="form-select">
                                                                        <option value="">Independent</option>
                                                                        @foreach($election->parties as $party)
                                                                            <option value="{{ $party->id }}" {{ $c->party_id == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Course</label>
                                                                    <input type="text" name="course" class="form-control" value="{{ $c->course }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Year Level</label>
                                                                    <input type="text" name="year_level" class="form-control" value="{{ $c->year_level }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Bio</label>
                                                                    <textarea name="bio" class="form-control" rows="3">{{ $c->bio }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Position Modal -->
<div class="modal fade" id="addPositionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.positions.store', $election) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Position</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Winners</label>
                        <input type="number" name="max_winners" class="form-control" min="1" max="25" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control" min="0" value="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Party Modal -->
<div class="modal fade" id="addPartyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.parties.store', $election) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Party</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color (hex or name)</label>
                        <input type="text" name="color" class="form-control" placeholder="#0d6efd">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Candidate Modal -->
<div class="modal fade" id="addCandidateModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.candidates.store', $election) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Candidate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Position</label>
                                <select name="position_id" class="form-select" required>
                                    @foreach($election->positions as $pos)
                                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Party (optional)</label>
                                <select name="party_id" class="form-select">
                                    <option value="">Independent</option>
                                    @foreach($election->parties as $party)
                                        <option value="{{ $party->id }}">{{ $party->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Course</label>
                                <input type="text" name="course" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Year Level</label>
                                <input type="text" name="year_level" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Bio</label>
                                <textarea name="bio" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection