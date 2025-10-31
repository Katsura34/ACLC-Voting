<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function store(Request $request, Election $election): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'max_winners' => 'required|integer|min:1|max:25',
            'order' => 'nullable|integer|min:0',
        ]);
        $data['order'] = $data['order'] ?? 0;
        $data['election_id'] = $election->id;

        Position::create($data);

        return back()->with('success', 'Position added.');
    }

    public function update(Request $request, Election $election, Position $position): RedirectResponse
    {
        $this->authorizePosition($election, $position);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'max_winners' => 'required|integer|min:1|max:25',
            'order' => 'nullable|integer|min:0',
        ]);

        $position->update($data);

        return back()->with('success', 'Position updated.');
    }

    public function destroy(Election $election, Position $position): RedirectResponse
    {
        $this->authorizePosition($election, $position);

        $position->delete();

        return back()->with('success', 'Position deleted.');
    }

    protected function authorizePosition(Election $election, Position $position): void
    {
        abort_unless($position->election_id === $election->id, 404);
    }
}
