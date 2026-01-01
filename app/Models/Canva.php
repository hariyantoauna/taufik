<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canva extends Model
{
    //
    //
    protected $fillable = [
        'user_id',
        'course_id',
        'canva_url',
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
