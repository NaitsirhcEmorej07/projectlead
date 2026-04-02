<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
    Route::get('/worship-team', fn() => view('worship-team'))->name('worship-team');
    Route::get('/worship-schedule', fn() => view('worship-schedule'))->name('worship-schedule');

    Route::get('/approval', [ApprovalController::class, 'index'])->middleware(['church.admin'])->name('approval');
    Route::post('/approval/{user}/approve', [ApprovalController::class, 'approve'])->middleware(['church.admin'])->name('approval.approve');
    Route::post('/approval/{user}/decline', [ApprovalController::class, 'decline'])->middleware(['church.admin'])->name('approval.decline');

    // PROFILE ----------------------------------------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__ . '/auth.php';
