@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">Edit Profil</h3>
        <div class="row row-cols-1 row-cols-md-2 g-4">

            {{-- Edit Name, Address, Phone --}}
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">📝 Edit Data Diri</div>
                    <div class="card-body">
                        <form action="{{ route('profile.update.info') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Alamat</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ old('address', auth()->user()->address) }}">
                            </div>
                            <div class="mb-3">
                                <label>Nomor Telepon</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', auth()->user()->phone) }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Edit Email --}}
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">📧 Ganti Email</div>
                    <div class="card-body">
                        <form action="{{ route('profile.update.email') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label>Email Baru</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                            </div>
                            <button type="submit" class="btn btn-info text-white">Update Email</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">🔐 Ganti Password</div>
                    <div class="card-body">
                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label>Password Lama</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password Baru</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-warning text-dark">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Change Photo --}}
            <div class="col">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">🖼️ Ganti Foto Profil</div>
                    <div class="card-body">
                        <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label>Foto Baru</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                            @if (auth()->user()->photo)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" width="100"
                                        class="rounded">
                                </div>
                            @endif
                            <button type="submit" class="btn btn-secondary">Update Foto</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
