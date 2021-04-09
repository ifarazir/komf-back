<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\UserController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\User\UserCourseController;

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

// user controller routes
Route::post("register", [UserController::class, "register"]);

Route::post("login", [UserController::class, "login"]);

// sanctum auth middleware routes

Route::middleware('auth:api')->group(function() {
    Route::get("user", [UserController::class, "user"]);

    Route::resource('courses', UserCourseController::class)->only(['index', 'show']);
    Route::post('courses/add/{course}', [UserCourseController::class, "enroll"]);
    Route::get('user/courses', [UserCourseController::class, "UserCourses"]);

    
    Route::group(['prefix' => 'admin'], function() {
        Route::resource('courses', AdminCourseController::class)->only(['store', 'update', 'destroy']);
    });
});

Route::get('request', [UserCourseController::class, 'test']);