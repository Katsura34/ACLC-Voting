@extends('layouts.app')

@section('title', 'Cast Your Vote')

@section('content')
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

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@php
  $user = auth()->user();
  $election = \App\Models\Election::with(['positions' => function($q){ $q->orderBy('order'); }, 'positions.candidates.party'])
      ->where('is_active', true)
      ->first();
@endphp

@if($user->has_voted)
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
@elseif(!$election)
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
@else
  <form id="voteForm" method="POST" action="{{ route('student.vote.submit') }}">
    @csrf

    <div class="card border-0 shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
          <i class="fas fa-info-circle me-2"></i>{{ $election->title }}
        </h5>
      </div>
      <div class="card-body">
        <p class="mb-2">{{ $election->description }}</p>
        <div class="row text-center">
          <div class="col-md-4">
            <small class="text-muted">
              <i class="fas fa-calendar me-1"></i>
              @if($election->start_date) Starts {{ $election->start_date->format('M d, Y H:i') }} • @endif
              @if($election->end_date) Ends {{ $election->end_date->format('M d, Y H:i') }} @endif
            </small>
          </div>
          <div class="col-md-4">
            <small class="text-muted">
              <i class="fas fa-users me-1"></i>Registered Voters: {{ $election->total_registered_voters ?? 0 }}
            </small>
          </div>
          <div class="col-md-4">
            <small class="text-muted">
              <i class="fas fa-chart-bar me-1"></i>Votes Cast: {{ $election->total_votes_cast ?? 0 }}
            </small>
          </div>
        </div>
      </div>
    </div>

    @forelse($election->positions as $position)
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">
            @if($loop->index === 0)
              <i class="fas fa-crown text-warning me-2"></i>
            @else
              <i class="fas fa-user-tie text-info me-2"></i>
            @endif
            {{ $position->name }}
            <small class="text-muted ms-2">(Choose {{ $position->max_winners }})</small>
          </h5>
        </div>
        <div class="card-body">
          @if($position->candidates->isEmpty())
            <div class="col-12 text-center text-muted py-3">
              <i class="fas fa-users fa-2x mb-2"></i>
              <p>No candidates for this position.</p>
            </div>
          @else
            <div class="row">
              @foreach($position->candidates as $cand)
                <div class="col-md-6 col-lg-4 mb-3">
                  <div class="card border h-100 candidate-card">
                    <div class="card-body text-center">
                      @if($position->max_winners > 1)
                        <input type="checkbox"
                               class="form-check-input d-none position-{{ $position->id }}"
                               name="selections[{{ $position->id }}][]"
                               value="{{ $cand->id }}"
                               data-max="{{ $position->max_winners }}">
                      @else
                        <input type="radio"
                               class="form-check-input d-none position-{{ $position->id }}"
                               name="selections[{{ $position->id }}][]"
                               value="{{ $cand->id }}"
                               data-max="1">
                      @endif
                      <label class="w-100 cursor-pointer">
                        <img src="{{ $cand->photo_path ?? 'https://via.placeholder.com/80x80' }}" class="rounded-circle mb-3" alt="Candidate Photo" width="80" height="80">
                        <h6 class="fw-bold">{{ $cand->first_name }} {{ $cand->last_name }}</h6>
                        <p class="text-primary mb-1">{{ optional($cand->party)->name ?? 'Independent' }}</p>
                        <p class="text-muted small">
                          {{ $cand->course }}{{ $cand->year_level ? ' • '.$cand->year_level : '' }}
                        </p>
                        @if($cand->bio)
                          <p class="small">{{ \Illuminate\Support\Str::limit($cand->bio, 120) }}</p>
                        @endif
                      </label>
                    </div>
                    <div class="card-footer bg-white">
                      <div class="form-check d-inline-block">
                        @if($position->max_winners > 1)
                          <input class="form-check-input position-{{ $position->id }}"
                                 type="checkbox"
                                 name="selections[{{ $position->id }}][]"
                                 value="{{ $cand->id }}"
                                 data-max="{{ $position->max_winners }}">
                          <label class="form-check-label">Select</label>
                        @else
                          <input class="form-check-input position-{{ $position->id }}"
                                 type="radio"
                                 name="selections[{{ $position->id }}][]"
                                 value="{{ $cand->id }}"
                                 data-max="1">
                          <label class="form-check-label">Select</label>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif

          @if($election->allow_abstain)
            <div class="form-check mt-1">
              <input class="form-check-input abstain-toggle" type="checkbox" data-position="{{ $position->id }}">
              <label class="form-check-label text-muted small">Abstain for {{ $position->name }}</label>
            </div>
          @endif
        </div>
      </div>
    @empty
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted">
          No positions configured.
        </div>
      </div>
    @endforelse

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
            <button type="button" class="btn btn-outline-secondary me-2" id="reviewBtn">
              <i class="fas fa-eye me-2"></i>Review Votes
            </button>
            <button type="button" class="btn btn-success" id="openConfirm" data-bs-toggle="modal" data-bs-target="#confirmVoteModal" style="display:none;">
              <i class="fas fa-check me-2"></i>Open Confirm
            </button>
            <button type="button" class="btn btn-success" id="submitVote" disabled>
              <i class="fas fa-check me-2"></i>Submit Vote
            </button>
          </div>
        </div>
      </div>
    </div>

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
            <div id="vote-summary"></div>
            <div class="alert alert-warning mt-3">
              <strong>Warning:</strong> Once submitted, your vote cannot be changed.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="fas fa-edit me-2"></i>Edit Votes
            </button>
            <button type="button" class="btn btn-success" id="confirmSubmitBtn">
              <i class="fas fa-check me-2"></i>Confirm & Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <style>
    .candidate-card { cursor: pointer; transition: all 0.3s ease; }
    .candidate-card:hover { border-color: #0d6efd !important; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .cursor-pointer { cursor: pointer; }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Enforce max per position for checkboxes and radios
      document.querySelectorAll('[data-max]').forEach(function(input){
        input.addEventListener('change', function(e){
          const match = e.target.name.match(/selections\[(\d+)\]/);
          if (!match) return;
          const cls = '.position-' + match[1];
          const max = parseInt(e.target.getAttribute('data-max'));
          const inputs = document.querySelectorAll(cls);
          const checked = Array.from(inputs).filter(i => i.checked);
          if (e.target.type === 'checkbox' && checked.length > max) {
            e.target.checked = false;
            alert('You can select up to ' + max + ' candidate(s) for this position.');
          }
          updateSubmitAvailability();
        });
      });

      // Abstain toggles
      document.querySelectorAll('.abstain-toggle').forEach(function(toggle){
        toggle.addEventListener('change', function(e){
          const posId = e.target.getAttribute('data-position');
          const inputs = document.querySelectorAll('.position-' + posId);
          inputs.forEach(i => { i.checked = false; i.disabled = e.target.checked; });
          updateSubmitAvailability();
        });
      });

      // Review button builds summary then opens modal
      document.getElementById('reviewBtn')?.addEventListener('click', function(){
        const summary = document.getElementById('vote-summary');
        summary.innerHTML = '';
        @foreach($election->positions as $position)
          (function(){
            const posId = {{ $position->id }};
            const title = @json($position->name);
            const abstain = document.querySelector('.abstain-toggle[data-position="' + posId + '"]');
            const picks = Array.from(document.querySelectorAll('.position-' + posId + ':checked')).map(i => i.value);
            const wrapper = document.createElement('div');
            wrapper.className = 'mb-3';
            const h = document.createElement('h6');
            h.textContent = title;
            wrapper.appendChild(h);
            if (abstain && abstain.checked) {
              const p = document.createElement('p');
              p.className = 'text-muted';
              p.textContent = 'Abstain';
              wrapper.appendChild(p);
            } else if (picks.length === 0) {
              const p = document.createElement('p');
              p.className = 'text-danger';
              p.textContent = 'No selection';
              wrapper.appendChild(p);
            } else {
              const ul = document.createElement('ul');
              @foreach($position->candidates as $cand)
                (function(){
                  const id = '{{ $cand->id }}';
                  if (picks.includes(id)) {
                    const li = document.createElement('li');
                    li.textContent = '{{ $cand->first_name }} {{ $cand->last_name }}' + ' ({{ optional($cand->party)->name ?? 'Independent' }})';
                    ul.appendChild(li);
                  }
                })();
              @endforeach
              wrapper.appendChild(ul);
            }
            summary.appendChild(wrapper);
          })();
        @endforeach
        document.getElementById('openConfirm').click();
      });

      // Submit from modal
      document.getElementById('confirmSubmitBtn')?.addEventListener('click', function(){
        document.getElementById('voteForm').submit();
      });

      function updateSubmitAvailability(){
        let ok = true;
        @foreach($election->positions as $position)
          (function(){
            const posId = {{ $position->id }};
            const abstain = document.querySelector('.abstain-toggle[data-position="' + posId + '"]');
            const picks = Array.from(document.querySelectorAll('.position-' + posId + ':checked'));
            if ((abstain && abstain.checked) || picks.length > 0) {
              // ok for this position
            } else {
              ok = false;
            }
          })();
        @endforeach
        const submitBtn = document.getElementById('submitVote');
        if (submitBtn) submitBtn.disabled = !ok;
      }

      updateSubmitAvailability();
    });
  </script>
@endif
@endsection