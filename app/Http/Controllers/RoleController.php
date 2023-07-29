<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoleController extends Controller
{
    //INDEX
    public function index(){
        try
        {
            $roles = Role::all();
            return response()->json($roles);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }
    //STORE
    public function store(Request $request){

        try
        {
            $request->validate([
                'name' => 'required|unique:roles|max:255',
            ]);
            $role = Role::create([
                'name'=> $request->name,
            ]);
            return response()->json($role, 201);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }


    }

    //SHOW
    public function show (string $id){

        try
        {
            $role = Role::findOrFail($id);
            return response()->json($role);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //UPDATE
    public function update(Request $request, $id){

        try
        {
            $request->validate([
                'name' => 'required|unique:roles|max:255' . $id,
            ]);
            $role = Role::findOrFail($id);
            $role->update([
                'name'=> $request->name,
            ]);

            return response()->json($role, 201);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //DESTROY
    public function destroy($id){

        try
        {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
