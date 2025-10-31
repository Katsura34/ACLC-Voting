<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartyController extends Controller
{
    public function store(Request $request, Election $election)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['election_id'] = $election->id;

        Party::create($data);

        return back()->with('success', 'Party added.');
    }

    public function update(Request $request, Election $election, Party $party)
    {
        $this->authorizeParty($election, $party);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);

        $party->update($data);

        return back()->with('success', 'Party updated.');
    }

    public function destroy(Election $election, Party $party)
    {
        $this->authorizeParty($election, $party);

        $party->delete();

        return back()->with('success', 'Party deleted.');
    }

    protected function authorizeParty(Election $election, Party $party): void
    {
        abort_unless($party->election_id === $election->id, 404);
    }
}
