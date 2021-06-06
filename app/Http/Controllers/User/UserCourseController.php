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
        $courses = Course::with('lessons')->paginate(5);
        foreach ($courses as $course) {
            if ($course->photo != null) {
                $course['photo_url'] = asset('storage/' . $course->photo->filePath());
            }
        }
        if (count($courses) > 0) {
            return response()->json(["status" => "success", "count" => count($courses), "data" => $courses->makeHidden(['created_at','updated_at','photo','photo_id'])], 200);
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
            if ($course->photo != null) {
                $course['photo_url'] = asset('storage/' . $course->photo->filePath());
            }
            return response()->json(["status" => "success", "data" => $course->makeHidden(['created_at','updated_at','photo','photo_id'])], 200);
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

    public function unenroll(Course $course) {
        $user = Auth::user();
        if (!is_null($course)) {
            $course->users()->detach($user);
            return response()->json(["status" => "success", "message" => "Course UnEnroll Success"], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
        }
    }

    public function UserCourses() {
        $user = Auth::user();
        $courses = $user->courses;
        foreach ($courses as $course) {
            if ($course->photo != null) {
                $course['photo_url'] = asset('storage/' . $course->photo->filePath());
            }
        }
        return response()->json(["status" => "success", "data" => $courses->makeHidden(['created_at','updated_at','photo','photo_id', 'pivot'])], 200);
    }

    public function CourseLessons(Course $course) {

        if (!is_null($course)) {
            $lessons = $course->lessons;

            foreach ($lessons as $lesson) {
                $lesson->prgress = auth()->user()->calculateProgress($lesson);
            }
            return response()->json(["status" => "success", "data" => $lessons->makeHidden(['created_at', 'updated_at', 'course_id'])], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
        }
        
    }
}