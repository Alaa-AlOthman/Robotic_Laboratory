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
        //
        User::create([
            'name'=>'admin',
            'password'=>Hash::make('admin123'),
            'role'=>'admin'
        ]);

         User::create([
            'name'=>'student',
            'password'=>Hash::make('std12345'),
            'role'=>'user'
        ]);
    }
}
