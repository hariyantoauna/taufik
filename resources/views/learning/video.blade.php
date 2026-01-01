@extends('learning.index')

@section('course')
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('video.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="post" class="form-label">Postingan</label>
                <textarea class="form-control" name="post" id="post" rows="3" required></textarea>
            </div>

            <input type="hidden" value="{{ $course->id }}" name="course_id">

            <div class="mb-3">
                <label for="video_url" class="form-label">URL Video (YouTube)</label>
                <input type="url" class="form-control" name="video_url" id="video_url"
                    placeholder="https://www.youtube.com/watch?v=xxxxx" required>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Post</button>
            </div>
        </form>
    </div>
</div>

<h3 class="mb-4">🕒 Timeline</h3>

@if ($timelines->count() > 0)
@foreach ($timelines as $item)
<div class="card mb-4 shadow-sm border-0 rounded-4">
    <div class="card-body">
        {{-- Header post --}}
        <div class="d-flex align-items-center mb-3">
            <img src="{{ $item->user->photo
                        ? asset('storage/' . $item->user->photo)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->name) . '&background=random&color=fff&rounded=true&size=160' }}"
                alt="Foto Pengguna" class="rounded-circle shadow-sm me-3"
                style="object-fit: cover; height: 50px; width: 50px;">
            <div>
                <h6 class="mb-0 fw-semibold">{{ $item->user->name ?? 'Pengguna' }}</h6>
                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
            </div>
        </div>

        {{-- Tombol hapus (admin/guru/pemilik) --}}
        @if (auth()->id() === $item->user_id || auth()->user()->hasAnyRole(['teacher', 'admin']))
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <form action="{{ route('soft.delete', ['model' => 'video', 'id' => $item->id]) }}" method="POST"
                onsubmit="return confirm('Yakin hapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
            </form>
        </div>
        @endif

        {{-- Post --}}
        @if (!empty($item->post))
        <p class="mb-3 fs-5 text-dark">{{ $item->post }}</p>
        @endif

        {{-- Video --}}
        @if (!empty($item->video_url))
        <div class="ratio ratio-16x9 rounded overflow-hidden mb-3">
            <iframe
                src="{{ Str::contains($item->video_url, 'embed') ? $item->video_url : 'https://www.youtube.com/embed/' . \Illuminate\Support\Str::after($item->video_url, 'v=') }}"
                frameborder="0" allowfullscreen>
            </iframe>
        </div>
        @endif

        {{-- Form komentar --}}
        <form action="{{ route('comment.store') }}" method="POST" class="mb-3">
            @csrf
            <label for="message_{{ $item->id }}" class="form-label">Komentar</label>
            <textarea class="form-control" name="message" id="message_{{ $item->id }}" rows="3"></textarea>
            <input type="hidden" name="code" value="{{ $item->code }}">
            <input type="hidden" name="course_id" value="{{ $course->id }}">
            <div class="my-3 d-grid gap-2 d-md-flex justify-content-md-end">
                <button class="btn btn-primary" type="submit">Kirim</button>
            </div>
        </form>

        {{-- Daftar komentar --}}
        @foreach ($comments as $comment)
        @if ($comment->code === $item->code)
        <div class="card border-0 shadow-sm mb-3 mx-2" style="border-radius: 1rem;">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <img src="{{ $comment->user->photo
                                        ? asset('storage/' . $comment->user->photo)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                        alt="Foto" class="rounded-circle me-3" width="48" height="48" style="object-fit: cover;">
                    <div class="w-100">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-semibold">{{ $comment->user->name }}</h6>
                                <small class="text-muted">{{ $comment->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        <p class="mt-2 mb-0 text-dark" style="font-size: 0.95rem;">
                            {{ $comment->message }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

    </div>
</div>
@endforeach
@else
<p class="text-muted text-center">Belum ada postingan video.</p>
@endif
@endsection