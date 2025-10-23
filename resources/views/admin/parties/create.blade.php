@extends('layouts.app')

@section('title', 'Create Party')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="h4 mb-0"><i class="fas fa-flag text-primary me-2"></i>Create Party</h1>
  <div>
    <a href="{{ route('admin.parties.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
  </div>
</div>

@php
  $elections = \App\Models\Election::orderByDesc('created_at')->get();
  $selectedElection = request('election_id');
@endphp

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.parties.store', ['election' => old('election_id', $selectedElection)]) }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Election</label>
          <select name="election_id" class="form-select" required>
            <option value="">Select election</option>
            @foreach($elections as $e)
              <option value="{{ $e->id }}" {{ (string)old('election_id', $selectedElection) === (string)$e->id ? 'selected' : '' }}>{{ $e->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Color</label>
          <input type="text" name="color" class="form-control" placeholder="#0d6efd">
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
      </div>
      <div class="mt-3 text-end">
        <button class="btn btn-primary"><i class="fas fa-save me-1"></i>Save</button>
      </div>
    </form>
  </div>
</div>
@endsection