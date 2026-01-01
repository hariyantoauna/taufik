<form action="{{ route('post.store') }}" method="POST">
    @csrf
    {{-- Content --}}
    <div class="mb-3">
        <label class="form-label">Post <span class="text-danger"></span></label>
        <textarea name="post" id="summernote" class="form-control @error('post') is-invalid @enderror" rows="7"
            required>{{ old('post') }}</textarea>

        @error('post')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <input type="hidden" value="{{ $course->id }}" name="course_id">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <button type="submit" class="btn btn-primary">Post</button>
    </div>
</form>