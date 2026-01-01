<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $fillable = [
        'user_id',
        'course_id',
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


    // Agar bisa filtering otomatis
    protected static function booted()
    {
        // Semua query hanya ambil data yg belum "dihapus"
        static::addGlobalScope('notDeleted', function ($query) {
            $query->whereNotNull('updated_at');
        });
    }

    // Fungsi custom soft delete
    public function softDelete()
    {
        $this->updated_at = null;
        $this->save();
    }
}
