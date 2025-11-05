<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Party;
use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalStudents = User::students()->count();
        $totalElections = Election::count();
        $totalParties = Party::count();
        $totalCandidates = Candidate::count();

        $activeElection = Election::with(['positions', 'candidates'])
            ->where('is_active', true)
            ->latest('start_date')
            ->first();

        $recentElections = Election::orderByDesc('created_at')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalElections',
            'totalParties',
            'totalCandidates',
            'activeElection',
            'recentElections'
        ));
    }
}
