<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $courses = Course::all();
        if (count($courses) > 0) {
            return response()->json(["status" => "success", "count" => count($courses), "data" => $courses], 200);
        } else {
            return response()->json(["status" => "failed", "count" => count($courses), "message" => "Failed! no Course found"], 200);
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

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $CourseInput = $request->all();

        $course = Course::create($CourseInput);
        if (!is_null($course)) {
            return response()->json(["status" => "success", "message" => "Success! Course created", "data" => $course]);
        } else {
            return response()->json(["status" => "failed", "message" => "Whoops! Course not created"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $course = Course::where("id", $id)->first();
        if (!is_null($course)) {
            return response()->json(["status" => "success", "data" => $course], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
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
        //
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

            // update post
            $update = $course->update($request->all());

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
        
        if(!is_null($user)) {
            $course = Course::where("id", $course->id)->delete();
            return response()->json(["status" => "success", "message" => "Success! Course deleted"], 200);
        }

        else {
            return response()->json(["status" => "failed", "message" => "Un-authorized user"], 403);
        }
    }
}
