@extends('layouts.app')

@section('content')
    <div class="container">

        {{-- Flash Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>✅ Sukses!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                <strong>ℹ️ Info:</strong> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <h2 class="mb-4 fw-bold text-dark">📘 Detail Kursus</h2>

        <div class="card shadow-sm border-0 mb-4" style="background-color: #f9fafb;">
            <div class="card-body">

                <h3 class="card-title text-dark">
                    {{ $course->title }}


                    @if ($course->is_published)
                        <span class="badge rounded-pill bg-success ms-2">Published</span>
                    @else
                        <span class="badge rounded-pill bg-secondary ms-2">Draft</span>
                    @endif
                </h3>



                <div class="p-4 rounded-3 border bg-light mb-4 shadow-sm">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $course->user->photo
                            ? asset('storage/' . $course->user->photo)
                            : 'https://ui-avatars.com/api/?name=' .
                                urlencode($course->user->name) .
                                '&background=random&color=fff&rounded=true&size=160' }}"
                            alt="Foto {{ $course->user->name }}" class="rounded-circle shadow-sm me-3" width="80"
                            height="80">

                        <div>
                            <h5 class="mb-0">{{ $course->user->name }}</h5>
                            <small class="text-muted">👤 Pembuat Kursus</small>
                        </div>
                    </div>

                    <div class="row text-dark">
                        <div class="col-md-6 mb-2">
                            <strong>🎓 Level:</strong> {{ $course->level ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">

                            <a class="btn btn-sm btn-warning" href="{{ $course->learning_tools }}"> <strong>Perangkat
                                    Pembelajaran</strong></a>

                        </div>
                    </div>
                </div>

                {{-- Switch Publikasi --}}



                @hasanyrole('admin|teacher|developer')
                    <form method="POST" action="{{ route('course.togglePublish', $course->id) }}" id="publishForm">
                        @csrf
                        @method('PATCH')
                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="is_published" id="switch_{{ $course->id }}"
                                {{ $course->is_published ? 'checked' : '' }} onchange="confirmPublish(event)">
                            <label class="form-check-label fw-semibold text-dark" for="switch_{{ $course->id }}">
                                {{ $course->is_published ? 'Telah Dipublikasikan' : 'Belum Dipublikasikan' }}
                            </label>
                        </div>
                    </form>
                @endhasanyrole



                <div class="d-flex justify-content-between flex-wrap align-items-center">
                    {{-- Ambil Kursus / Ikuti --}}
                    <div>
                        @if (!$order)
                            @if ($course->is_published)
                                <form action="{{ route('course.order', $course->id) }}" method="POST" id="ambilForm">
                                    @csrf
                                    <button type="button" class="btn btn-warning px-4 py-2 fw-semibold"
                                        onclick="confirmAmbil()">
                                        📥 Ambil Kursus Ini
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    Kursus ini belum dipublikasikan. Silakan tunggu sampai kursus tersedia untuk diambil.
                                </div>
                            @endif
                        @else
                            @if ($order->status == 1)
                                <a href="{{ route('post.index', $course->id) }}"
                                    class="btn btn-success px-4 py-2 fw-semibold">
                                    ✅ Ikuti Kursus
                                </a>
                            @else
                                <div class="alert alert-warning d-flex align-items-center gap-2 mt-3" role="alert">
                                    <i class="bi bi-hourglass-split"></i>
                                    <div>
                                        Permohonan Anda sedang <strong>menunggu verifikasi</strong> dari admin. Harap
                                        bersabar.
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- Tombol Aksi Admin --}}
                    <div class="mt-3 mt-md-0">
                        @hasanyrole('admin|teacher|developer')
                            <a href="{{ route('course.edit', $course->id) }}"
                                class="btn btn-primary px-4 py-2 fw-semibold me-2">
                                ✏️ Edit
                            </a>
                        @endhasanyrole

                        @hasanyrole('admin|developer')
                            <form action="{{ route('course.destroy', $course->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Yakin ingin menghapus kursus ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger px-4 py-2 fw-semibold">🗑️ Hapus</button>
                            </form>
                        @endhasanyrole

                    </div>
                </div>
            </div>
        </div>


    </div>


    {{-- === TABEL PESERTA === --}}


    @hasanyrole('admin|teacher|developer')
        <section class="container my-4">
            {{-- Peserta Kursus --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <h5 class="mb-4 fw-semibold">👥 Peserta Kursus</h5>
                    {{-- Tombol Lihat Pemohon --}}
                    <div class="d-flex justify-content-end mb-4">
                        <button class="btn btn-dark position-relative" data-bs-toggle="modal" data-bs-target="#pemohonModal">
                            🔍 Lihat Pemohon Kursus
                            @if ($applicants->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $applicants->count() }}
                                </span>
                            @endif
                        </button>
                    </div>
                    @if ($participants->isEmpty())
                        <p class="text-muted">Belum ada peserta.</p>
                    @else
                        <form action="{{ route('course.revoke', $course->id) }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table align-middle table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Pilih untuk Ditangguhkan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($participants as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $order->user->name }}</td>
                                                <td>{{ $order->user->email }}</td>
                                                <td>
                                                    <input type="checkbox" name="revoke_ids[]" value="{{ $order->id }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-danger mt-2">🚫 Tangguhkan Peserta Terpilih</button>
                        </form>
                    @endif
                </div>
            </div>
            <a href="{{ route('course.index') }}" class="btn btn-secondary px-4 py-2 fw-semibold">⬅️ Kembali</a>



            {{-- === MODAL: Pemohon === --}}
            <div class="modal fade" id="pemohonModal" tabindex="-1" aria-labelledby="pemohonModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <form action="{{ route('course.approve', $course->id) }}" method="POST">
                        @csrf
                        <div class="modal-content shadow-sm">
                            <div class="modal-header bg-light border-bottom">
                                <h5 class="modal-title fw-semibold" id="pemohonModalLabel">📋 Daftar Pemohon Kursus</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                @if ($applicants->isEmpty())
                                    <p class="text-muted">Tidak ada pemohon saat ini.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table align-middle table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Setujui</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($applicants as $index => $order)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $order->user->name }}</td>
                                                        <td>{{ $order->user->email }}</td>
                                                        <td>
                                                            <input type="checkbox" name="approve_ids[]"
                                                                value="{{ $order->id }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success">✔️ Setujui Pemohon Terpilih</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    @endhasanyrole


@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmPublish(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Publikasi',
                text: "Yakin ingin mengubah status publikasi kursus ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#999',
                confirmButtonText: 'Ya, Publikasikan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('publishForm').submit();
                } else {
                    document.getElementById('switch_{{ $course->id }}').checked = !document.getElementById(
                        'switch_{{ $course->id }}').checked;
                }
            });
        }

        function confirmAmbil() {
            Swal.fire({
                title: 'Ambil Kursus?',
                text: "Anda akan mengambil kursus ini. Lanjutkan?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#999',
                confirmButtonText: 'Ya, Ambil',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('ambilForm').submit();
                }
            });
        }
    </script>
@endsection
