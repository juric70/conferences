<?php

namespace App\Http\Controllers;

use App\Models\City;
use http\Message;
use Illuminate\Http\Request;

class CityController extends Controller
{
    //INDEX
    public function index(){
        $cities = City::with('country')->get();
        return response()->json($cities, 200);
    }
    //STORE
    public function store(Request $request){

     try{
         $request->validate([
             'name' => 'required|max:255|unique:cities',
             'zip_code' => 'required|unique:cities|numeric',
             'country_id' => 'required|exists:countries,id',
         ]);

         $city = City::create([
             'name' => $request->name,
             'zip_code' => $request->zip_code,
             'country_id' => $request->country_id,
         ]);

         return response()->json($city, 201);
     }
     catch (\Exception $e){
         return response()->json($e->getMessage());

     }
    }

    //SHOW
    public function show($id){
        try
        {
            $city = City::with('country')->findOrFail($id);
            return response()->json($city);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
    //UPDATE
    public function update(Request $request, $id){
        try{
            $request->validate([
                'name' => 'required|max:255|unique:cities,name,' . $id,
                'zip_code' => 'required|numeric|unique:cities,zip_code,' . $id,
                'country_id' => 'required|exists:countries,id',
            ]);
            $city = City::with('country')->findOrFail($id);
            $city->update([
                'name' => $request->name,
                'zip_code' => $request->zip_code,
                'country_id' => $request->country_id,
            ]);
            return response()->json($city, 200);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }
    //DESTROY
    public function destroy($id){
        try
        {
            $city = City::findOrFail($id);
            $city->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
