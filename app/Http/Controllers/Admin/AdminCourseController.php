<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Services\Uploader\Uploader;

class AdminCourseController extends Controller
{
    private $uploader;

    public function __construct(Uploader $uploader)
    {
        $this->uploader = $uploader;
    }
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
            return response()->json(["status" => "success", "count" => count($courses), "data" => $courses->makeHidden(['created_at', 'updated_at'])], 200);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "price" => "required|integer"
        ]);

        $file = $this->uploader->upload();

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $CourseInput = $request->all();
        $CourseInput['photo_id'] = $file->id;

        $course = Course::create($CourseInput);
        if (!is_null($course)) {
            return response()->json(["status" => "success", "message" => "Success! Course created", "data" => $course]);
        } else {
            return response()->json(["status" => "failed", "message" => "Whoops! Course not created"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $input = $request->all();
        $user = Auth::user();

        if (!is_null($user)) {

            // validation
            $validator = Validator::make($request->all(), [
                "title" => "required",
                "description" => "required",
                "price" => "required|integer"
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
            }

            if ($request->file('file') !== null) {
                $course->photo->delete();

                $file = $this->uploader->upload();

                $CourseInput = $request->all();
                $CourseInput['photo_id'] = $file->id;

                $course->update($CourseInput);

            } else {
                $CourseInput = $request->all();

                $course->update($CourseInput);
            }

            return response()->json(["status" => "success", "message" => "Success! course updated", "data" => $course], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Un-authorized user"], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
        $user = Auth::user();

        if (!is_null($user)) {
            $course = Course::where("id", $course->id)->delete();
            return response()->json(["status" => "success", "message" => "Success! Course deleted"], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Un-authorized user"], 403);
        }
    }


    public function UpdatePhoto(Course $course, Request $request)
    {
        if ($request->file('file') !== null) {
            $file = $this->uploader->upload();
            $course->update(['photo_id' => $file->id]);
            return response()->json(["status" => "success", "message" => "Course Photo Updated Successfully!"]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Course Photo Required!"]);
        }
    }
}
