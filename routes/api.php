<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\TeamLeaderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/login', [AuthController::class,'login']);
Route::post('/register', [AuthController::class,'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/teamleader', [TeamLeaderController::class, 'show']);
    Route::post('/candidate', [CandidateController::class, 'create']);
    Route::post('/candidate/list', [CandidateController::class, 'list']);
    Route::post('/candidate/feedback', [CandidateController::class, 'feedback']);
    Route::post('/candidate/resume/download', [CandidateController::class, 'download']);
    Route::post('/logout', [AuthController::class, 'logout']);
});