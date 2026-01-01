<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use App\Models\Url;
use App\Models\Course;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UrlLearningController extends Controller
{
    //
    public function index(Course $course)
    {
        //




        $timelines = Url::where(['course_id'=> $course->id, 'active'=> 1])->latest()->get();
        // dd($timeline);

        $data = [
            'title' => "URL",
            'course' => $course,
            'timelines' => $timelines,
            'comments' =>  Comment::where(['course_id' => $course->id])->latest()->get(),
        ];

        return view('learning.url', $data);
    }

    public function store(Request $request)
    {
        // dd($request);

        $request->validate([
            'url_url' => 'required|string|max:1000',
            'post' => 'required|string',
        ]);

        // dd(3 . time());

        Url::create([
            'user_id' => Auth::user()->id,
            'url_url' => $request->url_url,
            'course_id' => $request->course_id,
            'post' => $request->post,
            'code' => 3 . time(),
            'active' => 1
        ]);

        return back()->with('success', 'Url pdf berhasil dikirim!');
    }
}
