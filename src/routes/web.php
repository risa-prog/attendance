<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminLogoutController;
use App\Http\Controllers\TimestampController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmailVerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


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


Route::get('/login',
[LoginController::class,'showLoginForm'])->name('login');
Route::post('/login',[LoginController::class,'login']);
Route::get('/register',
[RegisterController::class,'showRegisterForm']);
Route::post('/register',[RegisterController::class,'register']);
Route::post('/logout',[LogoutController::class,'logout']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // email_verified_at を更新
    return redirect('/attendance'); // 認証後のリダイレクト先
})->middleware(['auth:web', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証リンクを再送しました。');
})->middleware(['auth:web', 'throttle:6,1'])->name('verification.send');
Route::get('/email/verify', function () {
    return view('auth.email_verification'); 
})->middleware('auth:web')->name('verification.notice');

Route::get('/email_verification',[EmailVerificationController::class,'index'])->name('email.verification');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLogoutController::class, 'logout']);
});

Route::middleware(['auth:web','verified'])->group(function(){
    Route::get('/attendance',[AttendanceController::class,'showAttendance']);
    Route::get('/attendance/list', [
        AttendanceController::class,
        'showAttendanceList'
    ])->name('attendance.list');
    Route::get('/user/attendance/{id}',[AttendanceController::class,'showAttendanceDetail'])->name('user.attendance.detail');
    Route::get('/user/stamp_correction_request/list',[AttendanceController::class,'showCorrectionList'])->name('user.collection.list');
    Route::post('/attendance',[AttendanceController::class,'request']);

    Route::post('/timestamp/work_start',[TimestampController::class,'workStart']);
    Route::post('/timestamp/work_end',[TimestampController::class,'workEnd']);
    Route::post('/timestamp/rest_start',[TimestampController::class,'restStart']);
    Route::post('timestamp/rest_end',[TimestampController::class,'restEnd']);

});

Route::middleware(['check.admin'])->group(function () {
    Route::get('/admin/attendance/list', [AdminController::class, 'showAttendanceList']);
    Route::get('/admin/attendance/{id}', [AttendanceController::class, 'showAttendanceDetail'])->name('admin.attendance.detail');
    Route::get('/admin/staff/list', [AdminController::class, 'showStaffList']);
    Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'showStaffAttendance']);
    Route::get('/admin/stamp_correction_request/list', [AdminController::class, 'showAdminCorrectionList'])->name('admin.collection.list');
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminController::class, 'showCorrectionRequestApproval'])->name('request.approval');
    Route::post('/stamp_correction_request/approve', [AdminController::class, 'approve']);
    // Admin 修正機能
    Route::post('/stamp_correction', [AdminController::class, 'correct']);

    // csv
    Route::post('/admin/attendance/staff/csv-download', [AdminController::class, 'downloadCsv']);
});

Route::get('/attendance/{id}', [AttendanceController::class, 'AttendanceDetailRedirect']);
Route::get('/stamp_correction_request/list', function () {

})->middleware(['correction.list.redirector']);
