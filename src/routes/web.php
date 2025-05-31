<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
// use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminLogoutController;
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


Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');
Route::get('/admin/login', function () {
    return view('admin.login');
})->middleware('guest:admin')->name('admin.login');

Route::get('/login',
[LoginController::class,'showLoginForm'])->name('login');
Route::post('/login',[LoginController::class,'login']);
Route::get('/register',
[RegisterController::class,'showRegisterForm']);
Route::post('/register',[RegisterController::class,'register']);
Route::post('/logout',[LogoutController::class,'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});


Route::middleware('auth:web')->group(function(){
    Route::get('/attendance',[AttendanceController::class,'showAttendance']);
    Route::get('/attendance/list',[AttendanceController::class,'showAttendanceList']);
    Route::get('/attendance/{id}',[AttendanceController::class,'showAttendanceDetail'])->name('work_detail');
    Route::get('/stamp_correction_request/list',[AttendanceController::class,'showCorrectionList']);
    Route::post('/attendance',[AttendanceController::class,'request']);

    Route::post('/timestamp/work_start',[TimestampController::class,'workStart']);
    Route::post('/timestamp/work_end',[TimestampController::class,'workEnd']);
    Route::post('/timestamp/rest_start',[TimestampController::class,'restStart']);
    Route::post('timestamp/rest_end',[TimestampController::class,'restEnd']);

});

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/attendance/list',[AdminController::class,'showAttendanceList']);
     Route::get('/admin/staff/list',[AdminController::class,'showStaffList']);
     Route::get('/admin/attendance/staff/{id}',[AdminController::class,'showStaffAttendance']);
    Route::get('/stamp_correction_request/approve/{attendance_correct_request}',[AdminController::class,'showCorrectionRequestApproval'])->name('request.approval');
    Route::post('/stamp_correction_request/approve',[AdminController::class,'approve']);
    // Admin 修正機能
    Route::post('/stamp_correction',[AdminController::class,'correct']);

    // csv
    Route::post('/admin/attendance/staff/csv-download',[AdminController::class,'downloadCsv']);
});

//  });


    
    






// Route::middleware(['guest:admin'])->group(function () {
//     Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm']);
//     Route::post('/admin/login', [AdminLoginController::class, 'store']);
// });



// Route::prefix('admin')->group(function () {
//     Route::get('/login', AdminLoginController::class,'showStaffList');
//     // Route::post('/login', [AdminLoginController::class, 'store'])
//     //     ->middleware(['guest:admin']);
    
//     //     Route::middleware(['auth:admin'])->group(function () {
//     //     Route::get('/staff/list');
//     // });
// });




// Route::post('/admin/logout', function (Request $request) {
//     Auth::guard('admin')->logout();
//     $request->session()->invalidate();
//     $request->session()->regenerateToken();
//     return redirect('/admin/login');
// });









