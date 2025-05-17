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
    Route::get('/attendance/{id}',[AttendanceController::class,'showAttendanceDetail']);
    Route::get('/stamp_correction_request/list',[AttendanceController::class,'showCorrectionList']);

    Route::get('/timestamp/punch_in',[TimestampController::class,'punchIn']);
    Route::get('/timestamp/punch_out',[TimestampController::class,'punchOut']);
    Route::get('/timestamp/break_begins',[TimestampController::class,'breakBegins']);
    Route::get('timestamp/break_ends',[TimestampController::class,'breakEnds']);

    Route::get('/attendance_list/last_month',[AttendanceController::class,'showLastMonth']);
    Route::get('/attendance_list/next_month',[AttendanceController::class,'showNextMonth']);

    // user 修正ボタン
    Route::post('/attendance',[AttendanceController::class,'request']);

    // user 承認待ちのリスト表示
    Route::get('/waiting_for_approval',[AttendanceController::class,'showWaitingForApproval']);
    // user 承認済みのリスト表示
    Route::get('/approved',[AttendanceController::class,'showApproved']);

    // Admin
    // スタッフ一覧
    Route::get('/admin/staff/list',[AdminController::class,'showStaffList']);
    // 勤怠一覧
    Route::get('/admin/attendance/list',[AdminController::class,'showAttendance']);
    // スタッフ別勤怠一覧
    Route::get('/admin/attendance/staff/{id}',[AdminController::class,'showStaffAttendance']);

    // 申請詳細画面（申請承認画面）
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}',[AdminController::class,'showCorrectionRequestApproval']);
    // 申請承認処理
    Route::post('/stamp_correction_request/approve',[AdminController::class,'approve']);
});








