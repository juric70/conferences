<?php

namespace App\Http\Controllers;

use App\Models\ConferenceDay;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Ramsey\Uuid\Type\Time;

class TimetableController extends Controller
{
    //INDEX
    public function index(){
        $timetables = Timetable::with('user', 'conference_day')->get();
        return response()->json($timetables, 200);
    }

    //STORE
    public function store(Request $request){

        try{
           $validator = Validator::make($request->all(),[
                'start_time' => 'required',
                'end_time' => 'required',
                'title' => 'required|max:255',
                'address' => 'required|max:255',
                'conference_room' => 'required|max:255',
                'description' => 'required',
                'available_seats' => 'required|numeric',
                'conference_day_id' => 'required|exists:conference_days,id',
                'user_id' => 'required|exists:users,id',
            ]);
            if($validator->fails()) {

                return response()->json($validator->errors(),422);
            }
            $timetable = Timetable::create([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'title' => $request->title,
                'address' => $request->address,
                'conference_room' => $request->conference_room,
                'description' => $request->description,
                'available_seats' => $request->available_seats,
                'conference_day_id' => $request->conference_day_id,
                'user_id' => $request->user_id,
            ]);

            return response()->json($timetable, 201);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    //SHOW
    public function show($id){
        try
        {
            $timetable = Timetable::with('user', 'conference_day')->findOrFail($id);
            return response()->json($timetable);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }


    //SHOW ALL TIMETABLES OF ONE CONFERENCE DAY

    public function show_timetable_of_one_conference_day($id){
        try{
            $timetable = Timetable::where('conference_day_id',$id)->get();
            return $timetable;
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }


    //UPDATE
    public function update(Request $request, $id){
        try{
           $validator = Validator::make($request->all(),[
                'start_time' => 'required',
                'end_time' => 'required',
                'title' => 'required|max:255',
                'address' => 'required|max:255',
                'conference_room' => 'required|max:255',
                'description' => 'required',
                'available_seats' => 'required|numeric',
                'conference_day_id' => 'required|exists:conference_days,id',
                'user_id' => 'required|exists:users,id',
            ]);
            if($validator->fails()) {

                return response()->json($validator->errors(),422);
            }
            $timetable = Timetable::with('user', 'conference_day')->findOrFail($id);
            $timetable->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'title' => $request->title,
                'address' => $request->address,
                'conference_room' => $request->conference_room,
                'description' => $request->description,
                'available_seats' => $request->available_seats,
                'conference_day_id' => $request->conference_day_id,
                'user_id' => $request->user_id,
            ]);
            return response()->json($timetable, 200);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }
    //DESTROY
    public function destroy($id){
        try
        {
            $timetable = Timetable::findOrFail($id);
            $timetable->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }

    }
}
