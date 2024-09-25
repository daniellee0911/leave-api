<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test for login and logout function.
     */
    public function test_login_and_logout_function(): void
    {
       
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);

        // 帳號密碼有誤
        $response = $this->postJson(
            route('auth.login'),
            [
                'email' => "admin@gmail.com",
                'password' => "12345678",
            ]
        );
        $response->assertStatus(401);

        // 帳號密碼正確
        $response = $this->postJson(
            route('auth.login'),
            [
                'email' => "admin@gmail.com",
                'password' => "admin1234",
            ]
        );
        $response->assertStatus(200);
        
        // 登入成功 token 沒有過期
        $response->assertCookieNotExpired('token');
        
        $response = $this->postJson(
            route('auth.logout'),
        );
        $response->assertStatus(200);

        // 登出成功 token 就變過期
        $response->assertCookieExpired('token');

    }
}
