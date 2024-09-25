<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test for index function by the admin user.
     */
    public function test_index_function_by_admin_user(): void
    {
        // cache 測試
        $this->assertNull(cache()->get('users-all:page-1'));
        $this->assertNull(cache()->get('users-all-last-page'));

        // 未登入就執行
        $response = $this->getJson(
            route('users.index')
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->getJson(
            route('users.index')
        );
        $response->assertStatus(200);

        // cache 測試
        $this->assertNotNull(cache()->get('users-all:page-1'));
        $this->assertNotNull(cache()->get('users-all-last-page'));
    }

    /**
     * A basic feature test for index function by the normal user.
     */
    public function test_index_function_by_normal_user(): void
    {
        // 未登入就執行
        $response = $this->getJson(
            route('users.index')
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        Sanctum::actingAs($loginUser);
        
        // 登入後執行
        $response = $this->getJson(
            route('users.index')
        );
        $response->assertStatus(403);
    }

    /**
     * A basic feature test for show function by the admin user.
     */
    public function test_show_function_by_admin_user(): void
    { 
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->getJson(
            route('users.show',$user->id)
        );
        $response->assertStatus(401);

        // cache 測試
        $this->assertNull(cache()->get('users-info-id:' .$user->id));

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行  
        $response = $this->getJson(
            route('users.show',$user->id)
        );
        $response->assertStatus(200);

        // cache 測試
        $this->assertNotNull(cache()->get('users-info-id:' .$user->id));

        // 用不存在的user id 執行
        $response = $this->getJson(
            route('users.show',$user->id+100)
        );
        $response->assertStatus(404);
    }

    /**
     * A basic feature test for show function by the normal user.
     */
    public function test_show_function_by_normal_user(): void
    { 
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->getJson(
            route('users.show',$user->id)
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->getJson(
            route('users.show',$user->id)
        );
        $response->assertStatus(403);
    }

    /**
     * A basic feature test for create function by the admin user.
     */
    public function test_create_function_by_admin_user(): void
    {
        // 未登入就執行
        $response = $this->postJson(
            route('users.create'),
            [
                'name' => 'test1',
                'email' => 'test1@gmail.com',
                'is_admin' => 0,
                'password' => 'test1234',
                'password_confirmation' => 'test1234',
            ]
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->postJson(
            route('users.create'),
            [
                'name' => 'test1',
                'email' => 'test1@gmail.com',
                'is_admin' => 0,
                'password' => 'test1234',
                'password_confirmation' => 'test1234',
            ]
        );
        $response->assertStatus(201);
    }

    /**
     * A basic feature test for create function by the normal user.
     */
    public function test_create_function_by_normal_user(): void
    {
        // 未登入就執行
        $response = $this->postJson(
            route('users.create'),
            [
                'name' => 'test1',
                'eamil' => 'test1@gmail.com',
                'is_admin' => 0,
                'password' => 'test1234',
                'password_confirmation' => 'test1234',
            ]
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->postJson(
            route('users.create'),
            [
                'name' => 'test1',
                'eamil' => 'test1@gmail.com',
                'is_admin' => 0,
                'password' => 'test1234',
                'password_confirmation' => 'test1234',
            ]
        );
        $response->assertStatus(403);
    }
    /**
     * A basic feature test for updateProfile function by the admin user.
     */
    public function test_update_profile_function_by_admin_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->putJson(
            route('users.update.profile',$user->id),
            [
                'birthday_date' => '1999-09-09',
                'entry_date' => '2024-07-07',
            ]
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->putJson(
            route('users.update.profile',$user->id),
            [
                'birthday_date' => '1999-09-09',
                'entry_date' => '2024-07-07',
            ]
        );
        $response->assertStatus(200);
        
        // 用不存在的user id 執行
        $response = $this->putJson(
            route('users.update.profile',$user->id+100),
            [
                'birthday_date' => '1999-09-09',
                'entry_date' => '2024-07-07',
            ]
        );
        $response->assertStatus(404);
    }

    /**
     * A basic feature test for updateProfile function by the normal user.
     */
    public function test_update_profile_function_by_normal_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->putJson(
            route('users.update.profile',$user->id),
            [
                'birthday_date' => '1999-09-09',
                'entry_date' => '2024-07-07',
            ]
        );
        $response->assertStatus(401);
        
        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->putJson(
            route('users.update.profile',$user->id),
            [
                'birthday_date' => '1999-09-09',
                'entry_date' => '2024-07-07',
            ]
        );
        $response->assertStatus(403);
    }

    /**
     * A basic feature test for updateEmail function by the admin user.
     */
    public function test_update_email_function_by_admin_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->putJson(
            route('users.update.email',$user->id),
            [
                'email' => 'test2@gmail.com',
            ]
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->putJson(
            route('users.update.email',$user->id),
            [
                'email' => 'test2@gmail.com',
            ]
        );
        $response->assertStatus(200);

        // 用不存在的user id 執行
        $response = $this->putJson(
            route('users.update.email',$user->id+100),
            [
                'email' => 'test2@gmail.com',
            ]
        );
        $response->assertStatus(404);
    }

    /**
     * A basic feature test for updateEmail function by the normal user.
     */
    public function test_update_email_function_by_normal_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->putJson(
            route('users.update.email',$user->id),
            [
                'email' => 'test2@gmail.com',
            ]
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->putJson(
            route('users.update.email',$user->id),
            [
                'email' => 'test2@gmail.com',
            ]
        );
        $response->assertStatus(403);
    }

    /**
     * A basic feature test for updatePassword function by the admin user.
     */
    public function test_update_password_function_by_admin_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->putJson(
            route('users.update.password',$user->id),
            [
                'password' => 'test5678',
                'password_confirmation' => 'test5678',
            ]
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->putJson(
            route('users.update.password',$user->id),
            [
                'password' => 'test5678',
                'password_confirmation' => 'test5678',
            ]
        );
        $response->assertStatus(200);

        // 用不存在的user id 執行
        $response = $this->putJson(
            route('users.update.password',$user->id+100),
            [
                'password' => 'test5678',
                'password_confirmation' => 'test5678',
            ]
        );
        $response->assertStatus(404);
    }

    /**
     * A basic feature test for updatePassword function by the normal user.
     */
    public function test_update_password_function_by_normal_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $response = $this->putJson(
            route('users.update.password',$user->id),
            [
                'password' => 'test5678',
                'password_confirmation' => 'test5678',
            ]
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->putJson(
            route('users.update.password',$user->id),
            [
                'password' => 'test5678',
                'password_confirmation' => 'test5678',
            ]
        );
        $response->assertStatus(403);
        
    }

    /**
     * A basic feature test for delete function by the admin user.
     */
    public function test_delete_function_by_admin_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);
        $response = $this->deleteJson(
            route('users.delete', $user->id)
        );
        $response->assertStatus(401);

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->deleteJson(
            route('users.delete', $user->id)
        );
        $response->assertStatus(204);

        // 用不存在的user id 執行
        $response = $this->deleteJson(
            route('users.delete', $user->id+100)
        );
        $response->assertStatus(404);

        $response = $this->deleteJson(
            route('users.delete', $loginUser->id)
        );
        $response->assertStatus(403);

    }

    /**
     * A basic feature test for delete function by the normal user.
     */
    public function test_delete_function_by_normal_user(): void
    {
        // 未登入就執行
        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);
        $response = $this->deleteJson(
            route('users.delete', $user->id)
        );
        $response->assertStatus(401);
        
        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        Sanctum::actingAs($loginUser);

        // 登入後執行
        $response = $this->deleteJson(
            route('users.delete', $user->id)
        );
        $response->assertStatus(403);
    }
}
