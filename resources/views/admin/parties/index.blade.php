@extends('layouts.app')

@section('title', 'Parties')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h4 mb-0"><i class="fas fa-people-group text-primary me-2"></i>Parties</h1>
  <div>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
  </div>
</div>

@php
  $elections = \App\Models\Election::orderByDesc('created_at')->get();
  $selectedElection = request('election_id');
  $q = request('q');

  $parties = \App\Models\Party::with(['election'])
    ->withCount('candidates')
    ->when($selectedElection, fn($qry) => $qry->where('election_id', $selectedElection))
    ->when($q, fn($qry) => $qry->where(function($sub){
      $sub->where('name', 'like', '%'.request('q').'%')
          ->orWhere('slug', 'like', '%'.request('q').'%');
    }))
    ->orderBy('name')
    ->paginate(10)
    ->withQueryString();
@endphp

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2">
      <div class="col-md-4">
        <select name="election_id" class="form-select">
          <option value="">All Elections</option>
          @foreach($elections as $e)
            <option value="{{ $e->id }}" {{ (string)$selectedElection === (string)$e->id ? 'selected' : '' }}>{{ $e->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search party name or slug">
      </div>
      <div class="col-md-4 text-end">
        <button class="btn btn-outline-secondary"><i class="fas fa-search me-1"></i>Filter</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPartyModal"><i class="fas fa-plus me-1"></i>Add Party</button>
      </div>
    </form>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    @if($parties->isEmpty())
      <div class="text-center text-muted py-4">No parties found.</div>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Election</th>
              <th>Color</th>
              <th>Description</th>
              <th class="text-center">Candidates</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($parties as $party)
              <tr>
                <td class="fw-semibold">{{ $party->name }}</td>
                <td class="text-muted small">{{ optional($party->election)->title }}</td>
                <td><span class="badge" style="background-color: {{ $party->color ?? '#e9ecef' }}">{{ $party->color ?? '—' }}</span></td>
                <td class="text-muted small">{{ \Illuminate\Support\Str::limit($party->description, 80) }}</td>
                <td class="text-center">{{ $party->candidates_count }}</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPartyModal-{{ $party->id }}"><i class="fas fa-edit"></i></button>
                  <form action="{{ url('/admin/elections/'.$party->election_id.'/parties/'.$party->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this party?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>

              <div class="modal fade" id="editPartyModal-{{ $party->id }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="POST" action="{{ url('/admin/elections/'.$party->election_id.'/parties/'.$party->id) }}">
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
                          <label class="form-label">Color</label>
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
          </tbody>
        </table>
      </div>
      <div>
        {{ $parties->links() }}
      </div>
    @endif
  </div>
</div>

<div class="modal fade" id="addPartyModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addPartyForm" method="POST" action="#">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Party</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Election</label>
            <select name="election_id" id="partyElectionSelect" class="form-select" required>
              <option value="">Select election</option>
              @foreach($elections as $e)
                <option value="{{ $e->id }}" {{ (string)$selectedElection === (string)$e->id ? 'selected' : '' }}>{{ $e->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Color</label>
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

<script>
  document.addEventListener('DOMContentLoaded', function(){
    const select = document.getElementById('partyElectionSelect');
    const form = document.getElementById('addPartyForm');
    const base = '{{ url('/admin/elections') }}';
    function updateAction(){
      const id = select.value;
      form.action = id ? `${base}/${id}/parties` : '#';
    }
    select.addEventListener('change', updateAction);
    updateAction();
  });
</script>
@endsection