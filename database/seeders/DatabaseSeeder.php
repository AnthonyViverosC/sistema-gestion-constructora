<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        $name = env('ADMIN_NAME', 'Administrador');

        if (! $email || ! $password) {
            $this->command?->warn('No se creó usuario admin: define ADMIN_EMAIL y ADMIN_PASSWORD en el .env antes de correr db:seed.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name'              => $name,
                'password'          => Hash::make($password),
                'rol'               => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command?->info("Usuario admin listo: {$email}");
    }
}
