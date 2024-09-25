<?php

namespace App\Http\Controllers;

use App\Models\DailyTimeRule;
use App\Models\Leave;
use App\Models\LeaveRequest;
use App\Models\LeaveResult;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function index(Request $request){

        $leave = LeaveRequest::with(['user','leave','result','result.user'])->get();
        return response($leave, 200);
    }

    public function show(Request $request,$user_id){

        if(User::find($user_id)===null){
            return response(['message'=>'The id has not been found'], 404);
        }

        $leave = LeaveRequest::with(['user', 'leave', 'result', 'result.user'])
                                ->where('user_id', $user_id)->get();

        return response($leave, 200);
    }

    public function user(Request $request){

        $user = auth()->user();
        $leave = LeaveRequest::with(['user', 'leave', 'result', 'result.user'])
                                ->where('user_id', $user->id)->get();
        return response($leave, 200);
    }

    public function selectTime(Request $request){

        $daily_time_rule = DailyTimeRule::first();
        $work_start_time = Carbon::parse($daily_time_rule->work_start_time)->format('H:i');
        $work_end_time = Carbon::parse($daily_time_rule->work_end_time)->format('H:i');

        $times = [];
        $periods = CarbonPeriod::create($work_start_time, '30 minutes', $work_end_time);

        foreach($periods as $period){
            $times[] = $period->format('H:i');
        }

        return response($times, 200);

    }

    public function createRequest(Request $request, $leave_id){

        $user = auth()->user();

        if(Leave::find($leave_id)===null){
            return response(['message' => "The leave_id has not been found"], 404);
        }

        $daily_time_rule = DailyTimeRule::first();
        $work_start_time = Carbon::parse($daily_time_rule->work_start_time)->format('H:i');
        $work_end_time = Carbon::parse($daily_time_rule->work_end_time)->format('H:i');

        $validator = Validator::make([
            'description' => request()->description,
            'start_date' => request()->start_date,
            'start_time' => request()->start_time,
            'end_date' => request()->end_date,
            'end_time' => request()->end_time,
        ], [
            'description' => "required|string|max:255",
            'start_date' => "required|date_format:Y-m-d|before_or_equal:end_date",
            'start_time' => "required|date_format:H:i|before:end_time|after_or_equal:{$work_start_time}",
            'end_date' => "required|date_format:Y-m-d|after_or_equal:start_date",
            'end_time' =>  "required|date_format:H:i|after:start_time|before_or_equal:{$work_end_time}",
        ]);
        $validator->validate();


        $leaveRequest = new LeaveRequest([
            'user_id' => $user->id,
            'leave_id' => $leave_id,
            'description' => request()->description,
            'start_date' => request()->start_date,
            'start_time' => request()->start_time,
            'end_date' => request()->end_date,
            'end_time' => request()->end_time,
        ]);
        $leaveRequest->save();

        return response(['message' => "Success"],201);  
    }

    public function reviewRequest(Request $request, $leave_request_id){

        $user = auth()->user();
        
        if(LeaveRequest::find($leave_request_id)===null){
            return response(['message' => "The leave_request_id has not been found"], 404);
        }

        if(LeaveRequest::find($leave_request_id)->user_id===$user->id){
            return response(['message' => "The requester and reviewer cannot be same person"], 400);
        }

        $validator = Validator::make([
            'is_allowed' => request()->is_allowed,
        ], [
            'is_allowed' => 'required|boolean',
        ]);
        $validator->validate();

        DB::beginTransaction();
        
        try{      
           LeaveRequest::where('id', $leave_request_id)->update([
                'is_reviewed' => 1,
           ]);
           
           $leaveResult = new LeaveResult([
                'leave_request_id' => $leave_request_id,
                'review_user_id' => $user->id,
                'is_allowed' => request()->is_allowed,
           ]);
           $leaveResult->save();
            
        }catch(\Throwable $th){
    
            DB::rollBack();
            report($th);
            return response(['message' => "Server error"],500);
        }

        DB::commit();

        return response(['message' => "Success"],200);  
    }

}

