<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    //
    //
    protected $fillable = [
        'user_id',
        'course_id',
        'video_url',
        'post',
        'code',
        'active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
