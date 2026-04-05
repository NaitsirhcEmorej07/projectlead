<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorshipTeamController;
use App\Http\Controllers\SongController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


Route::get('/worship-team/{id}', [WorshipTeamController::class, 'view'])
    ->name('worship.team.view');


/*
|--------------------------------------------------------------------------
| GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('home');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::view('/registration_page', 'welcome')->name('registration.page');
Route::get('/register-user', [RegisteredUserController::class, 'createUser'])->name('register.user');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // CHURCH SELECTION (IF MULTIPLE) ---------------------------------------------------------------------------------------------
    Route::get('/select-church', fn() => view('auth.select-church', ['churches' => Auth::user()->churches()->wherePivot('is_approved', 1)->get()]))->name('select-church');
    Route::post('/select-church', fn(Request $request) => (function () use ($request) {
        $v = $request->validate(['church_id' => ['required', 'exists:churches,id']]);
        abort_unless(Auth::user()->churches()->where('church_id', $v['church_id'])->wherePivot('is_approved', 1)->exists(), 403);
        session(['church_id' => $v['church_id']]);
        return redirect()->route('worship-team');
    })())->name('select-church.store');


    // MAIN APP ---------------------------------------------------------------------------------------------
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/worship-schedule', fn() => view('worship-schedule'))->name('worship-schedule');

    // APPROVAL ----------------------------------------------------------------------------------------------
    Route::get('/approval', [ApprovalController::class, 'index'])->middleware(['church.admin'])->name('approval');
    Route::post('/approval/{user}/approve', [ApprovalController::class, 'approve'])->middleware(['church.admin'])->name('approval.approve');
    Route::post('/approval/{user}/decline', [ApprovalController::class, 'decline'])->middleware(['church.admin'])->name('approval.decline');

    // SONG ----------------------------------------------------------------------------------------------
    Route::get('/songs', [SongController::class, 'index'])->name('songs.index');
    Route::post('/songs', [SongController::class, 'store'])->name('songs.store');
    Route::put('/songs/{id}', [SongController::class, 'update'])->name('songs.update');
    Route::delete('/songs/{id}', [SongController::class, 'destroy'])->name('songs.destroy');

    // WORSHIP TEAM ----------------------------------------------------------------------------------------------
    Route::get('/worship-team', [WorshipTeamController::class, 'index'])->name('worship-team');
    Route::get('/worship-team-view', [WorshipTeamController::class, 'index'])->name('worship-team-view');



    // PROFILE ----------------------------------------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/profile-song/store', [ProfileController::class, 'storeSong'])->name('profile-song.store');
    Route::put('/profile-song/update/{id}', [ProfileController::class, 'updateSong'])->name('profile-song.update');
    Route::delete('/profile-song/delete/{id}', [ProfileController::class, 'deleteSong'])->name('profile-song.delete');
});


require __DIR__ . '/auth.php';
