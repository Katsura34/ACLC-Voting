<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    public function index(): View
    {
        $elections = Election::orderByDesc('created_at')->paginate(10);

        return view('admin.elections.index', compact('elections'));
    }

    public function create(): View
    {
        return view('admin.elections.create');
    }

    public function store(Request $request): RedirectResponse
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

        // Coerce checkbox inputs to real booleans regardless of raw values
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

    public function show(Election $election): View
    {
        $election->load(['positions', 'parties', 'candidates']);

        return view('admin.elections.show', compact('election'));
    }

    public function edit(Election $election): View
    {
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election): RedirectResponse
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

        // Coerce checkbox inputs to real booleans regardless of raw values
        $data['is_active'] = $request->boolean('is_active');
        $data['allow_abstain'] = $request->boolean('allow_abstain');
        $data['show_live_results'] = $request->boolean('show_live_results');

        // Keep status consistent with is_active if needed
        if ($data['is_active'] && $data['status'] === 'draft') {
            $data['status'] = 'active';
        }
        if (! $data['is_active'] && $data['status'] === 'active') {
            $data['status'] = 'draft';
        }

        if ($request->boolean('recompute_stats')) {
            $data['total_registered_voters'] = User::students()->count();
            $data['voting_percentage'] = $election->total_registered_voters > 0
                ? round(($election->total_votes_cast / $election->total_registered_voters) * 100, 2)
                : 0.00;
        }

        $election->update($data);

        return redirect()->route('admin.elections.show', $election)->with('success', 'Election updated successfully.');
    }

    public function destroy(Election $election): RedirectResponse
    {
        $election->delete();

        return redirect()->route('admin.elections.index')->with('success', 'Election deleted.');
    }

    public function toggle(Election $election): RedirectResponse
    {
        $election->is_active = ! $election->is_active;
        $election->status = $election->is_active ? 'active' : 'draft';
        $election->save();

        return back()->with('success', 'Election status updated.');
    }

    public function publishResults(Election $election): RedirectResponse
    {
        $election->update([
            'results_published' => true,
            'results_published_at' => now(),
        ]);

        return back()->with('success', 'Results published.');
    }

    public function resetVotes(Election $election): RedirectResponse
    {
        DB::transaction(function () use ($election) {
            $election->votes()->delete();
            $election->update([
                'total_votes_cast' => 0,
                'voting_percentage' => 0.00,
            ]);
            \App\Models\User::query()->update(['has_voted' => false]);
        });

        return back()->with('success', 'All votes reset for this election and users marked as not voted.');
    }
}
