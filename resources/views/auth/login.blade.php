@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">
                    <i class="fas fa-vote-yea me-2"></i>ACLC Voting System
                </h4>
                <small>Student Login Portal</small>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    
                    <!-- USN Field -->
                    <div class="mb-3">
                        <label for="usn" class="form-label">
                            <i class="fas fa-user me-2"></i>University Student Number (USN)
                        </label>
                        <input type="text" 
                               class="form-control @error('usn') is-invalid @enderror" 
                               id="usn" 
                               name="usn" 
                               value="{{ old('usn') }}" 
                               required 
                               autofocus 
                               placeholder="Enter your USN">
                        @error('usn')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required 
                                   placeholder="Enter your password">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                <i class="fas fa-eye" id="passwordToggle"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center text-muted">
                <small>
                    <i class="fas fa-shield-alt me-1"></i>
                    Secure Login • ACLC Voting System
                </small>
            </div>
        </div>
        
        <!-- Instructions -->
        <div class="mt-3 text-center">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Instructions:</strong><br>
                Use your University Student Number (USN) and password to login.<br>
                Contact your administrator if you need assistance.
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggle.classList.remove('fa-eye');
        passwordToggle.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        passwordToggle.classList.remove('fa-eye-slash');
        passwordToggle.classList.add('fa-eye');
    }
}
</script>
@endsection