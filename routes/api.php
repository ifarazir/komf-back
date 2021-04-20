<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\UserController;

use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminVocabController;
use App\Http\Controllers\Admin\AdminLessonController;

use App\Http\Controllers\User\UserCourseController;
use App\Http\Controllers\User\UserVocabController;
use App\Http\Controllers\User\UserLessonController;

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
    Route::post("logout", [UserController::class, "logout"]);
    
    Route::resource('courses', UserCourseController::class)->only(['index', 'show']);
    Route::post('courses/add/{course}', [UserCourseController::class, "enroll"]);
    Route::post('courses/remove/{course}', [UserCourseController::class, "unenroll"]);
    Route::get('courses/{course}/lessons', [UserCourseController::class, "CourseLessons"]);
    Route::get('user/courses', [UserCourseController::class, "UserCourses"]);

    Route::get('lessons/{lesson}', [UserLessonController::class, "show"]);
    Route::get('lessons/{lesson}/vocabs', [UserLessonController::class, "LessonVocabs"]);
    
    Route::group(['prefix' => 'admin',  'middleware' => 'role:admin'], function() {
        Route::resource('courses', AdminCourseController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::resource('lessons', AdminLessonController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::resource('vocabs', AdminVocabController::class);
    });
});

Route::get('request', [UserCourseController::class, 'test']);