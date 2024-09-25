<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use App\Models\Leave;


class CreateLeave extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new leave';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('name');
        

        $validator = Validator::make([
            'name' => $name,
        ], [
            'name' => 'required|string|unique:leaves',
        ]);

        if($validator->fails()) {
            $this->info('Leave not created. See error messages below:');

            foreach($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        $validator->errors()->all();

        $leave = new Leave([
            "name" => $name,
        ]);
        $leave->save();

        $this->info('Leave created.');

        return 0;

    }
}