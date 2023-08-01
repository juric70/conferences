<?php

namespace App\Http\Controllers;

use App\Models\OrganizationsOffer;
use App\Models\UsersOffer;
use Illuminate\Http\Request;

class UsersOfferController extends Controller
{
    //INDEX
    public function index(){
        try
        {
            $offers = UsersOffer::all();
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
                'kind' => 'required|unique:users_offers,kind|max:255',
                'code' => 'required|unique:users_offers,code|max:50',
                'number_of_days' => 'required|numeric',
                'price' => 'required|numeric',
                'description' => 'required',
                'conference_id' => 'required|exists:conferences,id'
            ]);
            $offer = UsersOffer::create([
                'kind'=> $request->kind,
                'code'=> $request->code,
                'number_of_days'=> $request->number_of_days,
                'price'=> $request->price,
                'description'=> $request->description,
                'publishable_conferences'=> $request->publishable_conferences,
                'conference_id'=> $request->conference_id,
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
            $offer = UsersOffer::findOrFail($id);
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
                'kind' => 'required|unique:users_offers,kind,' . $id .'|max:255',
                'code' => 'required|unique:users_offers,code,' . $id . '|max:50',
                'number_of_days' => 'required|numeric',
                'price' => 'required|numeric',
                'description' => 'required',
                'conference_id' => 'required|exists:conferences,id'
            ]);
            $offer = UsersOffer::findOrFail($id);
            $offer->update([
                'kind'=> $request->kind,
                'code'=> $request->code,
                'number_of_days'=> $request->number_of_days,
                'price'=> $request->price,
                'description'=> $request->description,
                'publishable_conferences'=> $request->publishable_conferences,
                'conference_id'=> $request->conference_id,
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
            $offer = UsersOffer::findOrFail($id);
            $offer->delete();
            return response()->json(204);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());

        }

    }
}
