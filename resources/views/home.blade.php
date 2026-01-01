@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($courses as $course)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 1rem;">
                        {{-- Gambar Course --}}
                        <img src="{{ $course->thumbnail
                            ? asset('storage/thumbnails/' . $course->thumbnail)
                            : 'https://source.unsplash.com/600x400/?education,learning' }}"
                            class="card-img-top"
                            style="height: 200px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem;"
                            alt="Thumbnail {{ $course->title }}">

                        {{-- Konten --}}
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $course->title }}</h5>
                            <p class="card-text text-muted" style="font-size: 0.9rem;">
                                {{ Str::limit($course->description, 100) }}
                            </p>

                            <div class="d-flex align-items-center mt-3">
                                {{-- Foto Pengajar --}}
                                <img src="{{ $course->teacher->photo
                                    ? asset('storage/photos/' . $course->teacher->photo)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($course->teacher->name) }}"
                                    alt="Guru" class="rounded-circle me-2" width="40" height="40"
                                    style="object-fit: cover;">

                                <div>
                                    <strong>{{ $course->teacher->name }}</strong><br>
                                    <small class="text-muted">Pengajar</small>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Card --}}
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    👨‍🎓 {{ $course->students_count ?? '0' }} siswa
                                </small>
                                <a href="{{ route('course.show', $course->id) }}"
                                    class="btn btn-outline-primary btn-sm rounded-pill">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
