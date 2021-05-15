<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\Lesson_Vocab;
use App\Models\Vocab;
use Illuminate\Support\Facades\DB;

class UserLessonController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        $lessons = Lesson::get();
        if (count($lessons) > 0) {
            return response()->json(["status" => "success", "count" => count($lessons), "data" => $lessons->makeHidden(['created_at','updated_at'])], 200);
        } else {
            return response()->json(["status" => "failed", "count" => count($lessons), "message" => "Failed! no Lesson found"], 200);
        }
    }
    
     public function show(Lesson $lesson)
    {
        if (!is_null($lesson)) {
            return response()->json(["status" => "success", "data" => $lesson->makeHidden(['created_at', 'updated_at'])], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Course found"], 200);
        }
    }

    public function LessonVocabs(Lesson $lesson)
    {
        $vocabs = $lesson->vocabs;
        return response()->json(["status" => "success", "data" => $vocabs->makeHidden(['created_at', 'updated_at', 'pivot'])], 200);
    }

    public function lessonCheck(Request $request)
    {
        $lesson_vocab = DB::table('lesson_vocab')->where('lesson_id',$request->lesson_id)->where('vocab_id',$request->vocab_id)->first();

        if (!is_null($lesson_vocab)) {
            auth()->user()->progress()->syncWithoutDetaching($lesson_vocab->id);
            return response()->json(["status" => "success", "message" => "Succcess , lesson check"], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! lesson doesnt have this vocab"], 200);
        }
    }
}
