<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Election;
use App\Models\Party;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function store(Request $request, Election $election): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'party_id' => 'nullable|exists:parties,id',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
            'photo_path' => 'nullable|string|max:255',
        ]);

        // Ensure selected position and party (if provided) belong to this election
        abort_unless(Position::where('id', $data['position_id'])->where('election_id', $election->id)->exists(), 404);
        if (! empty($data['party_id'])) {
            abort_unless(Party::where('id', $data['party_id'])->where('election_id', $election->id)->exists(), 404);
        }

        $data['election_id'] = $election->id;
        Candidate::create($data);

        return back()->with('success', 'Candidate added.');
    }

    public function update(Request $request, Election $election, Candidate $candidate): RedirectResponse
    {
        $this->authorizeCandidate($election, $candidate);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'party_id' => 'nullable|exists:parties,id',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
            'photo_path' => 'nullable|string|max:255',
        ]);

        // Ensure selected position and party (if provided) belong to this election
        abort_unless(Position::where('id', $data['position_id'])->where('election_id', $election->id)->exists(), 404);
        if (! empty($data['party_id'])) {
            abort_unless(Party::where('id', $data['party_id'])->where('election_id', $election->id)->exists(), 404);
        }

        $candidate->update($data);

        return back()->with('success', 'Candidate updated.');
    }

    public function destroy(Election $election, Candidate $candidate): RedirectResponse
    {
        $this->authorizeCandidate($election, $candidate);

        $candidate->delete();

        return back()->with('success', 'Candidate deleted.');
    }

    protected function authorizeCandidate(Election $election, Candidate $candidate): void
    {
        abort_unless($candidate->election_id === $election->id, 404);
    }
}
