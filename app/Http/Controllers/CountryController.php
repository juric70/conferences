<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    //INDEX
    public function index(){
        try{
            $countires = Country::all();
            return response()->json($countires, 200);

        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //STORE
    public function store(Request $request){
        try
        {
            $request->validate([
                'name' => 'required|unique:countries|max:255',
                'code' => 'required|unique:countries|max:10',
            ]);
            $country = Country::create([
                'name' => $request->name,
                'code' => $request->code,
            ]);
            return response()->json($country, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //SHOW
    public function show($id){
        try
        {
            $country = Country::findOrFail($id);
            return response()->json($country);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //UPDATE
    public function update(Request $request, $id){
        try
        {
            $request->validate([
                'name' => 'required|max:255|unique:countries,name,' . $id,
                'code' => 'required|max:10|unique:countries,code,' . $id,
            ]);
            $country = Country::findOrFail($id);
            $country->update([
                'name' => $request->name,
                'code' => $request->code,
            ]);
            return response()->json($country, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }


    }

    //DISTROY
    public function destroy($id){
        try{
            $country = Country::findOrFail($id);
            $country->delete();
            return response()->json(204);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
