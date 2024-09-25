<?php

namespace Database\Seeders;

use App\Models\DailyTimeRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyTimeRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DailyTimeRule::upsert([
            ["id" => 1, "work_start_time" => "09:30", "work_end_time" => "18:30"],
        ],["id"]);
    }
}