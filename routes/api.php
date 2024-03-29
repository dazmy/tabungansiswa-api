<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\SearchByMonthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    return response()->json([
        'message' => 'Hello World!',
    ], 200);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/students', StudentController::class);
    Route::apiResource('/teacher', UserController::class)->only(['update', 'show']);
    Route::apiResource('/deposit', DepositController::class)->only(['store', 'show', 'update', 'destroy']);
    Route::apiResource('/credit', CreditController::class)->only(['store', 'update', 'destroy']);
    
    Route::get('/homepage', HomepageController::class);
    Route::get('/profile', ProfileController::class);
    Route::get('/grade', GradeController::class);
    
    Route::get('/detailofmonth/{studentid}/{month}/{year}', SearchByMonthController::class);
    Route::get('/datamonth/{month}/{year}', [PDFController::class, 'getMonth']);
});
