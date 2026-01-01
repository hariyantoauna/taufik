<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use App\Models\Post;
use App\Models\Word;
use App\Models\Image;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $courses = Course::where('is_published')->latest()->get();

        $data = [
            'courses' => $courses
        ];

        return view('home', $data);
    }

    public function store(Request $request) {}
}
