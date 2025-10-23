@extends('layouts.app')

@section('title', 'Register User')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-user-plus me-2 text-primary"></i>Register New User
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
            </a>
        </div>
        
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>User Registration Form
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    
                    <!-- USN Field -->
                    <div class="mb-3">
                        <label for="usn" class="form-label">
                            <i class="fas fa-id-badge me-2"></i>University Student Number (USN) *
                        </label>
                        <input type="text" 
                               class="form-control @error('usn') is-invalid @enderror" 
                               id="usn" 
                               name="usn" 
                               value="{{ old('usn') }}" 
                               required 
                               autofocus 
                               placeholder="e.g., 2021-001234">
                        @error('usn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="form-text">
                            Must be unique. This will be used for login.
                        </div>
                    </div>
                    
                    <!-- User Type -->
                    <div class="mb-3">
                        <label for="user_type" class="form-label">
                            <i class="fas fa-user-tag me-2"></i>User Type *
                        </label>
                        <select class="form-select @error('user_type') is-invalid @enderror" 
                                id="user_type" 
                                name="user_type" 
                                required>
                            <option value="">Select User Type</option>
                            <option value="student" {{ old('user_type') === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="admin" {{ old('user_type') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password *
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   minlength="6"
                                   placeholder="Minimum 6 characters">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="passwordToggle1"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Confirm Password *
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required 
                                   minlength="6"
                                   placeholder="Re-enter password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="passwordToggle2"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Register User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="mt-3">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>Registration Instructions
                </h6>
                <ul class="mb-0">
                    <li><strong>USN:</strong> Must be unique across all users</li>
                    <li><strong>Password:</strong> Minimum 6 characters required</li>
                    <li><strong>User Types:</strong>
                        <ul>
                            <li><strong>Student:</strong> Can vote in elections</li>
                            <li><strong>Admin:</strong> Can manage elections, parties, and candidates</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(fieldId === 'password' ? 'passwordToggle1' : 'passwordToggle2');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection