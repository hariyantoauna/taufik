<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //


    protected $fillable = [
        'title',
        'user_id',
        'level',
        'learning_tools',
        'is_published'
    ];

    // Relasi: Course dimiliki oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
