<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use App\Models\Url;
use App\Models\Post;
use App\Models\Canva;
use App\Models\Image;
use App\Models\Video;
use App\Models\Course;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeLearningController extends Controller
{
    //

    public function index(Course $course)
    {
        //


        $posts = Post::where(['course_id'=> $course->id, 'active' => 1])->latest()->get();
        $canvas = Canva::where(['course_id'=> $course->id, 'active' => 1])->latest()->get();
        $videos = Video::where(['course_id'=> $course->id, 'active' => 1])->latest()->get();
        $pdfs = Pdf::where(['course_id'=> $course->id, 'active' => 1])->latest()->get();
        $urls = Url::where(['course_id'=> $course->id, 'active' => 1])->latest()->get();

        $comments = Comment::where(['course_id' => $course->id])->latest()->get();

        // dd($comments);


        $timelines = collect()
            ->merge($posts->map(fn($item) => [
                'type' => 'post',
                'feature' => 'Postingan',
                'post' => $item->post,

                'name' => optional($item->user)->name,
                'photo' => optional($item->user)->photo,
                'code' => $item->code,
                
             

                'created_at' => $item->created_at,
            ]))

            ->merge($canvas->map(fn($item) => [
                'type' => 'canva',
                'feature' => 'Canva',
                'post' => $item->post,
                'canva_url' => $item->canva_url,

                'name' => optional($item->user)->name,
                'photo' => optional($item->user)->photo,
                'code' => $item->code,
                'created_at' => $item->created_at,
            ]))

            ->merge($videos->map(fn($item) => [
                'type' => 'video',
                'feature' => 'Video Pembelajaran',
                'post' => $item->post,
                'video_url' => $item->video_url,

                'name' => optional($item->user)->name,
                'photo' => optional($item->user)->photo,
                'code' => $item->code,
                'created_at' => $item->created_at,
            ]))

            ->merge($pdfs->map(fn($item) => [
                'type' => 'pdf',
                'feature' => 'Dokumen PDF',
                'post' => $item->post,
                'pdf_url' => $item->pdf_url,

                'name' => optional($item->user)->name,
                'photo' => optional($item->user)->photo,
                'code' => $item->code,
                'created_at' => $item->created_at,
            ]))

            ->merge($urls->map(fn($item) => [
                'type' => 'url',
                'feature' => 'Dokumen URL',
                'post' => $item->post,
                'url_url' => $item->url_url,

                'name' => optional($item->user)->name,
                'photo' => optional($item->user)->photo,
                'code' => $item->code,
                'created_at' => $item->created_at,
            ]))

            ->sortByDesc('created_at')
            ->values();

        // dd($timeline);

        $data = [
            'title' => "Beranda",
            'course' => $course,
            'timelines' => $timelines,
            'comments' => $comments,
        ];

        return view('learning.home', $data);
    }

    public function store(Request $request)
    {


        $request->validate([
            'post' => 'required|string|max:1000',
        ]);

        Post::create([
            'user_id' => Auth::user()->id,
            'post' => $request->post,
            'course_id' => $request->course_id,
            'code' => 1 . time(),
            'active' => 1
        ]);

        return back()->with('success', 'Postingan berhasil dikirim!');
    }
}
