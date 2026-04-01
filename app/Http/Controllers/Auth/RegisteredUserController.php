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

        // ✅ CREATE USER
        $user = User::create([
            'name' => $request->name ?? $request->church_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'is_approved' => $request->type === 'admin' ? 1 : 0,
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

            // connect
            $user->churches()->attach($church->id);
        }

        // 🔥 MEMBER FLOW (MULTI SELECT)
        if ($request->type === 'member') {

            // attach multiple churches
            $user->churches()->attach($request->churches);
        }

        event(new Registered($user));

        // ✅ AUTO LOGIN ADMIN ONLY
        if ($user->type === 'admin') {
            Auth::login($user);
            return redirect()->route('worship-team');
        }

        return back()->with('success', 'Registered! Wait for admin approval.');
    }
}
