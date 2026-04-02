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
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // ✅ Admin Register Page
    public function create(): View
    {
        return view('auth.register');
    }

    // ✅ User Register Page
    public function createUser(): View
    {
        $churches = Church::select('id', 'name')->get();

        return view('auth.register-user', compact('churches'));
    }

    // ✅ Store (ADMIN and USER)
    public function store(Request $request): RedirectResponse
    {
        // ✅ VALIDATION
        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
            'type' => ['required', 'in:admin,member'],

            // admin fields
            'church_name' => ['required_if:type,admin', 'string', 'max:255'],
            'church_abbr' => ['nullable', 'string', 'max:50'],

            // member fields
            'churches' => ['required_if:type,member', 'array'],
            'churches.*' => ['exists:churches,id'],
        ]);

        // ✅ CREATE USER (NO is_approved HERE ❌)
        $user = User::create([
            'name' => $request->name ?? $request->church_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ]);

        // 🔥 ADMIN FLOW
        if ($request->type === 'admin') {

            $church = Church::create([
                'name' => $request->church_name,
                'abbr' => $request->church_abbr
                    ? strtoupper($request->church_abbr)
                    : null,
                'created_by' => $user->id,
            ]);

            // ✅ attach with pivot data
            $user->churches()->attach($church->id, [
                'is_approved' => 1,
                'type' => 'admin',
            ]);

            // ✅ ADD THIS LINE
            session(['church_id' => $church->id]);
        }

        // 🔥 MEMBER FLOW (MULTI SELECT)
        if ($request->type === 'member') {

            // ✅ attach multiple with pivot data
            foreach ($request->churches as $churchId) {
                $user->churches()->attach($churchId, [
                    'is_approved' => 0,
                    'type' => 'member',
                ]);
            }
        }

        event(new Registered($user));

        // ✅ AUTO LOGIN ADMIN ONLY
        if ($user->type === 'admin') {
            session(['church_id' => $church->id]);
            Auth::login($user);
            return redirect()->route('worship-team');
        }

        return back()->with('success', 'Please wait for the church admin’s approval before logging in.');
    }
}
