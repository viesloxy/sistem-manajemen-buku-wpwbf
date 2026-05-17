<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AntrianAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'antrian1@kantinbuku.com'],
            [
                'name'             => 'Petugas Loket 1',
                'password'         => Hash::make('antrian123'),
                'role'             => 'antrian_admin',
                'is_antrian_admin' => true,
                'id_google'        => null,
                'otp'              => null,
                'avatar'           => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'antrian2@kantinbuku.com'],
            [
                'name'             => 'Petugas Loket 2',
                'password'         => Hash::make('antrian456'),
                'role'             => 'antrian_admin',
                'is_antrian_admin' => true,
                'id_google'        => null,
                'otp'              => null,
                'avatar'           => null,
            ]
        );
    }
}