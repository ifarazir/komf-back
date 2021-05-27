<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\UserController;

use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminVocabController;
use App\Http\Controllers\Admin\AdminLessonController;
use App\Http\Controllers\Admin\AdminQQuestionController;
use App\Http\Controllers\Backend\FileController;
use App\Http\Controllers\User\UserCourseController;
use App\Http\Controllers\User\UserVocabController;
use App\Http\Controllers\User\UserLessonController;
use App\Http\Controllers\User\UserQQuestionController;

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

Route::middleware('auth:api')->group(function () {
    Route::get("user", [UserController::class, "user"]);
    Route::post("user/ChangePassword", [UserController::class, "ChangePassword"]);
    Route::post("user/ChangeProfile", [UserController::class, "ChangeProfile"]);
    Route::post("logout", [UserController::class, "logout"]);

    Route::resource('courses', UserCourseController::class)->only(['index', 'show']);
    Route::post('courses/add/{course}', [UserCourseController::class, "enroll"]);
    Route::post('courses/remove/{course}', [UserCourseController::class, "unenroll"]);
    Route::get('courses/{course}/lessons', [UserCourseController::class, "CourseLessons"]);
    Route::get('user/courses', [UserCourseController::class, "UserCourses"]);

    Route::get('lessons', [UserLessonController::class, "index"]);
    Route::get('lessons/{lesson}', [UserLessonController::class, "show"]);
    Route::post('lesson/vocab/check', [UserLessonController::class, "lessonCheck"]);
    Route::get('lessons/{lesson}/vocabs', [UserLessonController::class, "LessonVocabs"]);
    Route::post('lessons/quiz', [UserQQuestionController::class, "RandomQuiz"]);

    Route::group(['prefix' => 'admin',  'middleware' => 'role:admin'], function () {
        Route::resource('courses', AdminCourseController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::post('lessons/{lesson}/vocabs/add/', [AdminLessonController::class, "lessonVocabs"]);
        Route::resource('lessons', AdminLessonController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::resource('vocabs', AdminVocabController::class);
        Route::resource('quiz/questions', AdminQQuestionController::class);

        Route::resource('file', FileController::class);

        Route::get('file/{file}', [FileController::class, 'show'])->name('file.show');
        Route::get('file/delete/{file}', [FileController::class, 'delete'])->name('file.delete');
    });
});

Route::get('request', [UserCourseController::class, 'test']);
