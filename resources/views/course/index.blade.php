@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">📚 Daftar Course</h2>
        @hasanyrole('admin|teacher|developer')
            <a href="{{ route('course.create') }}" class="btn btn-primary mb-4">+ Buat Course Baru</a>
        @endhasanyrole


        <div class="row">
            @forelse($courses as $course)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $course->user->photo
                                    ? asset('storage/' . $course->user->photo)
                                    : 'https://ui-avatars.com/api/?name=' .
                                        urlencode($course->user->name) .
                                        '&background=random&color=fff&rounded=true&size=160' }}"
                                    alt="Foto {{ $course->user->name }}" class="rounded-circle me-3 shadow" width="50"
                                    height="50">
                                <div>
                                    <h6 class="mb-0">{{ $course->user->name }}</h6>
                                    <small class="text-muted">Pembuat Kursus</small>
                                </div>
                            </div>

                            <h5 class="card-title mb-2 text-primary fw-semibold">{{ $course->title }}</h5>
                            <p class="mb-1"><strong>🎓 Level:</strong> {{ $course->level ?? '-' }}</p>
                            <p class="mb-2"><strong>📅 Dibuat:</strong> {{ $course->created_at->format('d M Y') }}</p>

                            @if ($course->learning_tools)
                                <p class="mb-2"><strong>🧰 Tools:</strong> {{ $course->learning_tools }}</p>
                            @endif

                            <a href="{{ route('course.show', $course->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                🔍 Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">Belum ada course.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
