<?php

namespace App\Console\Commands;

use App\Models\DailyTimeRule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateDailyTimeRule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-time-rule:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create daily time rule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $work_start_time = $this->ask('work_start_time');
        $work_end_time = $this->ask('work_end_time');

        $validator = Validator::make([
            'work_start_time' => $work_start_time,
            'work_end_time' => $work_end_time ,
        ], [
            'work_start_time' => 'required|date_format:H:i|before:work_end_time',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
        ]);

        if($validator->fails()) {
            $this->info('The rule is not created. See error messages below:');

            foreach($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        $validator->errors()->all();

        DailyTimeRule::whereNotNull('id')->delete();

        $daily_time_rule = new DailyTimeRule([
            'work_start_time' => $work_start_time,
            'work_end_time' => $work_end_time ,
        ]);
        $daily_time_rule->save();

        $this->info('The rule has been created.');

        return 0;
    }
}