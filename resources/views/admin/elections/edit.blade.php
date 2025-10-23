@extends('layouts.app')

@section('title', 'Edit Election')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-edit text-secondary me-2"></i>Edit Election</h1>
    <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.elections.update', $election) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $election->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4">{{ old('description', $election->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', optional($election->start_date)->format('Y-m-d\TH:i')) }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', optional($election->end_date)->format('Y-m-d\TH:i')) }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            @foreach(['draft','active','completed','cancelled'] as $st)
                                <option value="{{ $st }}" {{ old('status', $election->status) === $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                            @endforeach
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" {{ old('is_active', $election->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Set as Active</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="allow_abstain" id="allow_abstain" {{ old('allow_abstain', $election->allow_abstain) ? 'checked' : '' }}>
                        <label class="form-check-label" for="allow_abstain">Allow Abstain</label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="show_live_results" id="show_live_results" {{ old('show_live_results', $election->show_live_results) ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_live_results">Show Live Results</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="recompute_stats" id="recompute_stats" value="1">
                        <label class="form-check-label" for="recompute_stats">Recompute analytics (registered voters, percentage)</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-secondary me-2">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.elections.show', $election) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection