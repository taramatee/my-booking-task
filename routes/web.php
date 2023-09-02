<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\API\AppointmentController;

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

Route::get('/', function () {
    return view('auth.registration');
});


Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('submit-login', [AuthController::class, 'submitLogin'])->name('login.submit');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('submit-registration', [AuthController::class, 'submitRegistration'])->name('register.submit');
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('add-appointment', [AppointmentController::class, 'addAppointment'])->name('add-appointment');


Route::middleware('patient')->group(function(){
    Route::get('/index', [AppointmentController::class, 'index'])->name('index');
    Route::post('/create', [AppointmentController::class, 'create'])->name('create');
    Route::post('/update/{id}', [AppointmentController::class, 'update'])->name('update');
    Route::get('/postponed/{id}', [AppointmentController::class, 'postponed'])->name('postponed');
    Route::post('/savePostponed', [AppointmentController::class, 'savePostponed'])->name('savePostponed');
});

Route::middleware('doctor')->group(function(){
    Route::get('/view-appointment', [AppointmentController::class, 'viewAppointment'])->name('viewAppointment');
    Route::post('/statusUpdate/{id}', [AppointmentController::class, 'statusUpdate'])->name('statusUpdate');
});
