<?php

namespace App\Models;

use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
    protected $fillable = [
        'course_id',
        'sender_id',
        'receiver_id',
        'message',
        'active'
    ];

    // Relasi ke user pengirim
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relasi ke user penerima (bisa null jika chat ke semua)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Relasi ke course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
