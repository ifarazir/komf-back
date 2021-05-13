<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\QQuestion;
use Illuminate\Http\Request;

class UserQQuestionController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function RandomQuiz(Request $request)
    {

        if(is_null($request->limit))
            return response()->json(["status" => "failed", "message" => "Failed! Limit Question Required!"], 200);
        $questions = QQuestion::where('lesson_id', $request->lesson_id)->inRandomOrder()->limit($request->limit)->get();
        if (!is_null($questions)) {
            return response()->json(["status" => "success", "data" => $questions->makeHidden(['created_at', 'updated_at'])], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Question found"], 200);
        }
    }

}
