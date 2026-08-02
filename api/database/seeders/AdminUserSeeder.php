<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@slither.test';
        $password = 'password123';

        User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin',
                'password' => $password,
                'role' => 'admin',
            ]
        );

        $this->command->info("Seeded admin user -> email: {$email} / password: {$password}");
    }
}
