<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Closure;

class UpdateUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user password';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->ask('id');
        $password = $this->ask('password');
        $password_confirmation = $this->ask('password_confirmation');

        $validator = Validator::make([
            'id' => $id,
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ], [
            'id' => [function (string $attribute, mixed $value, Closure $fail) {
                if(User::find($value)===null) {
                    $fail("The {$attribute} is invalid.");
                }
            },],
            'password' => 'required|min:6|string|confirmed',
        ]);

        if($validator->fails()) {
            $this->info('The user password is not updated. See error messages below:');

            foreach($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        $validator->errors()->all();

        User::where('id', $id)->first()->update([
            'password' => Hash::make($password),
        ]);

        $this->info('The user password has been updated.');

        return 0;
    }
}
