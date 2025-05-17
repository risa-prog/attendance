<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Work;
use App\Models\Correction;

class AdminController extends Controller
{
    public function showAttendance(){
        $now = Carbon::today();
        $today = $now->format('Y年m月d日');
        $date = $now->format('Y/m/d');
        $work_day = $now->format('Y-m-d');
        $works = Work::where('date',$work_day)->get();
       
        return view('admin.attendance_list',compact('today','date','works'));
    }

    public function showStaffList(){
        $users = User::all();
        return view('admin.staff_list',compact('users'));
    }

    public function showStaffAttendance($id){
        $user = User::find($id);
        $date = Carbon::now()->format('Y/m');
        return view('admin.staff',compact('user','date'));
    }

    public function showCorrectionRequestApproval($attendance_correct_request){
        $correction = Correction::find($attendance_correct_request);
        return view('admin.approval',compact('correction'));
    }

    public function approve(Request $request){

    }
}
