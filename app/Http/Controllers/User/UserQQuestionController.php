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
        if (is_null($request->limit)) {
            return response()->json(["status" => "failed", "message" => "Failed! Limit Question Required!"], 200);
        }

        $request['lessons_id'] = explode(",", $request['lessons_id']);

        $questions = QQuestion::whereIn('lesson_id', $request['lessons_id'])->get();
        dd(count($questions));
        if (count($questions) < $request->limit) {
            return response()->json(["status" => "failed", "message" => "Failed! Limit Question is bigger than question count!"], 200);
        }
        $items = Arr::random($questions, $request->limit);

        if (!is_null($items)) {
            return response()->json(["status" => "success", "data" => $items], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Question found"], 200);
        }
    }

}