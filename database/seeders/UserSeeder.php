<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
           'first_name' => 'Admin',
           'last_name' => 'User',
           'email' => 'admin@gmail.com',
           'role' => 1,
           'password' => Hash::make('password'),
       ]);

       User::create([
           'first_name' => 'Sajid',
           'last_name' => 'Beauty',
           'email' => 'sajidbeautybd@gmail.com',
           'role' => 2,
           'password' => Hash::make('password'),
       ]);
    }
}
