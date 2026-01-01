@extends('layouts.app')

@section('content')



<section class="container py-4">
    <div class="row">
        {{-- SIDEBAR PROFIL --}}
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 text-center" style="border-radius: 1rem;">
                <div class="card-body p-4">
                    <img src="{{ Auth::user()->photo
                            ? asset('storage/' . Auth::user()->photo)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" alt="Foto Profil"
                        class="rounded-circle mb-3 shadow" width="90" height="90" style="object-fit: cover;">

                    <h5 class="fw-bold mb-1">{{ Auth::user()->name }}</h5>
                    <p class="text-muted mb-2" style="font-size: 0.9rem;">{{ Auth::user()->email }}</p>
                    {{-- Tambahan Info Lainnya --}}
                    <span class="badge bg-primary-soft text-primary">Siswa</span>

                    {{-- Tombol Edit Profil --}}
                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            {{-- Navigasi Tab --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-body p-3">
                    <ul class="nav nav-tabs flex-wrap">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home.index') ? 'active' : '' }}"
                                href="{{ route('home.index', $course->id) }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('post.index') ? 'active' : '' }}"
                                href="{{ route('post.index', $course->id) }}">Postingan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('canva.index') ? 'active' : '' }}"
                                href="{{ route('canva.index', $course->id) }}">Canva</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('video.index') ? 'active' : '' }}"
                                href="{{ route('video.index', $course->id) }}">Video</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('pdf.index') ? 'active' : '' }}"
                                href="{{ route('pdf.index', $course->id) }}">PDF</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('url.index') ? 'active' : '' }}"
                                href="{{ route('url.index', $course->id) }}">URL</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('chat.index') ? 'active' : '' }}"
                                href="{{ route('chat.index', $course->id) }}">Chat</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#">Chat AI</a>
                        </li> --}}
                    </ul>
                </div>
            </div>

            {{-- Tempat konten dimasukkan --}}
            @yield('course')
        </div>
    </div>
</section>
@endsection