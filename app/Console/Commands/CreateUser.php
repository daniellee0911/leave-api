<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->ask('name');
        $email = $this->ask('email');
        $is_admin = $this->ask('is_admin');
        $password = $this->ask('password');
        $password_confirmation = $this->ask('password_confirmation');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'is_admin' => $is_admin,
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ], [
            'name' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'is_admin' => 'required|boolean',
            'password' => 'required|min:6|string|confirmed',
        ]);

        if($validator->fails()) {
            $this->info('User not created. See error messages below:');

            foreach($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        $validator->errors()->all();

        $user = new User([
            "name" => $name,
            "email" => $email,
            "is_admin" => $is_admin,
            "password" => Hash::make($password)
        ]);
        $user->save();

       

        $this->info('User account created.');
        return 0;

    }
}