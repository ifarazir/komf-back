<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vocab;
use Illuminate\Http\Request;

class UserVocabController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Vocab $vocab)
    {
        //
        if (!is_null($vocab)) {
            return response()->json(["status" => "success", "data" => $vocab], 200);
        } else {
            return response()->json(["status" => "failed", "message" => "Failed! no Vocab found"], 200);
        }
    }
}
