<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserRoleSeeder extends Seeder
{

    public function run(): void
    {
        // Crear roles si no existen
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
        ], [
            'description' => 'Administrador del sistema',

            'created_at' => now(),
            'updated_at' => now(),
            'slug' => 'admin',
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'User',
        ], [
            'description' => 'Usuario estándar',

            'created_at' => now(),
            'updated_at' => now(),
            'slug' => 'user',
        ]);

        // Crear usuarios
        $users = [
            [
                'name' => 'Administrador 1',
                'email' => 'admin1@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                "reset_password" => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Administrador 2',
                'email' => 'admin2@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Usuario 1',
                'email' => 'user1@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('user123'),
                'role_id' => $userRole->id,

                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Usuario 2',
                'email' => 'user2@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('user123'),
                'role_id' => $userRole->id,

                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
