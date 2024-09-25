<?php

namespace Database\Seeders;

use App\Models\Leave;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Leave::upsert([
            ["id" => 1, "name" => "病假"],
            ["id" => 2, "name" => "事假"],
            ["id" => 3, "name" => "特休假"],
            ["id" => 4, "name" => "公假"],
        ],["id"]);

    }
}