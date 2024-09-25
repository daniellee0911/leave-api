<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // 列出所有使用者
    public function index(Request $request){

        $users = cache()->rememberForever('users-all:page-' . request('page', default:1), function(){
            return User::paginate(10);
        });

        cache()->forever('users-all-last-page', $users->lastPage());

        return response($users, 200);
    }

    // 列出特定使用者
    public function show(Request $request,$user_id){

        if(User::find($user_id)===null){
            return response(['message' => "The id has not been found"], 404);
        }

        $user = cache()->rememberForever('users-info-id:' . $user_id, function() use($user_id){
            return User::with(['profile'])->find($user_id);
        });

        return response($user, 200);
    }
    
    // 建立新的使用者
    public function create(Request $request)
    {
        request()->validate([
            'name' => "required|string|unique:users",
            'email' => "required|string|email|unique:users",
            'password' => "required|confirmed|min:6|string",
            'is_admin' => "required|boolean",
        ]);


        $user = new User([
            'name' => request()->name,
            'email' => request()->email,
            'password' => Hash::make(request()->password),
            'is_admin' => request()->is_admin,
        ]);
        $user->save();
        return response(['message' => "Success"], 201);
    }

    // 更新使用者的額外資訊
    public function updateProfile(Request $request,$user_id){
        
        if(User::find($user_id)===null){
            return response(['message' => "The id has not been found"], 404);
        }

        $validator = Validator::make([
           'birthday_date' => request()->birthday_date,
           'entry_date' => request()->entry_date,
        ], [
           'birthday_date' => "required|date_format:Y-m-d",
           'entry_date' => "required|date_format:Y-m-d",
        ]);
        $validator->validate();


        $user_profile = UserProfile::where('user_id',$user_id)->first();
        if($user_profile){
            $user_profile->update([
                'birthday_date' => request()->birthday_date,
                'entry_date' => request()->entry_date,
            ]);
        }else{
            $user_profile = new userProfile([
                'user_id' => $user_id,
                'birthday_date' => request()->birthday_date,
                'entry_date' => request()->entry_date,
            ]);
            $user_profile->save();
        }

        
        return response(['message' => "Success"],200);
    }

    //更新使用者的email 
    public function updateEmail(Request $request,$user_id){

        if(User::find($user_id)===null){
            return response(['message' => "The id has not been found"], 404);
        }

        $validator = Validator::make([
           'email' => request()->email,
        ], [
           'email' => "required|string|email|unique:users",
        ]);
        $validator->validate();

        User::where('id', $user_id)->first()->update([
            'email' => request()->email
        ]);

        return response(['message' => "Success"],200);
    }

    //更新使用者的password
    public function updatePassword(Request $request,$user_id){

        if(User::find($user_id)===null){
            return response(['message' => "The id has not been found"], 404);
        }

        $validator = Validator::make([
            'password' => request()->password,
            'password_confirmation' =>  request()->password_confirmation,
        ], [
            'password' => "required|min:6|string|confirmed",
        ]);
        $validator->validate();
 
        User::where('id', $user_id)->first()->update([
            'password' => Hash::make(request()->password),
        ]);
 
        return response(['message' => "Success"],200);  
    }

    // 刪除使用者
    public function delete(Request $request,$user_id){

        $user = auth()->user();
        
        if(User::find($user_id)===null){
            return response(['message' => "The id has not been found"], 404);
        }
        
        // 不能自己扇自己
        if(intval($user_id)===$user->id){
            return response(['message' => "Cannot delete yourself"], 403);
        }

        DB::beginTransaction();
        
        try{      
            $user_profile = UserProfile::where('user_id',$user_id)->first();
            $user = User::where('id', $user_id)->first();
    
            if($user_profile) $user_profile->delete();
            if($user) $user->delete();
            
        }catch(\Throwable $th){
            
            DB::rollBack();
        }

        DB::commit();

        return response(['message' => "Success"],204);  
    }
}
