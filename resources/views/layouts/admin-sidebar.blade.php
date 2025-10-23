<div class="d-flex">
    <!-- Admin Sidebar -->
    <div class="sidebar bg-dark" style="width: 250px;">
        <div class="p-3">
            <h6 class="text-white-50 text-uppercase mb-3">Admin Panel</h6>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('admin.elections.*') ? 'active' : '' }}" href="{{ route('admin.elections.index') }}">
                    <i class="fas fa-vote-yea me-2"></i>Elections
                </a>
                <a class="nav-link {{ request()->routeIs('admin.parties.*') ? 'active' : '' }}" href="{{ route('admin.parties.index') }}">
                    <i class="fas fa-users me-2"></i>Parties
                </a>
                <a class="nav-link {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}" href="{{ route('admin.candidates.index') }}">
                    <i class="fas fa-user-tie me-2"></i>Candidates
                </a>
                
                <hr class="my-3" style="border-color: #495057;">
                
                <a class="nav-link" href="{{ route('register') }}">
                    <i class="fas fa-user-plus me-2"></i>Register User
                </a>
                
                <a class="nav-link text-warning" href="#" onclick="resetVotes()">
                    <i class="fas fa-redo me-2"></i>Reset All Votes
                </a>
            </nav>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <!-- Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </main>
</div>

<script>
function resetVotes() {
    if (confirm('Are you sure you want to reset ALL user votes? This action cannot be undone.')) {
        // Add reset votes functionality here
        alert('Reset votes functionality will be implemented');
    }
}
</script>