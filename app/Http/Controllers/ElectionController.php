<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::orderByDesc('created_at')->paginate(10);
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
            'allow_abstain' => 'sometimes|boolean',
            'show_live_results' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['allow_abstain'] = $request->boolean('allow_abstain');
        $data['show_live_results'] = $request->boolean('show_live_results');
        $data['status'] = $data['is_active'] ? 'active' : 'draft';
        $data['total_registered_voters'] = User::students()->count();
        $data['total_votes_cast'] = 0;
        $data['voting_percentage'] = 0.00;

        $election = Election::create($data);

        return redirect()->route('admin.elections.show', $election)->with('success', 'Election created successfully.');
    }

    public function show(Election $election)
    {
        $election->load(['positions', 'parties', 'candidates']);
        return view('admin.elections.show', compact('election'));
    }

    public function edit(Election $election)
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'sometimes|boolean',
            'status' => 'required|in:draft,active,completed,cancelled',
            'allow_abstain' => 'sometimes|boolean',
            'show_live_results' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['allow_abstain'] = $request->boolean('allow_abstain');
        $data['show_live_results'] = $request->boolean('show_live_results');

        // Recompute analytics if requested
        if ($request->boolean('recompute_stats')) {
            $data['total_registered_voters'] = User::students()->count();
            $data['voting_percentage'] = $election->total_registered_voters > 0
                ? round(($election->total_votes_cast / $election->total_registered_voters) * 100, 2)
                : 0.00;
        }

        $election->update($data);

        return redirect()->route('admin.elections.show', $election)->with('success', 'Election updated successfully.');
    }

    public function destroy(Election $election)
    {
        $election->delete();
        return redirect()->route('admin.elections.index')->with('success', 'Election deleted.');
    }

    public function toggle(Election $election)
    {
        $election->is_active = ! $election->is_active;
        $election->status = $election->is_active ? 'active' : 'draft';
        $election->save();

        return back()->with('success', 'Election status updated.');
    }

    public function publishResults(Election $election)
    {
        $election->update([
            'results_published' => true,
            'results_published_at' => now(),
        ]);

        return back()->with('success', 'Results published.');
    }

    public function resetVotes(Election $election)
    {
        DB::transaction(function () use ($election) {
            // Clear votes for this election
            $election->votes()->delete();

            // Reset analytics and user flags
            $election->update([
                'total_votes_cast' => 0,
                'voting_percentage' => 0.00,
            ]);

            // Mark all users as not voted
            \App\Models\User::query()->update(['has_voted' => false]);
        });

        return back()->with('success', 'All votes reset for this election and users marked as not voted.');
    }
}
