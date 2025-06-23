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
use App\Http\Controllers\CommonController;
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

// メール認証
Route::get('/email/verify/{id}/{hash}',[EmailVerificationController::class,'emailVerify'])->middleware(['auth:web', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification',[EmailVerificationController::class,'resend'])->middleware(['auth:web', 'throttle:6,1'])->name('verification.send');
// メール認証必須のルートにて、メール認証をしないでアクセスしようとした時のリダイレクト先
Route::get('/email/verify', [EmailVerificationController::class,'emailVerificationRedirect'])->middleware('auth:web')->name('verification.notice');

// メール認証誘導画面へ
Route::get('/email_verification',[EmailVerificationController::class,'index'])->name('email.verification');
// メール認証誘導画面の認証ボタンを押した時
// Route::get('/email_verification/check', [EmailVerificationController::class, 'emailVerificationCheck']);

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLogoutController::class, 'logout']);
});

Route::middleware(['auth:web','verified'])->group(function() {
    Route::get('/attendance',[AttendanceController::class,'showAttendance']);
    Route::get('/attendance/list', [
        AttendanceController::class,
        'showAttendanceList'
    ])->name('attendance.list');
    Route::post('/attendance/correct_request',[AttendanceController::class,'request']);

    Route::post('/timestamp/work_start',[TimestampController::class,'workStart']);
    Route::post('/timestamp/work_end',[TimestampController::class,'workEnd']);
    Route::post('/timestamp/rest_start',[TimestampController::class,'restStart']);
    Route::post('timestamp/rest_end',[TimestampController::class,'restEnd']);

});

Route::middleware(['check.admin'])->group(function () {
    Route::get('/admin/attendance/list', [AdminController::class, 'showAttendanceList']);
    Route::get('/admin/staff/list', [AdminController::class, 'showStaffList']);
    Route::get('/admin/attendance/staff/{id}', [AdminController::class, 'showStaffAttendance']);
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}', [AdminController::class, 'showCorrectionRequestApproval'])->name('request.approval');
    Route::post('/stamp_correction_request/approve', [AdminController::class, 'approve']);
    // Admin 修正機能
    Route::post('/stamp_correction', [AdminController::class, 'correct']);

    // csv
    Route::post('/admin/attendance/staff/csv-download', [AdminController::class, 'downloadCsv']);
});

Route::middleware(['auth.check'])->group(function() {
    Route::get('/attendance/{id}', [CommonController::class, 'showAttendanceDetail'])->name('attendance.detail');
    Route::get('/stamp_correction_request/list',[CommonController::class,'showCorrectionList']);
});

