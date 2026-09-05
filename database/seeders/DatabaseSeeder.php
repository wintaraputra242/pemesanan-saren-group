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
        $this->call(ShieldSeeder::class);

        $admin = User::updateOrCreate(
            ['email' => config('app.admin_email')],
            [
                'name' => 'Admin Saren Grup',
                'password' => bcrypt(config('app.admin_password')),
                'email_verified_at' => now(),
            ],
        );
        $admin->assignRole('super_admin');

        $this->call(ProductSeeder::class);
    }
}
