<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorshipDevotion;
use App\Models\WorshipDevotionComment;
use App\Models\WorshipDevotionLike;

use Illuminate\Support\Facades\Auth;

class WorshipDevotionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | FEED (FB STYLE)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $churchId = session('church_id');

        $devotions = WorshipDevotion::with([
            'user',
            'comments.user',
            'comments.replies.user'
        ])
            ->where('church_id', $churchId)
            ->latest()
            ->paginate(10); // mas maliit for smooth scroll

        // 🔥 if AJAX request, return partial view lang
        if ($request->ajax()) {
            return view('partials.devotions', compact('devotions'))->render();
        }

        return view('worship-devotions', compact('devotions'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE DEVOTION
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        WorshipDevotion::create([
            'user_id'   => Auth::user()->id,
            'church_id' => session('church_id'), // ✅ from session
            'content'   => $request->content,
        ]);

        return back()->with('success', 'Devotion posted!');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE COMMENT / REPLY
    |--------------------------------------------------------------------------
    */
    public function comment(Request $request)
    {
        $request->validate([
            'worship_devotion_id' => 'required|exists:worship_devotions,id',
            'comment'             => 'required|string|max:500',
            'parent_id'           => 'nullable|exists:worship_devotion_comments,id',
        ]);

        WorshipDevotionComment::create([
            'worship_devotion_id' => $request->worship_devotion_id,
            'user_id'             => Auth::user()->id,
            'church_id'           => session('church_id'), // ✅ FIXED
            'comment'             => $request->comment,
            'parent_id'           => $request->parent_id,
        ]);

        return back()->with('success', 'Comment added!');
    }

    public function destroy($id)
    {
        $churchId = session('church_id');

        $devotion = WorshipDevotion::where('id', $id)
            ->where('church_id', $churchId)
            ->firstOrFail();

        if ($devotion->user_id !== \Illuminate\Support\Facades\Auth::user()->id) {
            abort(403);
        }

        // 🔥 DELETE COMMENTS
        WorshipDevotionComment::where('worship_devotion_id', $devotion->id)->delete();

        // 🔥 DELETE LIKES (NEW FIX)
        WorshipDevotionLike::where('worship_devotion_id', $devotion->id)->delete();

        // DELETE POST
        $devotion->delete();

        return back()->with('success', 'Devotion deleted!');
    }

    public function react(Request $request, $id)
    {
        $churchId = session('church_id');
        $userId = Auth::user()->id;
        $reaction = $request->reaction;

        $devotion = WorshipDevotion::where('id', $id)
            ->where('church_id', $churchId)
            ->firstOrFail();

        $existing = WorshipDevotionLike::where([
            'worship_devotion_id' => $id,
            'user_id' => $userId
        ])->first();

        if ($existing) {

            if ($existing->reaction === $reaction) {
                // 🔥 SAME CLICK = UNLIKE
                $existing->delete();

                $status = 'removed';
            } else {
                // 🔥 CHANGE REACTION (like → heart etc)
                $existing->update([
                    'reaction' => $reaction
                ]);

                $status = 'updated';
            }
        } else {
            // 🔥 NEW REACTION
            WorshipDevotionLike::create([
                'worship_devotion_id' => $id,
                'user_id' => $userId,
                'church_id' => $churchId,
                'reaction' => $reaction
            ]);

            $status = 'added';
        }

        // counts
        $counts = WorshipDevotionLike::where('worship_devotion_id', $id)
            ->selectRaw('reaction, COUNT(*) as total')
            ->groupBy('reaction')
            ->pluck('total', 'reaction');

        return response()->json([
            'status' => $status,
            'counts' => $counts
        ]);
    }
}
