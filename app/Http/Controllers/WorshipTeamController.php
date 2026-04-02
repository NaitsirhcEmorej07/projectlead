<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->get();

        return view('worship-team', compact('users', 'search'));
    }
}
