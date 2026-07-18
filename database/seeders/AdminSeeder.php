<?php

namespace Database\Seeders;

use App\Enums\AdminRole;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['email' => 'rbmaniya3568@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => 'password',
                'role' => AdminRole::SuperAdmin,
                'is_active' => true,
            ]
        );
    }
}
