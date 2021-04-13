<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserCourseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        //
        $courses = Course::paginate(5);
        if (count($courses) > 0) {
            return response()->json(["status" => "success", "count" => count($courses), "data" => $courses->makeHidden(['created_at','updated_at'])], 200);
        } else {
            return response()->json(["status" => "failed", "count" => count($courses), "message" => "Failed! no Course found"], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show(Course $course)
    {
        //
        if (!is_null($course)) {
            return response()->json(["status" => "success", "data" => $course], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
        }
    }

    public function enroll(Course $course) {
        $user = Auth::user();
        if (!is_null($course)) {
            $course->users()->syncWithoutDetaching($user);
            return response()->json(["status" => "success", "message" => "Course Enroll Success"], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
        }
    }

    public function UserCourses() {
        $user = Auth::user();
        return response()->json(["status" => "success", "data" => $user->courses->makeHidden(['pivot', 'created_at', 'updated_at'])], 200);
    }

    public function CourseLessons(Course $course) {
        $lessons = $course->lessons;
        return response()->json(["status" => "success", "data" => $lessons->makeHidden(['created_at', 'updated_at', 'course_id'])], 200);
    }
}