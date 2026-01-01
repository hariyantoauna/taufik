<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'teacher']);
        Role::create(['name' => 'editor']);
        Role::create(['name' => 'operator']);
        Role::create(['name' => 'depolover']);
        // User::factory(10)->create();

        $depolover = User::create([
            'name' => 'Hariyanto S. Auna',
            'email' => 'hariyantosauna@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $depolover->syncRoles(['admin', 'editor', 'teacher', 'editor', 'operator', 'depolover']);

        // $teacher_admin = User::create([
        //     'name' => 'Hariyanto S. Auna',
        //     'email' => 'hariyantosauna@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('mabes2025'),
        //     'remember_token' => Str::random(10),
        // ]);

        // $teacher_admin->syncRoles(['admin', 'teacher', 'editor',  'depolover']);

        $guru = User::create([
            'name' => 'Akun Guru',
            'email' => 'guru@contoh.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);

        $guru->syncRoles(['admin', 'teacher']);

        $siswa = User::create([
            'name' => 'Akun Siswa',
            'email' => 'siswa@contoh.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'remember_token' => Str::random(10),
        ]);
    }
}
