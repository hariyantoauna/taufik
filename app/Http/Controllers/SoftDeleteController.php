<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SoftDeleteController extends Controller
{
    public function destroy($model, $id)
    {

       
        // daftar model yang diizinkan
        $allowedModels = [
            'canva' => \App\Models\Canva::class,
            'video' => \App\Models\Video::class,
            'url'   => \App\Models\Url::class,
            'post'  => \App\Models\Post::class,
            'pdf'   => \App\Models\Pdf::class,
        ];

        // cek apakah model valid
        if (!array_key_exists($model, $allowedModels)) {
            return redirect()->back()->with('error', 'Model tidak dikenali.');
        }

        $modelClass = $allowedModels[$model];
        $item = $modelClass::findOrFail($id);

        $user = Auth::user();

        // cek: hanya owner, teacher, admin
        if ($user->id !== $item->user_id && !in_array($user->role, ['teacher', 'admin'])) {
            return redirect()->back()->with('error', 'Anda tidak punya izin menghapus data ini.');
        }

        // update active = 0
        $item->update(['active' => 0]);

        return redirect()->back()->with('success', ucfirst($model).' berhasil dinonaktifkan.');
    }
}