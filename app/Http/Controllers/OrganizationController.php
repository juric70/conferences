<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{

     //INDEX
    public function index(){
        $organizations = Organization::with('user', 'city', 'organization_type')->get();
        return response()->json($organizations, 200);
    }

    //STORE
    public function store(Request $request){

        try{
            $request->validate([
                'name' => 'required|max:255|unique:organizations',
                'address' => 'required|max:255',
                'description' => 'max:255',
                'city_id' => 'required|exists:cities,id',
                'user_id' => 'required|exists:users,id',
                'organization_type_id' => 'required|exists:organization_types,id',
            ]);

            $organization = Organization::create([
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,
                'approved' => false,
                'city_id' => $request->city_id,
                'user_id' => $request->user_id,
                'organization_type_id' => $request->organization_type_id,
            ]);

            return response()->json($organization, 201);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //SHOW
    public function show($id){
        try
        {
            $organization = Organization::with('city', 'user', 'organization_type')->findOrFail($id);
            return response()->json($organization);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
    //UPDATE
    public function update(Request $request, $id){
        try{
            $request->validate([
                'name' => 'required|max:255|unique:organizations,name,' . $id,
                'address' => 'required|max:255',
                'description' => 'max:255',
                'approved'=> 'boolean|required',
                'city_id' => 'required|exists:cities,id',
                'user_id' => 'required|exists:users,id',
                'organization_type_id' => 'required|exists:organization_types,id',

            ]);
            $organization = Organization::with('city', 'user', 'organization_type')->findOrFail($id);
            $organization->update([
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,
                'approved' => $request->approved,
                'city_id' => $request->city_id,
                'user_id' => $request->user_id,
                'organization_type_id' => $request->organization_type_id,
            ]);
            return response()->json($organization, 200);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }
    //DESTROY
    public function destroy($id){
        try
        {
            $organization = Organization::findOrFail($id);
            $organization->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
