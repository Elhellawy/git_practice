<?php

namespace App\Http\Controllers;

use App\Poll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Poll AS PollResource;


class PollsController extends Controller
{
    public function index(Request $request){

        return response()->json(Poll::paginate(2),200);
    }
    public function show($id){
        $poll=Poll::find($id);
        if(is_null($poll))
            return response()->json(null,404);

        $poll=Poll::with('questions')->findOrFail($id);
        $response=New PollResource($poll,200);
        return response()->json($response,200);
    }

    public function store(Request $request){
        $rules=['title'=>'required|max:10'];
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $poll=Poll::create($request->all());
        return response()->json($poll,201);
    }
    public function update(Request $request,Poll $poll){
        $poll->update($request->all());
        return response()->json($poll,200);
    }
    public function delete(Request $request,Poll $poll){
        $poll->delete();
        return response()->json(null,204);
    }
    public function errors(Request $request)
    {
        return response()->json(['msg'=>'Payment Is Required'],501);

    }
    public function questions(Request $request, Poll $poll)
    {
        $questions = $poll->questions;
        return response()->json($questions, 200);
    }

}
