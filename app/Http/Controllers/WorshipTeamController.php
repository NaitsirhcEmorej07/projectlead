<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class WorshipTeamController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $search = $request->search;

        $currentChurchId = session('church_id');

        if (!$currentChurchId) {
            return redirect()->route('select-church');
        }

        $users = User::with('roles')
            ->whereHas('churches', function ($query) use ($currentChurchId) {
                $query->where('church_id', $currentChurchId)
                    ->where('church_user.is_approved', true)
                    ->where('church_user.type', 'member'); // 👈 fixed
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByRaw('LOWER(name)')
            // ->orderBy('name', 'asc')
            ->get();

        return view('worship-team', compact('users', 'search'));
    }

    public function view($id)
    {
        $currentChurchId = session('church_id');

        if (!$currentChurchId) {
            return redirect()->route('select-church');
        }

        $user = User::with([
            'roles',
            'songs',
            'socialLinks'
        ])
            ->whereHas('churches', function ($query) use ($currentChurchId) {
                $query->where('church_id', $currentChurchId)
                    ->where('church_user.is_approved', true)
                    ->where('church_user.type', 'member');
            })
            ->findOrFail($id);

        return view('worship-team-view', compact('user'));
    }

    public function togglePublicView()
    {
        $user = Auth::user();

        // generate secure random link if wala pa
        if (!$user->public_link) {
            $user->public_link = Str::random(40); // 🔥 long secure token
        }

        $user->is_public = 1;
        $user->save();

        return response()->json([
            'status' => 'enabled',
            'link' => route('worship.team.public', ['link' => $user->public_link])
        ]);
    }

    public function togglePublicUnview()
    {
        $user = Auth::user();

        $user->is_public = 0;
        $user->save();

        return response()->json([
            'status' => 'disabled'
        ]);
    }

    public function publicView($link)
    {
        $user = User::with(['roles', 'songs', 'socialLinks'])
            ->where('public_link', $link)
            ->where('is_public', 1)
            ->firstOrFail();

        return view('worship-team-view-public', compact('user'));
    }
}
