<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SongSelect;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{
    // 📌 LIST
    public function index(Request $request)
    {
        $user = Auth::user();
        $church = $user->churches()->first();

        $query = SongSelect::where('church_id', $church->id);

        // 🔍 SEARCH (including original_key)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('song_title', 'like', "%{$search}%")
                    ->orWhere('song_by', 'like', "%{$search}%")
                    ->orWhere('original_key', 'like', "%{$search}%"); // 👈 added
            });
        }

        $songs = $query->latest()->get();

        // 🔥 for suggestions (include key optional)
        $allSongs = SongSelect::where('church_id', $church->id)
            ->get(['id', 'song_title', 'song_by', 'original_key']); // 👈 added

        return view('songs.index', compact('songs', 'allSongs'));
    }

    // 📌 STORE
    public function store(Request $request)
    {
        $request->validate([
            'song_title' => 'required|string|max:255',
            'song_by' => 'nullable|string|max:255',
            'song_reference' => 'nullable|url',
            'original_key' => 'nullable|string|max:10', // 👈 added
        ]);

        $user = Auth::user();
        $church = $user->churches()->first();

        SongSelect::create([
            'church_id' => $church->id,
            'song_title' => $request->song_title,
            'song_by' => $request->song_by,
            'song_reference' => $request->song_reference,
            'original_key' => $request->original_key, // 👈 added
        ]);

        return redirect()->back()->with('success', 'Song added successfully');
    }

    // 📌 UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'song_title' => 'required|string|max:255',
            'song_by' => 'nullable|string|max:255',
            'song_reference' => 'nullable|url',
            'original_key' => 'nullable|string|max:10', // 👈 added
        ]);

        $song = SongSelect::findOrFail($id);

        $song->update([
            'song_title' => $request->song_title,
            'song_by' => $request->song_by,
            'song_reference' => $request->song_reference,
            'original_key' => $request->original_key, // 👈 added
        ]);

        return redirect()->back()->with('success', 'Song updated');
    }

    // 📌 DELETE
    public function destroy($id)
    {
        $song = SongSelect::findOrFail($id);
        $song->delete();

        return redirect()->back()->with('success', 'Song deleted');
    }
}
