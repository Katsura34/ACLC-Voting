@extends('layouts.app')

@section('title', 'Candidates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h4 mb-0"><i class="fas fa-user-tie text-primary me-2"></i>Candidates</h1>
  <div>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
  </div>
</div>

@php
  $elections = \App\Models\Election::with('positions')->orderByDesc('created_at')->get();
  $selectedElection = request('election_id');
  $selectedPosition = request('position_id');
  $q = request('q');

  $candidates = \App\Models\Candidate::with(['election','party','position'])
    ->when($selectedElection, fn($qry) => $qry->where('election_id', $selectedElection))
    ->when($selectedPosition, fn($qry) => $qry->where('position_id', $selectedPosition))
    ->when($q, fn($qry) => $qry->where(function($sub){
      $sub->where('first_name', 'like', '%'.request('q').'%')
          ->orWhere('last_name', 'like', '%'.request('q').'%');
    }))
    ->orderBy('last_name')
    ->paginate(10)
    ->withQueryString();
@endphp

<div class="card border-0 shadow-sm mb-3">
  <div class="card-body">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Election</label>
        <select name="election_id" class="form-select" onchange="this.form.submit()">
          <option value="">All</option>
          @foreach($elections as $e)
            <option value="{{ $e->id }}" {{ (string)$selectedElection === (string)$e->id ? 'selected' : '' }}>{{ $e->title }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Position</label>
        <select name="position_id" class="form-select">
          <option value="">All</option>
          @if($selectedElection)
            @php $positions = optional($elections->firstWhere('id', (int)$selectedElection))->positions ?? collect(); @endphp
            @foreach($positions as $p)
              <option value="{{ $p->id }}" {{ (string)$selectedPosition === (string)$p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
          @endif
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Search</label>
        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Name">
      </div>
      <div class="col-md-3 text-end">
        <button class="btn btn-outline-secondary"><i class="fas fa-filter me-1"></i>Filter</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCandidateModal"><i class="fas fa-plus me-1"></i>Add Candidate</button>
      </div>
    </form>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-body">
    @if($candidates->isEmpty())
      <div class="text-center text-muted py-4">No candidates found.</div>
    @else
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Election</th>
              <th>Position</th>
              <th>Party</th>
              <th>Course/Year</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($candidates as $c)
              <tr>
                <td class="fw-semibold">{{ $c->first_name }} {{ $c->last_name }}</td>
                <td class="text-muted small">{{ optional($c->election)->title }}</td>
                <td>{{ optional($c->position)->name }}</td>
                <td>{{ optional($c->party)->name ?? 'Independent' }}</td>
                <td class="text-muted small">{{ trim(($c->course ?? '').' '.($c->year_level ?? '')) }}</td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCandidateModal-{{ $c->id }}"><i class="fas fa-edit"></i></button>
                  <form action="{{ url('/admin/elections/'.$c->election_id.'/candidates/'.$c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this candidate?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </form>
                </td>
              </tr>

              <div class="modal fade" id="editCandidateModal-{{ $c->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <form method="POST" action="{{ url('/admin/elections/'.$c->election_id.'/candidates/'.$c->id) }}">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Candidate</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row g-3">
                          <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" value="{{ $c->first_name }}" class="form-control" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" value="{{ $c->last_name }}" class="form-control" required>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Position</label>
                            <select name="position_id" class="form-select" required>
                              @php $positions = optional($elections->firstWhere('id', (int)$c->election_id))->positions ?? collect(); @endphp
                              @foreach($positions as $p)
                                <option value="{{ $p->id }}" {{ $c->position_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Party (optional)</label>
                            <select name="party_id" class="form-select">
                              <option value="">Independent</option>
                              @php $partiesForElection = \App\Models\Party::where('election_id', $c->election_id)->orderBy('name')->get(); @endphp
                              @foreach($partiesForElection as $p)
                                <option value="{{ $p->id }}" {{ $c->party_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Course</label>
                            <input type="text" name="course" value="{{ $c->course }}" class="form-control">
                          </div>
                          <div class="col-md-6">
                            <label class="form-label">Year Level</label>
                            <input type="text" name="year_level" value="{{ $c->year_level }}" class="form-control">
                          </div>
                          <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-control" rows="3">{{ $c->bio }}</textarea>
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
      <div>
        {{ $candidates->links() }}
      </div>
    @endif
  </div>
</div>

<div class="modal fade" id="addCandidateModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="addCandidateForm" method="POST" action="#">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Candidate</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Election</label>
              <select name="election_id" id="candidateElectionSelect" class="form-select" required>
                <option value="">Select election</option>
                @foreach($elections as $e)
                  <option value="{{ $e->id }}" {{ (string)$selectedElection === (string)$e->id ? 'selected' : '' }}>{{ $e->title }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Position</label>
              <select name="position_id" id="candidatePositionSelect" class="form-select" required>
                @php $positionsDefault = $selectedElection ? optional($elections->firstWhere('id', (int)$selectedElection))->positions : collect(); @endphp
                @foreach($positionsDefault ?? [] as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Party (optional)</label>
              <select name="party_id" id="candidatePartySelect" class="form-select">
                <option value="">Independent</option>
                @php $partyDefault = $selectedElection ? \App\Models\Party::where('election_id', $selectedElection)->orderBy('name')->get() : collect(); @endphp
                @foreach($partyDefault as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Course</label>
              <input type="text" name="course" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Year Level</label>
              <input type="text" name="year_level" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label">Bio</label>
              <textarea name="bio" class="form-control" rows="3"></textarea>
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

<script>
  document.addEventListener('DOMContentLoaded', function(){
    const eSel = document.getElementById('candidateElectionSelect');
    const pSel = document.getElementById('candidatePositionSelect');
    const form = document.getElementById('addCandidateForm');
    const base = '{{ url('/admin/elections') }}';

    function updateAction(){
      const id = eSel.value;
      form.action = id ? `${base}/${id}/candidates` : '#';
    }

    eSel.addEventListener('change', function(){
      updateAction();
    });

    updateAction();
  });
</script>
@endsection