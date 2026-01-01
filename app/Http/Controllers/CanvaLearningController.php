<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Canva;
use App\Models\Course;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CanvaLearningController extends Controller
{
    //
    public function index(Course $course)
    {
        //




        $timelines = Canva::where(['course_id'=> $course->id, 'active'=> 1])->latest()->get();
        // dd($timeline);
        $comments = Comment::where(['course_id' => $course->id])->latest()->get();

        $data = [
            'title' => "Canva",
            'course' => $course,
            'timelines' => $timelines,
            'comments' => $comments
        ];

        return view('learning.canva', $data);
    }

    public function store(Request $request)
    {


       // 1. Validasi input
        $request->validate([
            'canva_url' => 'required|string|max:1000',
            'post' => 'required|string',
            'course_id' => 'required|integer',
        ]);

        // 2. Ambil URL Canva dari request
        $url = $request->canva_url;

        // 3. Ubah link edit/share menjadi format embed
        if (str_contains($url, 'canva.com')) {
            // Hilangkan query string setelah /view atau /edit
            $url = preg_replace('/\/edit\?.*/', '/view?embed', $url);
            $url = preg_replace('/\/view\?.*/', '/view?embed', $url);
        }

        // 4. Simpan ke database
        Canva::create([
            'user_id'   => Auth::id(),
            'canva_url' => $url,
            'course_id' => $request->course_id,
            'post'      => $request->post,
            'code'      => 1 . time(),
            'active'    => 1,
        ]);

        // 5. Redirect dengan pesan sukses
        return back()->with('success', 'Url Canva berhasil dikirim!');
    }

    public function destroy($id)
{
    $canva = Canva::findOrFail($id);

    // ubah active jadi 0, bukan delete permanen
    $canva->update(['active' => 0]);

    return redirect()->back()->with('success', 'Data berhasil dinonaktifkan.');
}
    
}
