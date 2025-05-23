<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TimestampController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/register',[RegisterController::class,'store']);
Route::post('/login',[LoginController::class,'store']);

Route::middleware('auth')->group(function(){
    Route::get('/attendance',[AttendanceController::class,'showAttendance']);
    Route::get('/attendance/{id}',[AttendanceController::class,'showAttendanceDetail'])->name('work_detail');
    Route::get('/stamp_correction_request/list',[AttendanceController::class,'showCorrectionList']);

    Route::get('/timestamp/punch_in',[TimestampController::class,'punchIn']);
    Route::get('/timestamp/punch_out',[TimestampController::class,'punchOut']);
    Route::get('/timestamp/break_begins',[TimestampController::class,'breakBegins']);
    Route::get('timestamp/break_ends',[TimestampController::class,'breakEnds']);

    Route::get('/attendance_list/previous_month',[AttendanceController::class,'showPreviousMonth']);
    Route::get('/attendance_list/next_month',[AttendanceController::class,'showNextMonth']);

    // user 修正ボタン
    Route::post('/attendance',[AttendanceController::class,'request']);

    // user 承認待ちのリスト表示
    Route::get('/stamp_correction_request/list/waiting_for_approval',[AttendanceController::class,'showWaitingForApproval']);
    // user 承認済みのリスト表示
    Route::get('/stamp_correction_request/list/approved',[AttendanceController::class,'showApproved']);

    // Admin
    // スタッフ一覧
    Route::get('/admin/staff/list',[AdminController::class,'showStaffList']);
    // 勤怠一覧
    Route::get('/admin/attendance/list',[AdminController::class,'showAttendanceList']);
    // 勤怠一覧　前月
    Route::get('/admin/attendance/list/previous_day',[AdminController::class,'showPreviousDay']);
    // 勤怠一覧　翌月
    Route::get('/admin/attendance/list/next_day',[AdminController::class,'showNextDay']);
    // スタッフ別勤怠一覧
    Route::get('/admin/attendance/staff/{id}',[AdminController::class,'showStaffAttendance']);
    // 前月
    Route::get('/admin/attendance/staff/previous_month',[AdminController::class,'showPreviousMonth']);
    // 翌月
    Route::get('/admin/attendance/staff/next_month',[AdminController::class,'showNextMonth']);

    // 申請詳細画面（申請承認画面）
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}',[AdminController::class,'showCorrectionRequestApproval'])->name('request.approval');
    // 申請承認処理
    Route::post('/stamp_correction_request/approve',[AdminController::class,'approve']);
});








