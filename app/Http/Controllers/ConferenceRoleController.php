<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRole;
use Illuminate\Http\Request;

class ConferenceRoleController extends Controller
{
    //INDEX
    public function index(){
        try{
            $conference_roles = ConferenceRole::all();
            return response()->json($conference_roles, 200);

        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //STORE
    public function store(Request $request){
        try
        {
            $request->validate([
                'name' => 'required|unique:conference_roles|max:255',
                'description' => 'max:255',
            ]);
            $conference_role = ConferenceRole::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return response()->json($conference_role, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //SHOW
    public function show($id){
        try
        {
            $conference_role = ConferenceRole::findOrFail($id);
            return response()->json($conference_role);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //UPDATE
    public function update(Request $request, $id){
        try
        {
            $request->validate([
                'name' => 'required|max:255|unique:conference_roles,name,' . $id,
                'description' => 'max:255',
            ]);
            $conference_role = ConferenceRole::findOrFail($id);
            $conference_role->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);
            return response()->json($conference_role, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }


    }

    //DISTROY
    public function destroy($id){
        try{
            $conference_role = ConferenceRole::findOrFail($id);
            $conference_role->delete();
            return response()->json(204);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
