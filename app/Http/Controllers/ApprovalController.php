<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    // 👉 Show pending users
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $church = $user->churches()->first();

        if (!$church) {
            return view('approval.index', ['users' => collect()]);
        }

        // 🔒 ADMIN ONLY (using 'type')
        if (strtolower($church->pivot->type ?? '') !== 'admin') {
            return redirect()->route('worship-team')
                ->with('error', 'Access denied. Admins only.');
        }
        $users = $church->users()
            ->wherePivot('is_approved', 0)
            ->get();

        return view('approval.index', compact('users'));
    }

    // 👉 Approve user
    public function approve($userId)
    {
        $church = Auth::user()->churches()->first();

        if ($church) {
            $church->users()->updateExistingPivot($userId, [
                'is_approved' => 1
            ]);
        }

        return back()->with('success', 'User approved');
    }

    // 👉 Decline user
    public function decline($userId)
    {
        $church = Auth::user()->churches()->first();

        if ($church) {
            // 1. Remove user from this church (pivot)
            $church->users()->detach($userId);

            // 2. Get the user
            $user = User::find($userId);

            // 3. Check if user still belongs to ANY church
            if ($user && $user->churches()->count() === 0) {
                // 4. Delete user if no more churches
                $user->delete();
            }
        }

        return back()->with('success', 'User declined');
    }
}
