@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">✏️ Edit Course</h2>

        <form action="{{ route('course.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title">Judul Course</label>
                <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
            </div>

            <div class="mb-3">
                <label for="level">Level</label>
                <input type="text" name="level" class="form-control" value="{{ $course->level }}">
            </div>

            <div class="mb-3">
                <label for="learning_tools">Perangkat Pembelajaran (URL PDF)</label>
                <textarea name="learning_tools" class="form-control" rows="3">{{ $course->learning_tools }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('course.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
