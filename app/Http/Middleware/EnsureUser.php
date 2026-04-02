<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureChurchAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 🚫 Not logged in
        if (!$user) {
            return redirect()->route('login');
        }

        // 🏛️ No selected church
        $churchId = session('church_id');

        if (!$churchId) {
            return redirect()->route('select-church')
                ->with('error', 'Please select a church first.');
        }

        // 🔎 Get church relation
        $church = $user->churches()
            ->where('church_id', $churchId)
            ->first();

        // 🔒 ADMIN CHECK
        if (!$church || strtolower($church->pivot->type ?? '') !== 'admin') {
            return redirect()->route('worship-team')
                ->with('error', 'Admins only.');
        }

        // ✅ Allow access
        return $next($request);
    }
}