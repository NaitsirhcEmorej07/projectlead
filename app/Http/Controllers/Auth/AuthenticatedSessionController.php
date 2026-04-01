<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ get approved churches
        $approvedChurches = $user->churches()
            ->wherePivot('is_approved', 1)
            ->get();

        if ($approvedChurches->isEmpty()) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'You are not approved in any church.',
            ])->onlyInput('email');
        }

        // ✅ if only 1 → auto select
        if ($approvedChurches->count() === 1) {
            session(['church_id' => $approvedChurches->first()->id]);

            return redirect()->intended(route('worship-team'));
        }

        // 🔥 if multiple → show selection page
        session(['select_church_user_id' => $user->id]);

        return redirect()->route('select-church');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
