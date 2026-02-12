<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         User::create([
        'username' => 'Admin',
        'name' => 'Admin',
        'email' => 'admin@arisan.test',
        'password' => Hash::make('123456'),
        'role' => 'admin'
        ]);
        
    }
}
