@extends('layouts.app')

@section('title', 'Elections Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-vote-yea text-primary me-2"></i>Elections Management
        </h1>
        <p class="text-muted mb-0">Create and manage voting elections</p>
    </div>
    <div>
        <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Election
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>All Elections</h5>
    </div>
    <div class="card-body">
        @if($elections->count() === 0)
            <div class="text-center py-5">
                <i class="fas fa-vote-yea fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Elections Found</h5>
                <p class="text-muted mb-4">Get started by creating your first election</p>
                <a href="{{ route('admin.elections.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Election
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Turnout</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($elections as $e)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.elections.show', $e) }}" class="fw-semibold">{{ $e->title }}</a>
                                    <div class="text-muted small">{{ Str::limit($e->description, 60) }}</div>
                                </td>
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
                                <td>{{ optional($e->start_date)->format('M d, Y H:i') ?? '—' }}</td>
                                <td>{{ optional($e->end_date)->format('M d, Y H:i') ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $e->total_votes_cast }} / {{ $e->total_registered_voters }}</span>
                                    <small class="text-muted d-block">{{ number_format($e->voting_percentage, 2) }}%</small>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.elections.show', $e) }}" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.elections.edit', $e) }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.elections.destroy', $e) }}" method="POST" onsubmit="return confirm('Delete this election? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $elections->links() }}
            </div>
        @endif
    </div>
</div>
@endsection