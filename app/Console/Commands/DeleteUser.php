<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserProfile;
use Closure;
use Illuminate\Support\Facades\Validator;

class DeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->ask('id');
        $validator = Validator::make([
            'id' => $id,
        ], [
            'id' => [function (string $attribute, mixed $value, Closure $fail) {
                if(User::find($value)===null) {
                    $fail("The {$attribute} is invalid.");
                }
            },],
        ]);

        if($validator->fails()) {
            $this->info('The user is not deleted. See error messages below:');

            foreach($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }
        $validator->errors()->all();


        $user_profile = UserProfile::where('user_id',$id)->first();
        $user = User::where('id', $id)->first();
        if($user_profile) $user_profile->delete();
        if($user) $user->delete();

        
        $this->info('The user has deleted');

        return 0;
    }
}
