<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Course;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VideoLearningController extends Controller
{
    //
    public function index(Course $course)
    {
        //




        $timelines = Video::where(['course_id'=> $course->id, 'active'=> 1])->latest()->get();
        // dd($timeline);

        $data = [
            'title' => "Video",
            'course' => $course,
            'timelines' => $timelines,
            'comments' =>  Comment::where(['course_id' => $course->id])->latest()->get(),
        ];

        return view('learning.video', $data);
    }

    public function store(Request $request)
    {


         $request->validate([
        'video_url' => 'required|string|max:1000',
        'post'      => 'required|string',
    ]);

    $rawUrl = $request->video_url;

    // ambil VIDEO_ID dari berbagai format YouTube
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|live\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $rawUrl, $m)) {
        $videoId = $m[1];
    } elseif (preg_match('/v=([A-Za-z0-9_-]{11})/', $rawUrl, $m)) {
        $videoId = $m[1];
    } else {
        return back()->withErrors(['video_url' => 'URL YouTube tidak valid']);
    }

    // ubah jadi embed URL
    $embedUrl = "https://www.youtube.com/embed/{$videoId}";

    Video::create([
        'user_id'   => Auth::id(),
        'video_url' => $embedUrl, // simpan versi embed
        'course_id' => $request->course_id,
        'post'      => $request->post,
        'code'      => 4 . time(),
        'active'    => 1,
    ]);

    return back()->with('success', 'Url video berhasil dikirim!');
    }

    
}
