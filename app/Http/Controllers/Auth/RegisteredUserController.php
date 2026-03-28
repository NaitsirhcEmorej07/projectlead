<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Church;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Admin Register Page
    public function create(): View
    {
        return view('auth.register');
    }

    // Member Register Page
    public function createUser(): View
    {
        $churches = Church::select('id', 'name', 'abbr')->get();

        return view('auth.register-user', compact('churches'));
    }

    public function store(Request $request): RedirectResponse
    {
        // ✅ VALIDATION
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'type' => ['required', 'in:admin,member'],
            'church_id' => ['nullable', 'exists:churches,id'],
        ]);

        // ✅ CREATE USER FIRST
        $user = User::create([
            'name' => $request->name ?? $request->church_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ]);

        // ✅ IF ADMIN → CREATE CHURCH
        if ($request->type === 'admin') {

            $church = Church::create([
                'name' => $request->church_name,
                'abbr' => $request->church_abbr
                    ? strtoupper($request->church_abbr)
                    : null,
                'created_by' => $user->id,
            ]);

            // 🔥 assign church_id to admin
            $user->church_id = $church->id;

            // ✅ auto approve admin
            $user->is_approved = 1;

            $user->save();
        }

        // ✅ IF MEMBER → ASSIGN CHURCH
        if ($request->type === 'member') {
            $user->church_id = $request->church_id;

            // ❌ not approved by default
            $user->is_approved = 0;

            $user->save();
        }

        event(new Registered($user));

        if ($user->type === 'admin') {
            Auth::login($user);

            return redirect()->route('dashboard');
        }

        return back()->with('success', 'Thank you for registration. Please wait for your church admin approval before logging in.');
    }
}
