@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">📝 Buat Course Baru</h2>

        <form action="{{ route('course.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title">Judul Course</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="level">Level</label>
                <input type="text" name="level" class="form-control">
            </div>

            <div class="mb-3">
                <label for="learning_tools">Perangkat Pembelajaran (URL PDF)</label>
                <textarea name="learning_tools" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('course.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
