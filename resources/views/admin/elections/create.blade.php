@extends('layouts.app')

@section('title', 'Create Election')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0"><i class="fas fa-plus text-primary me-2"></i>Create Election</h1>
    <a href="{{ route('admin.elections.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.elections.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Describe this election">{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                        @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                        @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <!-- Hidden inputs ensure unchecked checkboxes submit as 0 for boolean validation -->
                    <input type="hidden" name="is_active" value="0">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Set as Active</label>
                    </div>

                    <input type="hidden" name="allow_abstain" value="0">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="allow_abstain" id="allow_abstain" value="1" {{ old('allow_abstain') ? 'checked' : '' }}>
                        <label class="form-check-label" for="allow_abstain">Allow Abstain</label>
                    </div>

                    <input type="hidden" name="show_live_results" value="0">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="show_live_results" id="show_live_results" value="1" {{ old('show_live_results') ? 'checked' : '' }}>
                        <label class="form-check-label" for="show_live_results">Show Live Results</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Create Election
                </button>
            </div>
        </form>
    </div>
</div>
@endsection