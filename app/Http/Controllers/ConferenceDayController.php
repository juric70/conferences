<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\ConferenceDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConferenceDayController extends Controller
{

    //INDEX
    public function index(){
        $conference_days = ConferenceDay::with('conference', 'conference.city')->get();
        return response()->json($conference_days, 200);
    }

    public function index_timetables(){
        $conference_days = ConferenceDay::with('conference', 'conference.city', 'timetable')->get();
        return response()->json($conference_days, 200);
    }
    //STORE
    public function store(Request $request){

        try{
           $validator = Validator::make( $request->all(),[
                'day_number' => 'required|numeric|unique:conference_days,day_number,null,id,conference_id, ' . $request->conference_id,
                'price' => 'required|numeric',
                'date' => 'required|date',
                'conference_id' => 'required|exists:conferences,id',
            ]);
            if($validator->fails()) {

                return response()->json($validator->errors(),422);
            }

            $conference_day = ConferenceDay::create([
                'day_number' => $request->day_number,
                'price' => $request->price,
                'date' => $request->date,
                'conference_id' => $request->conference_id,
            ]);

            return response()->json($conference_day->id, 201);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }
    //STORE CATEGORY
    public function storeCategory(Request $request, $id)
    {
        try{
            $conference_day = ConferenceDay::findOrFail($id);
            $request->validate([
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
            ]);

            $categories = $request->input('categories');


            $conference_day->categories()->syncWithoutDetaching($categories);
            return response()->json($conference_day, 201);

        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //SHOW
    public function show($id){
        try
        {
            $conference_day = ConferenceDay::with('conference', 'conference.city', 'categories',  'timetable', 'timetable.user')->findOrFail($id);
            return response()->json($conference_day);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
    //UPDATE
    public function update(Request $request, $id){
        try{
            $request->validate([

                'price' => 'required|numeric',

            ]);
            $conference_day = ConferenceDay::with('conference', 'conference.city', 'categories',  'timetable', 'timetable.user')->findOrFail($id);
            $conference_day->update([

                'price' => $request->price,

            ]);
            return response()->json($conference_day, 200);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }
    //DESTROY
    public function destroy($id){
        try
        {
            $conference_day = ConferenceDay::findOrFail($id);
            $conference_day->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //DESTROY CATEGORY
    public function destroyCategory(Request $request, $id)
    {
        try{
            $conference_day = ConferenceDay::findOrFail($id);
            $request->validate([
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
            ]);

            $categories = $request->input('categories');


            $conference_day->categories()->detach($categories);
            return response()->json( 204);

        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

}
