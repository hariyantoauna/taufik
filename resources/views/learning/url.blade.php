@extends('learning.index')

@section('course')
<div class="card mb-4">

    <div class="card-body">
        <div>
            <form action="{{ route('url.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="post" class="form-label">Postingan</label>
                    <textarea class="form-control" name="post" id="post" rows="3" required></textarea>
                </div>

                <input type="hidden" value="{{ $course->id }}" name="course_id">
                <div class="mb-3">
                    <label for="url_url" class="form-label">Url</label>
                    <input type="url" class="form-control" name="url_url" id="url_url">
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary" type="submit">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div>
    <h3 class="mb-4">🕒 Timeline</h3>

    @foreach ($timelines as $item)
    <div class="card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">

            {{-- Header post --}}
            <div class="d-flex align-items-center mb-3">
                <img src="{{ $item->user->photo
                            ? asset('storage/' . $item->user->photo)
                            : 'https://ui-avatars.com/api/?name=' .
                                urlencode($item->user->name) .
                                '&background=random&color=fff&rounded=true&size=160' }}" alt="Foto Pengguna"
                    class="rounded-circle shadow-sm me-3" style="object-fit: cover; height: 50px; width: 50px;">
                <div>
                    <h6 class="mb-0 fw-semibold">{{ $item->user->name ?? 'Pengguna' }}</h6>
                    <small class="text-muted">{{ $item['created_at']->diffForHumans() }}</small>
                </div>


            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                @if (auth()->id() === $item->user_id ||
                auth()->user()->hasAnyRole(['teacher', 'admin']))
                <form action="{{ route('soft.delete', ['model' => 'url', 'id' => $item->id]) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
                @endif

            </div>

            {{-- Post (jika ada) --}}
            @if (!empty($item['post']))
            <p class="mb-3 fs-5 text-dark">{{ $item['post'] }}</p>
            @endif

            {{-- Desain video atau url --}}

            {{-- <div>
                {!! QrCode::size(200)->generate($item->url_url) !!}
            </div> --}}

            @php
            $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($item->url_url));
            @endphp


            <div>
                <img id="qrcode-img" src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            </div>



            <br>

            <a id="download-btn" href="data:image/png;base64,{{ $qrCode }}" download="qrcode.png"
                class="btn btn-primary">
                Download QR Code
            </a>






            <a target="_blank" class="btn btn-success " href="{{ $item->url_url }}">Lihat</a>



            <div class="comment my-4">
                <div class="mb-3">
                    <form action="{{ route('comment.store') }}" method="post">
                        @csrf
                        <label for="message" class="form-label">Komentar</label>
                        <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                        <input type="hidden" name="code" value="{{ $item['code'] }}">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">

                        <div class="my-3 d-grid gap-2 d-md-flex justify-content-md-end">

                            <button class="btn btn-primary" type="submit">Kirim</button>
                        </div>
                    </form>
                </div>

                @foreach ($comments as $comment)
                @if ($comment->code === $item['code'])
                <div class="card border-0 shadow-sm mb-3 mx-2" style="border-radius: 1rem;">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            {{-- Foto Profil --}}
                            <img src="{{ $comment->user->photo
                                                ? asset('storage/' . $comment->user->photo)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}"
                                alt="Foto" class="rounded-circle me-3" width="48" height="48"
                                style="object-fit: cover;">

                            <div class="w-100">
                                {{-- Nama dan Tanggal --}}
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0 fw-semibold">{{ $comment->user->name }}</h6>
                                        <small class="text-muted">{{ $comment->created_at->format('d M Y, H:i')
                                            }}</small>
                                    </div>
                                    {{-- Optional tombol aksi seperti edit/hapus bisa ditaruh di sini --}}
                                </div>

                                {{-- Isi Komentar --}}
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
    </div>
    @endforeach
</div>
@endsection
