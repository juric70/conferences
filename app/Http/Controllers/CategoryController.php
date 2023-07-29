<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //INDEX
    public function index(){
        try{
            $categories = Category::all();
            return response()->json($categories, 200);

        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //STORE
    public function store(Request $request){
        try
        {
            $request->validate([
                'name' => 'required|unique:categories|max:255',
                'color' => 'required',
            ]);
            $category = Category::create([
                'name' => $request->name,
                'color' => $request->color,
            ]);
            return response()->json($category, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //SHOW
    public function show($id){
        try
        {
            $category = Category::findOrFail($id);
            return response()->json($category);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }

    //UPDATE
    public function update(Request $request, $id){
        try
        {
            $request->validate([
                'name' => 'required|max:255|unique:categories,name,' . $id,
                'color' => 'required',
            ]);
            $category = Category::findOrFail($id);
            $category->update([
                'name' => $request->name,
                'color' => $request->color,
            ]);
            return response()->json($category, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }


    }

    //DISTROY
    public function destroy($id){
        try{
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json(204);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
