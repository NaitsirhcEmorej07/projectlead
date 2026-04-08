<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX (List / Calendar Data)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $currentDate = \Carbon\Carbon::create($year, $month, 1);

        $startDayOfWeek = $currentDate->copy()->startOfMonth()->dayOfWeek;
        $daysInMonth = $currentDate->copy()->endOfMonth()->day;

        $schedules = \App\Models\Schedule::where('church_id', session('church_id'))
            ->whereYear('sched_date', $year)
            ->whereMonth('sched_date', $month)
            ->get()
            ->groupBy('sched_date');

        return view('worship-schedule', [
            'schedules' => $schedules,
            'currentDate' => $currentDate,
            'month' => $month,
            'year' => $year,
            'startDayOfWeek' => $startDayOfWeek,
            'daysInMonth' => $daysInMonth,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE (Create)
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'sched_title' => 'nullable|string|max:255',
            'sched_description' => 'nullable|string',
            'sched_type' => 'nullable|string|max:100',
            'sched_date' => 'required|date',
            'sched_time' => 'nullable',
        ]);

        Schedule::create([
            'church_id' => session('church_id'),
            'user_id' => Auth::id(),
            'sched_title' => $request->sched_title,
            'sched_description' => $request->sched_description,
            'sched_type' => $request->sched_type,
            'sched_date' => $request->sched_date,
            'sched_time' => $request->sched_time,
        ]);

        return back()->with('success', 'Schedule created successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $schedule = Schedule::where('church_id', session('church_id'))
            ->findOrFail($id);

        $request->validate([
            'sched_title' => 'nullable|string|max:255',
            'sched_description' => 'nullable|string',
            'sched_type' => 'nullable|string|max:100',
            'sched_date' => 'required|date',
            'sched_time' => 'nullable',
        ]);

        $schedule->update([
            'sched_title' => $request->sched_title,
            'sched_description' => $request->sched_description,
            'sched_type' => $request->sched_type,
            'sched_date' => $request->sched_date,
            'sched_time' => $request->sched_time,
        ]);

        return back()->with('success', 'Schedule updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        $schedule = Schedule::where('church_id', session('church_id'))
            ->findOrFail($id);

        $schedule->delete();

        return back()->with('success', 'Schedule deleted successfully.');
    }
}
