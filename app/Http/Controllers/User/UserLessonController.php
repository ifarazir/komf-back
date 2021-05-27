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
        $vocabs = $lesson->vocabs->makeHidden('pivot')->toArray();

        foreach ($vocabs as $vocab) {
            $l_v = DB::table('lesson_vocab')->where('vocab_id',$vocab['id'])->where('lesson_id',$lesson['id'])->first();
            $lesson_check = DB::table('user_progress')->where('lesson_vocab_id',$l_v->id)->where('user_id',auth()->id())->first();
            if (!is_null($lesson_check)) {
                $key = array_search($vocab, $vocabs);
                unset($vocabs[$key]);
            }
        }
        if (count($vocabs) == 0) {
            $vocabs = $lesson->vocabs->makeHidden('pivot')->toArray();
        }
        return response()->json(["status" => "success", "data" => $vocabs], 200);
    }
}
