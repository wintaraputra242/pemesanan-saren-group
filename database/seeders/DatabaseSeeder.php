<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(ShieldSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => 'admin@sarengroup.test'],
            [
                'name' => 'Admin Saren Grup',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
        );
        $admin->assignRole('super_admin');

        $this->call(ProductSeeder::class);
    }
}
