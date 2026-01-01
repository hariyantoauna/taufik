<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use App\Models\Order;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatController extends Controller
{
    //
    public function index(Course $course)
    {

        $courseId = $course->id;
        $course = Course::findOrFail($courseId);
        // Ambil user_id dari orders
        $orderUserIds = Order::where('course_id', $courseId)->pluck('user_id')->toArray();

        // Ambil course creator (misal fieldnya user_id)
        $courseCreatorId = Course::where('id', $courseId)->value('user_id');

        // Ambil semua user dengan role admin
        $adminUserIds = DB::table('model_has_roles')
            ->where('role_id', function ($query) {
                $query->select('id')->from('roles')->where('name', 'admin')->limit(1);
            })
            ->pluck('model_id')
            ->toArray();

        // Gabungkan semua user_id unik
        $allUserIds = array_unique(array_merge($orderUserIds, [$courseCreatorId], $adminUserIds));



        // Ambil data user lengkap
        $users = User::whereIn('id', $allUserIds)
            ->where('id', '!=', Auth::id())
            ->latest()
            ->get();
        // dd($users);
        // user lain di course ini
        return view('chat.index', compact('users', 'courseId', 'course'));
    }

    public function fetchMessages($courseId, $user_id = null)
    {
        $userId = Auth::id();
        $messages = Chat::with('sender')
            ->where('course_id', $courseId)
            ->when($user_id, function ($q) use ($userId, $user_id) {
                $q->where(function ($query) use ($userId, $user_id) {
                    $query->where('sender_id', $userId)->where('receiver_id', $user_id);
                })->orWhere(function ($query) use ($userId, $user_id) {
                    $query->where('sender_id', $user_id)->where('receiver_id', $userId);
                });
            }, function ($q) {
                $q->whereNull('receiver_id'); // chat ke semua
            })
            ->orderBy('created_at')
            ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $chat = Chat::create([
            'course_id' => $request->course_id,
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($chat);
    }

    public function typing(Request $request)
    {
        $key = "typing_{$request->course_id}_{$request->user()->id}_to_{$request->receiver_id}";
        Cache::put($key, now()->timestamp, 10);
        return response()->json(['status' => 'ok']);
    }

    public function typingStatus($courseId, $receiverId = null)
    {
        $key = "typing_{$receiverId}_to_" . Auth::user()->id . "_in_{$courseId}";
        $value = Cache::get($key);
        $isTyping = $value && (now()->timestamp - $value) < 10;

        return response()->json(['typing' => $isTyping]);
    }
}
