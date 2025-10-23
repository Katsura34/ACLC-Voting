<div class="d-flex">
    <!-- Student Sidebar -->
    <div class="sidebar bg-dark" style="width: 250px;">
        <div class="p-3">
            <h6 class="text-white-50 text-uppercase mb-3">Student Panel</h6>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('student.vote') ? 'active' : '' }}" href="{{ route('student.vote') }}">
                    <i class="fas fa-vote-yea me-2"></i>Cast Your Vote
                </a>
                
                <hr class="my-3" style="border-color: #495057;">
                
                <div class="nav-link text-muted" style="cursor: default;">
                    <i class="fas fa-info-circle me-2"></i>Voting Status: 
                    @if(auth()->user()->has_voted)
                        <span class="badge bg-success">Voted</span>
                    @else
                        <span class="badge bg-warning">Not Voted</span>
                    @endif
                </div>
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