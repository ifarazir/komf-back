<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Lesson;
use App\Models\QQuestion;
use Illuminate\Http\Request;

class AdminQQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $QQuestions = QQuestion::all();
        if (count($QQuestions) > 0) {
            return response()->json(["status" => "success", "count" => count($QQuestions), "data" => $QQuestions->makeHidden(['created_at','updated_at'])], 200);
        } else {
            return response()->json(["status" => "failed", "count" => count($QQuestions), "message" => "Failed! no QQuestion found"], 200);
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
            "q1" => "required",
            "q2" => "required",
            "q3" => "required",
            "q4" => "required",
            "answer" => "required",
            "lesson_id" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $QQuestionInput = $request->all();
        $QQuestion = QQuestion::create($QQuestionInput);

        if (!is_null($QQuestion)) {
            return response()->json(["status" => "success", "message" => "Success! Quiz Question created", "data" => $QQuestion]);
        } else {
            return response()->json(["status" => "failed", "message" => "Whoops! Quiz Question not created"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(QQuestion $QQuestion)
    {
        //
        if (!is_null($QQuestion)) {
            return response()->json(["status" => "success", "data" => $QQuestion], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Quiz Question found"], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QQuestion $QQuestion)
    {
        //
        $input = $request->all();

        if (!is_null($QQuestion)) {

            // validation
            $validator = Validator::make($request->all(), [
                "title" => "required",
                "q1" => "required",
                "q2" => "required",
                "q3" => "required",
                "q4" => "required",
                "answer" => "required",
                "lesson_id" => "required"
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
            }

            // update post
            $update = $QQuestion->update($request->all());

            return response()->json(["status" => "success", "message" => "Success! Quiz Question updated", "data" => $update], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Quiz Question found"], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(QQuestion $QQuestion)
    {
        if(!is_null($QQuestion)) {
            $QQuestion = Lesson::where("id", $QQuestion->id)->delete();
            return response()->json(["status" => "success", "message" => "Success! Quiz Question deleted"], 200);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Failed! no Quiz Question found"], 200);
        }
    }
}