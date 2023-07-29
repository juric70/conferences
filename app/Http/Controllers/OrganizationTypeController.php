<?php

namespace App\Http\Controllers;

use App\Models\OrganizationType;
use Illuminate\Http\Request;

class OrganizationTypeController extends Controller
{
    //INDEX
    public function index(){
        try{
            $organization_types = OrganizationType::all();
            return response()->json($organization_types, 200);

        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //STORE
    public function store(Request $request){
        try
        {
            $request->validate([
                'name' => 'required|unique:organization_types|max:255',
                'description' => 'max:255',
            ]);
            $organization_type = OrganizationType::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return response()->json($organization_type, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //SHOW
    public function show($id){
        try
        {
            $organization_type = OrganizationType::findOrFail($id);
            return response()->json($organization_type);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //UPDATE
    public function update(Request $request, $id){
        try
        {
            $request->validate([
                'name' => 'required|max:255|unique:organization_types,name,' . $id,
                'description' => 'max:255',
            ]);
            $organization_type = OrganizationType::findOrFail($id);
            $organization_type->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return response()->json($organization_type, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }


    }

    //DISTROY
    public function destroy($id){
        try{
            $organization_type = OrganizationType::findOrFail($id);
            $organization_type->delete();
            return response()->json(204);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
