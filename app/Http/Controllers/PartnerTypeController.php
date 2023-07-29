<?php

namespace App\Http\Controllers;

use App\Models\PartnerType;
use Illuminate\Http\Request;

class PartnerTypeController extends Controller
{

    //INDEX
    public function index(){
        try{
            $partner_types = PartnerType::all();
            return response()->json($partner_types, 200);

        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //STORE
    public function store(Request $request){
        try
        {
            $request->validate([
                'name' => 'required|unique:partner_types|max:255',
                'description' => 'max:255',
            ]);
            $partner_type = PartnerType::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return response()->json($partner_type, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //SHOW
    public function show($id){
        try
        {
            $partner_type = PartnerType::findOrFail($id);
            return response()->json($partner_type);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //UPDATE
    public function update(Request $request, $id){
        try
        {
            $request->validate([
                'name' => 'required|max:255|unique:pagit rtner_types,name,' . $id,
                'description' => 'max:255',
            ]);
            $partner_type = PartnerType::findOrFail($id);
            $partner_type->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return response()->json($partner_type, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }


    }

    //DISTROY
    public function destroy($id){
        try{
            $partner_type = PartnerType::findOrFail($id);
            $partner_type->delete();
            return response()->json(204);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
