<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'System Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'is_active'=> true,
        ]);

        $admin->assignRole('admin');
    }
}
