<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\SoftDeleteController;
use App\Http\Controllers\PdfLearningController;
use App\Http\Controllers\UrlLearningController;
use App\Http\Controllers\HomeLearningController;
use App\Http\Controllers\PostLearningController;
use App\Http\Controllers\CanvaLearningController;
use App\Http\Controllers\VideoLearningController;

Route::get('/', function () {
    return redirect('/course');
});

Route::get('/home', [HomeController::class, 'index'])->name('home.index');

Auth::routes();

Route::middleware(['auth'])->group(function () {


    Route::resource('course', CourseController::class);
    Route::patch('/course/{id}/toggle-publish', [CourseController::class, 'togglePublish'])
        ->name('course.togglePublish')
        ->middleware(['role:admin|teacher|depolover']);
});

Route::middleware(['auth'])->group(function () {
    //
    Route::middleware(['auth'])->group(function () {
        Route::post('/course/{course}/order', [OrderController::class, 'store'])->name('course.order');
    });
});

Route::middleware(['auth'])->group(function () {
    //

    Route::get('home/learning/{course}', [HomeLearningController::class, 'index'])->name('home.index');

    Route::get('post/learning/{course}', [PostLearningController::class, 'index'])->name('post.index');

    Route::post('post', [PostLearningController::class, 'store'])->name('post.store');
    Route::put('post/update/{id}', [PostLearningController::class, 'update'])->name('post.update');
});

Route::middleware(['auth'])->group(function () {
    //

    Route::get('canva/learning/{course}', [CanvaLearningController::class, 'index'])->name('canva.index');

    Route::post('canva', [CanvaLearningController::class, 'store'])->name('canva.store');
    Route::delete('/canva/{id}', [CanvaLearningController::class, 'destroy'])->name('canva.destroy');
});

Route::middleware(['auth'])->group(function () {
    //

    Route::get('video/learning/{course}', [VideoLearningController::class, 'index'])->name('video.index');

    Route::post('video', [VideoLearningController::class, 'store'])->name('video.store');
});

Route::middleware(['auth'])->group(function () {
    //

    Route::get('pdf/learning/{course}', [PdfLearningController::class, 'index'])->name('pdf.index');

    Route::post('pdf', [PdfLearningController::class, 'store'])->name('pdf.store');
});

Route::middleware(['auth'])->group(function () {
    //

    Route::get('url/learning/{course}', [UrlLearningController::class, 'index'])->name('url.index');

    Route::post('url', [UrlLearningController::class, 'store'])->name('url.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');

    Route::post('/course/{course}/revoke', [CourseController::class, 'revoke'])->name('course.revoke');
    Route::post('/course/{course}/approve', [CourseController::class, 'approve'])->name('course.approve');
});


Route::middleware('auth', 'role:admin|teacher')->group(function () {




    Route::get('/users/roles', [UserRoleController::class, 'index'])->name('users.roles.index');
    Route::post('/users/roles/{user}', [UserRoleController::class, 'update'])->name('users.roles.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/chat/{course}', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{course}/messages/{user_id?}', [ChatController::class, 'fetchMessages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');


    Route::post('/chat/typing', [ChatController::class, 'typing'])->name('chat.typing');
    Route::get('/chat/{course}/typing-status/{receiver?}', [ChatController::class, 'typingStatus']);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update/info', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
    Route::put('/profile/update/email', [ProfileController::class, 'updateEmail'])->name('profile.update.email');
    Route::put('/profile/update/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::put('/profile/update/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');


    Route::delete('/soft-delete/{model}/{id}', [SoftDeleteController::class, 'destroy'])
        ->middleware('auth')
        ->name('soft.delete');
});
