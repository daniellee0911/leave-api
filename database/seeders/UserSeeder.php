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
        User::upsert([
            ["id" => 1, "name" => "admin1", "email" => "admin1@gmail.com","is_admin" => 1,"password" => Hash::make("admin1234")],
            ["id" => 2, "name" => "admin2", "email" => "admin2@gmail.com","is_admin" => 1,"password" => Hash::make("admin5678")],
            ["id" => 3, "name" => "test1", "email" => "test1@gmail.com","is_admin" => 0,"password" => Hash::make("test1234")],
            ["id" => 4, "name" => "test2", "email" => "test2@gmail.com","is_admin" => 0,"password" => Hash::make("test5678")],
        ],["id"]);
    }
}
