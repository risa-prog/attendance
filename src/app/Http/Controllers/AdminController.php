<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Models\WorkCorrection;
use App\Models\RestCorrection;

class AdminController extends Controller
{
    public function showAttendance(){
        $today = Carbon::now();
        $work_day = $today->format('Y-m-d');
        $works = Work::where('date',$work_day)->get();
       
        return view('admin.attendance_list',compact('today','works'));
    }

    public function showStaffList(){
        $users = User::all();
        return view('admin.staff_list',compact('users'));
    }

    public function showStaffAttendance($id){
        $user = User::find($id);
        $date = Carbon::now();
        $works = Work::where('user_id',$user->id)->whereMonth('date',$date->month)->get();

        return view('admin.staff',compact('user','date','works'));
    }

    public function showPreviousMonth(Request $request){
        $user = User::find($request->user_id);
        
        $request_date = $request->date;
        $targetDate = Carbon::parse($request_date);
        $startOfPreviousMonth = $targetDate->copy()->subMonth()->startOfMonth();
        $endOfPreviousMonth = $targetDate->copy()->subMonth()->endOfMonth();
        
        $works = Work::whereBetween('date',[$startOfPreviousMonth,$endOfPreviousMonth])->where('user_id',$user->id)->get();

        $date = $startOfPreviousMonth;

        return view('admin.staff',compact('user','date','works'));
    }

    public function showNextMonth(Request $request){
        $user = User::find($request->user_id);
        
        $request_date = $request->date;
        
        $targetDate = Carbon::parse($request_date);
        $startOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->startOfMonth();
        $endOfNextMonth = $targetDate->copy()->addMonthNoOverflow()->endOfMonth();

        $works = Work::whereBetween('date',[$startOfNextMonth,$endOfNextMonth])->where('user_id',$user->id)->get();

        $date = $startOfNextMonth;

        return view('admin.staff',compact('user','date','works'));
    }

    public function showCorrectionRequestApproval($attendance_correct_request){
        $work_correction = WorkCorrection::find($attendance_correct_request);
        
        $rest_corrections = RestCorrection::with('work')->whereHas('work',function($query) use ($work_correction){
            $query -> where('work_id',$work_correction->work_id);
        })->get();
        
        return view('admin.approval',compact('work_correction','rest_corrections'));
    }

    public function approve(Request $request){
        $work_time = $request -> only(['start_time','end_time']);
        $work = Work::find($request->work_id);
        $work->update($work_time);

        if($work->restCorrections->isNotEmpty()){
            $rest_time = $request ->only('rest_id','rest_start','rest_end');
            $rest_id = $rest_time['rest_id'];
            $rest_start = $rest_time['rest_start'];
            $rest_end = $rest_time['rest_end'];

            for($i = 0; $i < count($rest_id); $i++){
                $rest_correction = [
                'id' => $rest_id[$i],
                'rest_start' => $rest_start[$i],
                'rest_end' => $rest_end[$i],
                'work_id' => $work->id
                ];
                
                $rests = Rest::where('work_id',$work->id)->get();
                $rest_id_group=array();
                foreach($rests as $rest){
                array_push($rest_id_group,$rest->id);
                }
        
                if(in_array($rest_correction['id'],$rest_id_group)){
                Rest::where('id',$rest_correction['id'])->update($rest_correction);
                }elseif($rest_correction['id'] === null){
                Rest::create($rest_correction);
                }else{
                    $rests = Rest::where('id','!=',$rest_correction['id'])->where('work_id',$work->id)->get();
                    foreach($rests as $rest){
                        Rest::find($rest->id)->delete();
                    }
                } 
            }
        }else{
            Rest::where('work_id',$work->id)->delete();
        }
        
        $work_correction = WorkCorrection::where('work_id','=',$work->id)->first();
        $work_correction->update(['status' => '2']);
    
        return redirect()->route(('request.approval'),['attendance_correct_request' => $work_correction->id]);

    
    }
}
