@extends('layouts.app')

@section('content')
<section class="container">
    <form method="POST" action="" enctype="multipart/form-data">
        @csrf

        {{-- Textarea --}}
        <div class="mb-3">
            <label for="editor" class="form-label">
                Tulis Postingan</label>
            <textarea class="form-control" name="content" id="editor" rows="5"></textarea>
        </div>

        {{-- Tombol Upload --}}
        <div class="mb-3 d-flex gap-2 flex-wrap">
            <!-- Upload Gambar -->
            <label class="btn btn-outline-primary">
                <i class="bi bi-image"></i> Gambar
                <input type="file" id="imageInput" name="image" accept="image/*" hidden>
            </label>

            <!-- Upload Video -->
            <label class="btn btn-outline-success">
                <i class="bi bi-camera-video"></i> Video
                <input type="file" id="videoInput" name="video" accept="video/*" hidden>
            </label>

            <!-- Upload PDF -->
            <label class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf"></i> PDF
                <input type="file" id="pdfInput" name="pdf" accept="application/pdf" hidden>
            </label>
        </div>

        {{-- Preview Area --}}
        <div id="previewArea" class="mb-4"></div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-send"></i> Kirim
        </button>
    </form>
</section>
{{-- CKEditor (opsional) --}}
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script> --}}

{{-- Preview File JS --}}
<script>
    function previewFile(input, type) {
        const file = input.files[0];
        const previewArea = document.getElementById('previewArea');
        previewArea.innerHTML = ''; // Kosongkan area sebelumnya

        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            let previewElement;

            if (type === 'image') {
                previewElement =
                    `<img src="${e.target.result}" alt="Preview" class="img-fluid rounded" >`;
            } else if (type === 'video') {
                previewElement = `<video controls class="w-100" style="max-height: 300px;">
                                        <source src="${e.target.result}" type="${file.type}">
                                      </video>`;
            } else if (type === 'pdf') {
                previewElement =
                    `<iframe src="${e.target.result}" width="100%" height="400px" class="border rounded"></iframe>`;
            }

            previewArea.innerHTML = previewElement;
        };

        reader.readAsDataURL(file);
    }

    document.getElementById('imageInput').addEventListener('change', function() {
        previewFile(this, 'image');
    });

    document.getElementById('videoInput').addEventListener('change', function() {
        previewFile(this, 'video');
    });

    document.getElementById('pdfInput').addEventListener('change', function() {
        previewFile(this, 'pdf');
    });
</script>
@endsection