<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // get church
        $church = $user->churches()->orderBy('church_user.created_at')->first();

        $validated = $request->validated();

        $request->validate([
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        // update user
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 👉 SAVE USER FIRST
        $user->save();

        // =====================================================
        // 👉 FORCE SYNC NAME (ADMIN ONLY)
        // =====================================================
        if ($user->type === 'admin' && $church) {

            // 🔥 ALWAYS SYNC (no condition)
            $church->name = $user->name;

            // 👉 logo
            if ($request->hasFile('logo')) {

                if ($church->logo && Storage::disk('public')->exists($church->logo)) {
                    Storage::disk('public')->delete($church->logo);
                }

                $path = $request->file('logo')->storePublicly('church-logos');
                $church->logo = $path;
            }

            $church->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
