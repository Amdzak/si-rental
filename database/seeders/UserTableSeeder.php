<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'username' => 'Admin',
                'password' => Hash::make('admin2001'),
                'email_verified_at' => now(),
                'role' => 1,
            ],
            [
                'name' => 'Pegawai 01',
                'username' => 'pegawai2001',
                'password' => Hash::make('pegawai2001'),
                'email_verified_at' => now(),
                'role' => 2,
            ],
        ];

        foreach ($users as $value) {
            User::create($value);
        }
    }
}
