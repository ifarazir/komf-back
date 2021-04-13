<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vocab;
use Illuminate\Support\Facades\Validator;

class AdminVocabController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $vocabs = Vocab::paginate(5);
        if (count($vocabs) > 0) {
            return response()->json(["status" => "success", "count" => count($vocabs), "data" => $vocabs->makeHidden(['created_at','updated_at'])], 200);
        } else {
            return response()->json(["status" => "failed", "count" => count($vocabs), "message" => "Failed! no Vocab found"], 200);
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
            "word" => "required",
            "syn" => "required",
            "def" => "required",
            "ex1" => "required",
            "ex2" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $VocabInput = $request->all();

        $vocab = Vocab::create($VocabInput);
        if (!is_null($vocab)) {
            return response()->json(["status" => "success", "message" => "Success! Vocab created", "data" => $vocab]);
        } else {
            return response()->json(["status" => "failed", "message" => "Whoops! Vocab not created"]);
        }
    }

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
