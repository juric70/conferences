<?php

namespace App\Http\Controllers;

use App\Models\OrganizationsOffer;
use Illuminate\Http\Request;

class OrganizationsOfferController extends Controller
{

    //INDEX
    public function index(){
        try
        {
            $offers = OrganizationsOffer::all();
            return response()->json($offers);
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
                'kind' => 'required|unique:organizations_offers,kind|max:255',
                'publishable_conferences' => 'required|numeric',
                'price' => 'required|numeric',
                'description' => 'required'
            ]);
                $offer = OrganizationsOffer::create([
                'kind'=> $request->kind,
                'publishable_conferences'=> $request->publishable_conferences,
                'price'=> $request->price,
                'description'=> $request->description,
            ]);
            return response()->json($offer, 201);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }


    }

    //SHOW
    public function show (string $id){

        try
        {
            $offer = OrganizationsOffer::findOrFail($id);
            return response()->json($offer);
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
                'kind' => 'required|unique:organizations_offers,kind,' . $id .'|max:255',
                'publishable_conferences' => 'required|numeric',
                'price' => 'required|numeric',
                'description' => 'required'
            ]);
            $offer = OrganizationsOffer::findOrFail($id);
            $offer->update([
                'kind'=> $request->kind,
                'publishable_conferences'=> $request->publishable_conferences,
                'price'=> $request->price,
                'description'=> $request->description,
            ]);

            return response()->json($offer, 201);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }

    }

    //DESTROY
    public function destroy($id){

        try
        {
            $offer = OrganizationsOffer::findOrFail($id);
            $offer->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
