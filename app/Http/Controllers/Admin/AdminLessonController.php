<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Lesson;
use Illuminate\Http\Request;

class AdminLessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //
        $lessons = Lesson::paginate(5);
        if (count($lessons) > 0) {
            return response()->json(["status" => "success", "count" => count($lessons), "data" => $lessons->makeHidden(['created_at','updated_at'])], 200);
        } else {
            return response()->json(["status" => "failed", "count" => count($lessons), "message" => "Failed! no Lesson found"], 200);
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
            "course_id" => "required|integer"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $LessonInput = $request->all();

        $lesson = Lesson::create($LessonInput);
        if (!is_null($lesson)) {
            return response()->json(["status" => "success", "message" => "Success! Lesson created", "data" => $lesson]);
        } else {
            return response()->json(["status" => "failed", "message" => "Whoops! Lesson not created"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
        if (!is_null($lesson)) {
            return response()->json(["status" => "success", "data" => $lesson], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
