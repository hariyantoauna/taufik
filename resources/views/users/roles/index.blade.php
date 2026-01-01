@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Pengaturan Role Pengguna</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <form method="POST" action="{{ route('users.roles.update', $user) }}">
                            @csrf
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                            {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}>
                                        <label>{{ ucfirst($role->name) }}</label>
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
