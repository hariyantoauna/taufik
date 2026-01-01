<?php

namespace App\Http\Controllers;

use App\Models\Pdf;
use App\Models\Course;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PdfLearningController extends Controller
{
    //

    //
    public function index(Course $course)
    {
        //




        $timelines = Pdf::where(['course_id'=> $course->id, 'active'=> 1])->latest()->get();
        // dd($timeline);

        $data = [
            'title' => "PDF",
            'course' => $course,
            'timelines' => $timelines,
            'comments' =>  Comment::where(['course_id' => $course->id])->latest()->get(),
        ];

        return view('learning.pdf', $data);
    }

    public function store(Request $request)
    {


     $request->validate([
    'pdf_url' => 'required|string|max:1000',
    'post' => 'required|string',
]);

// ambil file_id dari url
$pdfUrl = $request->pdf_url;
if (preg_match('/\/d\/(.*?)\//', $pdfUrl, $matches)) {
    $fileId = $matches[1];
    $pdfUrl = "https://drive.google.com/file/d/{$fileId}/preview";
}

Pdf::create([
    'user_id'   => Auth::user()->id,
    'pdf_url'   => $pdfUrl, // simpan versi /preview
    'course_id' => $request->course_id,
    'post'      => $request->post,
    'code'      => 2 . time(),
    'active'    => 1
]);

return back()->with('success', 'Url PDF berhasil dikirim!');
    }
}
