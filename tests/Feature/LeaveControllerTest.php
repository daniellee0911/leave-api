<?php

namespace Tests\Feature;

use App\Models\DailyTimeRule;
use App\Models\Leave;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LeaveControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test for createRequest function by the admin user.
     */
    public function test_create_request_function_by_admin_user(): void
    {
        $leave = Leave::create([
            'name' => "病假"
        ]);

        DailyTimeRule::create([
            'work_start_time' => '09:30',
            'work_end_time' => '18:30',
        ]);

        $loginUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin1234'),
        ]);
        $response = $this->postJson(
            route('leaves.request',$leave->id),
            [
                'description' => '感冒,身體不舒服',
                'start_date' => '2024-08-13',
                'start_time' => '14:30',
                'end_date' => '2024-08-13',
                'end_time' => '18:30',
            ]
        );
        $response->assertStatus(401);

        Sanctum::actingAs($loginUser);

        $response = $this->postJson(
            route('leaves.request',$leave->id),
            [
                'description' => '感冒,身體不舒服',
                'start_date' => '2024-08-13',
                'start_time' => '14:30',
                'end_date' => '2024-08-13',
                'end_time' => '18:30',
            ]
        );
        $response->assertStatus(201);
        
    }

    /**
     * A basic feature test for createRequest function by the normal user.
     */
    public function test_create_request_function_by_normal_user(): void
    {
        $leave = Leave::create([
            'name' => "病假"
        ]);

        DailyTimeRule::create([
            'work_start_time' => '09:30',
            'work_end_time' => '18:30',
        ]);

        $loginUser = User::create([
            'name' => 'user',
            'email' => 'user@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('user1234'),
        ]);
        $response = $this->postJson(
            route('leaves.request',$leave->id),
            [
                'description' => '感冒,身體不舒服',
                'start_date' => '2024-08-13',
                'start_time' => '14:30',
                'end_date' => '2024-08-13',
                'end_time' => '18:30',
            ]
        );
        $response->assertStatus(401);

        Sanctum::actingAs($loginUser);

        $response = $this->postJson(
            route('leaves.request',$leave->id),
            [
                'description' => '感冒,身體不舒服',
                'start_date' => '2024-08-13',
                'start_time' => '14:30',
                'end_date' => '2024-08-13',
                'end_time' => '18:30',
            ]
        );
        $response->assertStatus(201);
    }

    /**
     * A basic feature test for reviewRequest function by the admin user.
     */
    public function test_create_review_function_by_admin_user(): void
    {
        $leave = Leave::create([
            'name' => "病假"
        ]);

        DailyTimeRule::create([
            'work_start_time' => '09:30',
            'work_end_time' => '18:30',
        ]);

        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $leaveRequest1 = LeaveRequest::create([
            'user_id' => $user->id,
            'leave_id' => $leave->id,
            'description' => '感冒,身體不舒服',
            'start_date' => '2024-08-13',
            'start_time' => '14:30',
            'end_date' => '2024-08-13',
            'end_time' => '18:30',
        ]);

        $response = $this->postJson(
            route('leaves.request.review',$leaveRequest1->id),
            [
                'is_allowed' => 1,
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

        $response = $this->postJson(
            route('leaves.request.review',$leaveRequest1->id),
            [
                'is_allowed' => 1,
            ]
        );
        
        $response->assertStatus(200);

        $response = $this->postJson(
            route('leaves.request.review',$leaveRequest1->id+100),
            [
                'is_allowed' => 1,
            ]
        );
        $response->assertStatus(404);

        $leaveRequest2 = LeaveRequest::create([
            'user_id' => $loginUser->id,
            'leave_id' => $leave->id,
            'description' => '感冒,身體不舒服',
            'start_date' => '2024-08-13',
            'start_time' => '14:30',
            'end_date' => '2024-08-13',
            'end_time' => '18:30',
        ]);

        $response = $this->postJson(
            route('leaves.request.review',$leaveRequest2->id),
            [
                'is_allowed' => 1,
            ]
        );
        $response->assertStatus(400);
    }

    /**
     * A basic feature test for reviewRequest function by the normal user.
     */
    public function test_create_review_function_by_normal_user(): void
    {
        $leave = Leave::create([
            'name' => "病假"
        ]);

        DailyTimeRule::create([
            'work_start_time' => '09:30',
            'work_end_time' => '18:30',
        ]);

        $user = User::create([
            'name' => 'test1',
            'email' => 'test1@gmail.com',
            'is_admin' => 0,
            'password' => Hash::make('test1234'),
        ]);

        $leaveRequest1 = LeaveRequest::create([
            'user_id' => $user->id,
            'leave_id' => $leave->id,
            'description' => '感冒,身體不舒服',
            'start_date' => '2024-08-13',
            'start_time' => '14:30',
            'end_date' => '2024-08-13',
            'end_time' => '18:30',
        ]);

        $response = $this->postJson(
            route('leaves.request.review',$leaveRequest1->id),
            [
                'is_allowed' => 1,
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

        $response = $this->postJson(
            route('leaves.request.review',$leaveRequest1->id),
            [
                'is_allowed' => 1,
            ]
        );
        $response->assertStatus(403);
    }

}
