<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_admin_when_env_is_set(): void
    {
        putenv('ADMIN_EMAIL=cliente@empresa.com');
        putenv('ADMIN_PASSWORD=Passw0rd!Demo');
        putenv('ADMIN_NAME=Super Admin');

        try {
            $this->seed(DatabaseSeeder::class);

            $admin = User::where('email', 'cliente@empresa.com')->first();

            $this->assertNotNull($admin);
            $this->assertSame('admin', $admin->rol);
            $this->assertSame('Super Admin', $admin->name);
            $this->assertTrue(Hash::check('Passw0rd!Demo', $admin->password));
        } finally {
            putenv('ADMIN_EMAIL');
            putenv('ADMIN_PASSWORD');
            putenv('ADMIN_NAME');
        }
    }

    public function test_seeder_skips_when_env_is_missing(): void
    {
        putenv('ADMIN_EMAIL');
        putenv('ADMIN_PASSWORD');

        $this->seed(DatabaseSeeder::class);

        $this->assertSame(0, User::count());
    }
}
