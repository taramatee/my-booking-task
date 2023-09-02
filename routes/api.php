<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('patient')->group(function(){
//     Route::get('/index', [AppointmentController::class, 'index'])->name('index');
//     Route::post('/create', [AppointmentController::class, 'create'])->name('create');
//     Route::post('/update/{id}', [AppointmentController::class, 'update'])->name('update');
// });

// Route::middleware('doctor')->group(function(){
//     Route::get('/view-appointment', [AppointmentController::class, 'viewAppointment'])->name('viewAppointment');
//     Route::post('/status-update/{id}', [AppointmentController::class, 'statusUpdate'])->name('statusUpdate');
// });

