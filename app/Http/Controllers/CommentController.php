<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    //
    public function store(Request $request)
    {

        // dd($request);
        $request->validate([

            'message' => 'required|string',

        ]);

        Comment::create([
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
            'message' => $request->message,

            'code' => $request->code, // optional
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan');
    }
}
