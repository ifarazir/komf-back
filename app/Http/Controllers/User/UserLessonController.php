<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;

class UserLessonController extends Controller
{

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
            return response()->json(["status" => "success", "data" => $lesson->makeHidden(['created_at', 'updated_at', 'course_id'])], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
        }
    }

    public function LessonVocabs(Lesson $lesson) {
        $vocabs = $lesson->vocabs;
        return response()->json(["status" => "success", "data" => $vocabs->makeHidden(['created_at', 'updated_at', 'pivot'])], 200);
    }
}
