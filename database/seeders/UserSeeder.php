<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'phone' => '+37529111111',
            'email' => 'admin@admin.com',
            'password' => Hash::make(123456789),
            'role_id' => 1
        ]);
    }
}
