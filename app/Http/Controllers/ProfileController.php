<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\SongSelect;
use App\Models\SongUser;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // 🔥 Get all roles
        $roles = DB::table('role_select')->get();

        // 🔥 Get user selected roles
        $userRoleIds = $user->roles()->pluck('role_select.id')->toArray();

        // 🔥 ADD THIS (songs for autocomplete)
        $churchId = session('church_id');

        $songs = SongSelect::select(
            'id',
            'song_title',
            'song_by',
            'song_reference',
            'original_key'
        )
            ->where('church_id', $churchId)
            ->get();

        $userSongs = SongUser::where('user_id', $user->id)->get();


        return view('profile.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoleIds' => $userRoleIds,
            'songs' => $songs,
            'userSongs' => $userSongs, // 👈 ADD THIS
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // =========================
        // VALIDATION
        // =========================
        $validated = $request->validated();

        $request->validate([
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'describe' => ['nullable', 'string', 'max:1000'],
            'roles' => ['nullable', 'array'],
        ]);

        // =========================
        // UPDATE USER FIELDS
        // =========================
        $user->fill($validated);

        $user->contact_number = $request->contact_number;
        $user->describe = $request->describe;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // =========================
        // PROFILE PICTURE
        // =========================
        if ($request->hasFile('profile_picture')) {

            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->storePublicly('profile-pictures');
            $user->profile_picture = $path;
        }

        // SAVE USER FIRST
        $user->save();

        // =========================
        // 🔥 SAVE ROLES (PIVOT)
        // =========================
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->detach(); // optional: remove all if none selected
        }

        // =========================
        // ADMIN LOGIC (CHURCH)
        // =========================
        if ($user->type === 'admin') {

            $church = $user->churches()->orderBy('church_user.created_at')->first();

            if ($church) {

                $church->name = $user->name;

                if ($request->hasFile('logo')) {

                    if ($church->logo && Storage::disk('public')->exists($church->logo)) {
                        Storage::disk('public')->delete($church->logo);
                    }

                    $path = $request->file('logo')->storePublicly('church-logos');
                    $church->logo = $path;
                }

                $church->save();
            }
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Deactivate the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // 🔥 Manual control (no cascade)
        if ($user->churches()->exists()) {
            $user->churches()->updateExistingPivot(
                $user->churches->pluck('id')->toArray(),
                [
                    'is_approved' => false,
                ]
            );
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function storeSong(Request $request)
    {
        $request->validate([
            'song_title' => 'required|string|max:255',
            'song_by' => 'nullable|string|max:255',
            'song_reference' => 'nullable|string|max:255',
            'user_key' => 'nullable|string|max:10',
        ]);

        $user = Auth::user();

        $song = SongUser::create([
            'user_id' => $user->id,
            'church_id' => session('church_id') ?? null,
            'song_title' => $request->song_title,
            'song_by' => $request->song_by,
            'song_reference' => $request->song_reference,
            'user_key' => $request->user_key,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $song
        ]);
    }

    public function updateSong(Request $request, $id)
    {
        $request->validate([
            'song_title' => 'required|string|max:255',
            'song_by' => 'nullable|string|max:255',
            'song_reference' => 'nullable|string|max:255',
            'user_key' => 'nullable|string|max:10',
        ]);

        $user = Auth::user();

        $song = SongUser::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $song->update([
            'song_title' => $request->song_title,
            'song_by' => $request->song_by,
            'song_reference' => $request->song_reference,
            'user_key' => $request->user_key,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $song
        ]);
    }

    public function deleteSong($id)
    {
        $user = Auth::user();

        $song = SongUser::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $song->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
}
