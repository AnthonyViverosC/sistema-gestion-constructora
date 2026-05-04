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

    private const ADMIN_KEYS = ['ADMIN_EMAIL', 'ADMIN_PASSWORD', 'ADMIN_NAME'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->clearAdminEnv();
    }

    protected function tearDown(): void
    {
        $this->clearAdminEnv();
        parent::tearDown();
    }

    public function test_seeder_creates_admin_when_env_is_set(): void
    {
        $this->setEnv('ADMIN_EMAIL', 'cliente@empresa.com');
        $this->setEnv('ADMIN_PASSWORD', 'Passw0rd!Demo');
        $this->setEnv('ADMIN_NAME', 'Super Admin');

        $this->seed(DatabaseSeeder::class);

        $admin = User::where('email', 'cliente@empresa.com')->first();

        $this->assertNotNull($admin);
        $this->assertSame('admin', $admin->rol);
        $this->assertSame('Super Admin', $admin->name);
        $this->assertTrue(Hash::check('Passw0rd!Demo', $admin->password));
    }

    public function test_seeder_skips_when_env_is_missing(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(0, User::count());
    }

    private function clearAdminEnv(): void
    {
        foreach (self::ADMIN_KEYS as $key) {
            unset($_ENV[$key], $_SERVER[$key]);
            putenv($key);
        }
    }

    private function setEnv(string $key, string $value): void
    {
        $_ENV[$key]    = $value;
        $_SERVER[$key] = $value;
        putenv("{$key}={$value}");
    }
}
