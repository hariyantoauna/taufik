<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    //
    public function store(Course $course)
    {
        $userId = Auth::user()->id;

        // Cek jika user sudah mengambil course ini
        $exists = Order::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Kamu sudah mengambil kursus ini.');
        }

        Order::create([
            'user_id' => $userId,
            'course_id' => $course->id,
        ]);

        return back()->with('success', 'Kursus berhasil diambil!');
    }
}
