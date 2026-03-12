<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123123'),
        ]);
        $user->profile()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'bio' => 'This is the admin user.',
            'birth_date' => '1990-01-01',
            'gender' => 'male',
        ]);
    }
}
