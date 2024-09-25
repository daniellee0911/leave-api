<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    // 使用email和password登入,登入成功後加入cookie
    public function login(Request $request)
    {
        $validatedData = request()->validate([
            'email' => "required|string|email",
            'password' => "required|string",
        ]);

        if(!auth()->attempt($validatedData)){
            return response(['message' => "email or password is invalid."], 401);
        }

        request()->user()->tokens()->delete();
        $user = request()->user();

        $token = $user->createToken(
            'token',                    // The name of the token
            ['*'],                      // Whatever abilities you want 
            Carbon::now()->addHours(9)  // The expiration date
        )->plainTextToken;
        
        $cookie = cookie('token', $token, 60 * 9); // 9 hr

        return response(['message' => "Successfully logged in"],200)
                ->withCookie($cookie);
    }    
   
    // 登出成功後刪掉cookie
    public function logout(Request $request)
    {
        $cookie = cookie()->forget('token');
        request()->user()->tokens()->delete();

        return response([
            'message' => "Successfully logged out"
        ],200)->withCookie($cookie);
    }

    // 取得個人資訊
    public function user(Request $request)
    {
        $user = auth()->user();

        $user = cache()->rememberForever('users-info-id:' . $user->id, function() use($user){
            return User::with(['profile'])->find($user->id);
        });
        

        return response($user, 200);
    }
}