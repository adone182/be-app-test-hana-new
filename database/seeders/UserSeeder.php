<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'user_email' => 'doe@mail.id',
            'user_fullname' => 'John Doe',
            'user_password' => Hash::make('password123'),
            'user_status' => 1,
        ]);
    }
}

