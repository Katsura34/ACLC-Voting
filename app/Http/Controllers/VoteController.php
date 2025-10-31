<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();

        // Load the active election - be more flexible with the query
        $election = Election::with(['positions' => function ($q) {
            $q->orderBy('order');
        }, 'positions.candidates.party'])
            ->where('is_active', true)
            ->first();

        // If no active election, try to get the latest one regardless of status for debugging
        if (! $election) {
            $election = Election::with(['positions' => function ($q) {
                $q->orderBy('order');
            }, 'positions.candidates.party'])
                ->latest('created_at')
                ->first();
        }

        return view('student.vote', [
            'user' => $user,
            'election' => $election,
            'debug' => [
                'elections_count' => Election::count(),
                'active_elections_count' => Election::where('is_active', true)->count(),
                'user_voted' => $user->has_voted,
            ],
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $user = auth()->user();

        // Find active election
        $election = Election::with('positions')
            ->where('is_active', true)
            ->first();

        if (! $election) {
            return redirect()->route('student.dashboard')->with('error', 'No active election found.');
        }

        if ($user->has_voted) {
            return redirect()->route('student.dashboard')->with('error', 'You have already cast your vote.');
        }

        // Expect payload: selections[position_id] = [candidate_id, ...] (array to support max_winners > 1)
        $selections = $request->input('selections', []);

        // Validate per-position selection counts and candidate ownership
        foreach ($election->positions as $position) {
            $selected = collect($selections[$position->id] ?? [])->filter(fn ($v) => ! empty($v))->values();

            if ($selected->isEmpty()) {
                // allow abstain per position by submitting empty selection
                continue;
            }

            if ($selected->count() > $position->max_winners) {
                return back()->with('error', "Too many selections for {$position->name}. Maximum is {$position->max_winners}.")->withInput();
            }

            // Ensure all selected candidates belong to this position and election
            $validCount = Candidate::whereIn('id', $selected)
                ->where('position_id', $position->id)
                ->where('election_id', $election->id)
                ->count();

            if ($validCount !== $selected->count()) {
                return back()->with('error', 'Invalid candidate selection detected.')->withInput();
            }
        }

        DB::transaction(function () use ($election, $user, $selections) {
            // Store votes per position
            foreach ($election->positions as $position) {
                $selected = collect($selections[$position->id] ?? [])->filter(fn ($v) => ! empty($v))->values();
                if ($selected->isEmpty()) {
                    // Record abstain for this position
                    Vote::create([
                        'election_id' => $election->id,
                        'user_id' => $user->id,
                        'position_id' => $position->id,
                        'candidate_id' => null,
                        'is_abstain' => true,
                        'voted_at' => now(),
                    ]);

                    continue;
                }

                foreach ($selected as $candidateId) {
                    Vote::create([
                        'election_id' => $election->id,
                        'user_id' => $user->id,
                        'position_id' => $position->id,
                        'candidate_id' => $candidateId,
                        'is_abstain' => false,
                        'voted_at' => now(),
                    ]);
                }
            }

            // Mark user as voted
            $user->forceFill(['has_voted' => true])->save();

            // Recompute analytics
            $this->recomputeAnalytics($election);
        });

        return redirect()->route('student.dashboard')->with('success', 'Your vote has been recorded. Thank you for voting!');
    }

    protected function recomputeAnalytics(Election $election): void
    {
        $totalRegistered = \App\Models\User::students()->count();
        $totalVoted = Vote::where('election_id', $election->id)
            ->select('user_id')
            ->distinct()
            ->count('user_id');

        $election->update([
            'total_registered_voters' => $totalRegistered,
            'total_votes_cast' => $totalVoted,
            'voting_percentage' => $totalRegistered > 0 ? round(($totalVoted / $totalRegistered) * 100, 2) : 0.00,
        ]);
    }
}
