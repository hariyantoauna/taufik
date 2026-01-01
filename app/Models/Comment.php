<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //

    protected $fillable = ['course_id', 'code', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
