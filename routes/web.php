<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [LeaveTypeController::class, 'welcome_home'])->name('welcome_home');
Route::get('/view_leave_type', [LeaveTypeController::class, 'index'])->name('view_leave_type');
Route::get('/create_leave_type', [LeaveTypeController::class, 'create_leave_type'])->name('create_leave_type');
Route::post('/store_leave_type', [LeaveTypeController::class, 'store_leave_type'])->name('store_leave_type');

Route::get('/create_employee', [EmployeeController::class, 'create_employee'])->name('create_employee');
Route::post('/store_employee', [EmployeeController::class, 'store_employee'])->name('store_employee');

Route::get('/view_leave', [LeaveController::class, 'view_leave'])->name('view_leave');
Route::get('/create_leave', [LeaveController::class, 'create_leave'])->name('create_leave');
Route::post('/store_leave', [LeaveController::class, 'store_leave'])->name('store_leave');
Route::get('/get_intotal_leave_taken/{id}', [LeaveController::class, 'getTotalLeaveTaken']);
Route::get('/get_max_leave_taken/{id}', [LeaveController::class, 'getMaxLeaveTaken']);

// Route::get('/select_employee_month', 'LeaveController@selectEmployeeAndMonth')->name('select_employee_month');
// Route::get('/generate_monthly_leave_report', 'LeaveController@generateMonthlyLeaveReport')->name('generate_monthly_leave_report');
Route::get('/select_employee_month', [LeaveController::class, 'selectEmployeeAndMonth'])->name('select_employee_month');
Route::get('/generate_monthly_leave_report', [LeaveController::class, 'generateMonthlyLeaveReport'])->name('generate_monthly_leave_report');
// Route::get('/', function () {
//     return view('welcome');
// });
