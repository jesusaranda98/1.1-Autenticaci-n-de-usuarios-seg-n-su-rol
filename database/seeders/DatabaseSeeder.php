<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jesusarandaaa10@gmail.com'],
            [
                'name' => 'Administrador Principal',
                'password' => Hash::make('Admin1234!'),
                'role' => 'administrador',
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'empleado@empresa.com'],
            [
                'name' => 'Empleado Operativo',
                'password' => Hash::make('Empleado1234!'),
                'role' => 'empleado',
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]
        );
    }
}