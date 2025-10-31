<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Contracts\View\View;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $activeElection = Election::where('is_active', true)->first();

        return view('student.dashboard', compact('activeElection'));
    }
}
