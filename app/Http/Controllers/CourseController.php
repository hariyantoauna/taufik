<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Tampilkan semua course.
     */
    public function index()
    {
        $courses = Course::with('user')
            ->when(!Auth::user()->hasAnyRole(['admin', 'teacher', 'depolover']), function ($query) {
                // Jika bukan admin/teacher/depolover, filter hanya yang publikasi
                $query->where('is_published', 1);
            })
            ->orderByDesc('created_at')
            ->get();

        // dd($courses);


        return view('course.index', compact('courses'));
    }

    /**
     * Tampilkan form untuk membuat course baru.
     */
    public function create()
    {
        return view('course.create');
    }

    /**
     * Simpan course baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'learning_tools' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        Course::create($validated);

        return redirect()->route('course.index')->with('success', 'Course berhasil dibuat.');
    }

    /**
     * Tampilkan detail course.
     */
    public function show(Course $course)
    {
        $order = Order::Where(['course_id' => $course->id, 'user_id' => Auth::user()->id])->first();
        $participants = $course->orders()->where('status', 1)->with('user')->get();
        $applicants = $course->orders()->where('status', 0)->with('user')->get();
        return view('course.show', compact('course', 'order', 'participants', 'applicants'));
    }

    /**
     * Tampilkan form edit course.
     */
    public function edit(Course $course)
    {
        return view('course.edit', compact('course'));
    }

    /**
     * Simpan perubahan pada course.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'level' => 'nullable|string|max:255',
            'learning_tools' => 'nullable|string',
        ]);

        $course->update($validated);

        return redirect()->route('course.index')->with('success', 'Course berhasil diperbarui.');
    }

    /**
     * Hapus course.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('course.index')->with('success', 'Course berhasil dihapus.');
    }

    public function togglePublish($id)
    {
        $course = Course::findOrFail($id);
        $course->is_published = !$course->is_published;
        $course->save();

        return back()->with('status', 'Status kursus diperbarui.');
    }

    public function revoke(Request $request, Course $course)
    {

        $ids = $request->input('revoke_ids', []);

        if (count($ids) > 0) {
            Order::whereIn('id', $request->revoke_ids)->update(['status' => 0]);
            return redirect()->back()->with('success', 'Peserta telah ditangguhkan.');
        } else {
            return back()->with('error', 'Silakan pilih minimal satu peserta atau pemohon.');
        }
    }

    public function approve(Request $request, Course $course)
    {

        $ids = $request->input('approve_ids', []);

        if (count($ids) > 0) {
            Order::whereIn('id', $request->approve_ids)->update(['status' => 1]);
            return redirect()->back()->with('success', 'Pemohon berhasil disetujui sebagai peserta.');
        } else {
            return back()->with('error', 'Silakan pilih minimal satu peserta atau pemohon.');
        }
    }
}
