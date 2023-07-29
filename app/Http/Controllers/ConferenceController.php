<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    //INDEX
    public function index(){
        try {
            $conferences = Conference::with('user')->with('city')->get();
            return response()->json($conferences);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }

    }

    //STORE
    public function store(Request $request){
        try {
            $request->validate([
                'name'=>'required|max:255|unique:conferences',
                'description'=>'required',
                'starting_date'=>'required|date',
                'ending_date'=>'required|date|after_or_equal:starting_date',
                'user_id'=>'required|exists:users,id',
                'city_id'=>'required|exists:cities,id',
            ]);
            $conference=Conference::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'starting_date'=>$request->starting_date,
                'ending_date'=>$request->ending_date,
                'user_id'=>$request->user_id,
                'city_id'=>$request->city_id,
            ]);
            return response()->json($conference, 200);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
    //SHOW
    public function show($id){
        try
        {
            $conference = Conference::with('city')->with('user')->findOrFail($id);
            return response()->json($conference);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }
    //UPDATE
    public function update($id, Request $request){
        try {
            $request->validate([
                'name'=>'required|max:255|unique:conferences,name,' . $id,
                'description'=>'required',
                'starting_date'=>'required|date',
                'ending_date'=>'required|date|after_or_equal:starting_date',
                'user_id'=>'required|exists:users,id',
                'city_id'=>'required|exists:cities,id',
            ]);
            $conference = Conference::with('user')->with('city')->findOrFail($id);
            $conference->update([
                'name'=>$request->name,
                'description'=>$request->description,
                'starting_date'=>$request->starting_date,
                'ending_date'=>$request->ending_date,
                'user_id'=>$request->user_id,
                'city_id'=>$request->city_id,
            ]);
            return response()->json($conference, 200);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
    //DESTROY
    public function destroy($id){
        try
        {
            $conference = Conference::findOrFail($id);
            $conference->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
