<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Organization;
use App\Models\Partner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    //INDEX
    public function index(){
        try {
            $conferences = Conference::with('organization')->with('city')->get();
            return response()->json($conferences);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }

    }
    //SHOW ALL CONFERENCES OF AN CREATOR
public function show_all_conferences_of_creator(int $id){
    try {
        $conferences = Conference::whereHas('organization', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->with('city' )
            ->with('conference_day.categories')
            ->with('conference_day.timetable')
            ->with('conference_day.timetable.user')->with('organization')
            ->get();

        return $conferences;

    }catch(\Exception $exception){
        return response()->json($exception->getMessage());
    }

}
    //SHOW ALL CONFERENCES AND THEIRS COLORS
    public function index_colors(){
        try {
            $conferences = Conference::
            with('organization')
                ->with('city' )
                ->with('conference_day.categories')
                ->with('conference_day.timetable')
                ->with('conference_day.timetable.user')

                ->where('starting_date', '>=' , now())
                ->orderBy('starting_date')
                ->get();

            return response()->json($conferences);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }

    }

    //STORE
    public function store(Request $request){
        try {

            $validator = Validator::make($request->all(),[
                'name'=>'required|max:255|unique:conferences',
                'description'=>'required',
                'starting_date'=>'required|date',
                'ending_date'=>'required|date|after_or_equal:starting_date',
                'organization_id'=>'required|exists:organizations,id',
                'city_id'=>'required|exists:cities,id',
            ]);
            if($validator->fails()) {

                return response()->json($validator->errors(),422);
            }
            $organization = Organization::findOrFail($request->organization_id);
            $usage_number = $organization->usage_number;
            if($organization->publishable_number - $usage_number >= 1){
                $conference=Conference::create([
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'starting_date'=>$request->starting_date,
                    'ending_date'=>$request->ending_date,
                    'organization_id'=>$request->organization_id,
                    'city_id'=>$request->city_id,
                ]);
                $usage_number += 1;
                $organization->update([
                    'usage_number' => $usage_number
                ]);
                return response()->json($conference->id, 200);
            }else{
                return response()->json('You must get new subscription :)', 400);
            }

        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }


    //ADD PARTNERS
    public function storePartners(Request $request, $id){
        try {
            $request->validate([
                'description' => 'required',
                'organization_id' => 'required|array',
                'organization_id.*' => 'exists:organizations,id',
                'partner_type_id' => 'required|exists:partner_types,id'
            ]);
            $conference = Conference::findOrFail($id);
            $description = $request->description;
            $organization_ids = $request->input('organization_id');
            $partner_type_id = $request->input('partner_type_id');

            foreach ($organization_ids as $organization_id){
                $partner = Partner::firstOrCreate([
                    'conference_id' => $conference->id,
                    'organization_id' => $organization_id,
                    'partner_type_id' => $partner_type_id,
                ], [
                    'description' => $description,
                ]);
            }

            return response()->json("sve okej", 200);
        }
        catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }

    }
    //SHOW
    public function show($id){
        try
        {
            $conference = Conference::with('city')
                ->with('organization')
                ->with('conference_day.timetable')
                ->with('conference_day.timetable.user')
                ->with('city' )
                ->with('conference_day.categories')
                ->findOrFail($id);
            return response()->json($conference);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }
//show user id by conference
    public function show_creator_id($id){
        try
        {
            $conference = Conference::with('city')
                ->with('organization')
                ->with('conference_day.timetable')
                ->with('conference_day.timetable.user')
                ->with('city' )
                ->with('conference_day.categories')
                ->findOrFail($id);
            return response()->json($conference->organization->user_id);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }
    //SHOW with organizatios
    public function showConferencePartners($id){
        try
        {
            $conference = Conference::with('city')->with('organization')->with('partner.organization')->findOrFail($id);
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
                'city_id'=>'required|exists:cities,id',
            ]);
            $conference = Conference::with('organization')->with('city')->findOrFail($id);
            $conference->update([
                'name'=>$request->name,
                'description'=>$request->description,
                'starting_date'=>$request->starting_date,
                'ending_date'=>$request->ending_date,
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
