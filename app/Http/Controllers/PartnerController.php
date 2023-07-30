<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{

    //SHOW ALL ORGANIZATIONS THAT HAS SAME PARTNER_TYPE IN CONFERENCE
    public function showPartnersWithSameType($conference_id, $type_id){

        try {
            $conference = Conference::findOrFail($conference_id);
            $partners = $conference->partner()
                ->where('partner_type_id', $type_id)
                ->with('organization')
                ->get();
            return response()->json($partners, 200);
        }
        catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }


    }

    //UPDATE
    public function update(Request $request, $id){
        try {
            $request->validate([
                'description' => 'required',
                'organization_id' => 'required|exists:organizations,id',
                'conference_id' => 'required|exists:conferences,id',
                'partner_type_id' => 'required|exists:partner_types,id',
            ]);

            $partner = Partner::findOrFail($id);
            $partner->update([
                'description' => $request->description,
                'organization_id' => $request->organization_id,
                'conference_id' => $request->conference_id,
                'partner_type_id' => $request->partner_type_id,
            ]);

            return response()->json($partner, 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    //DESTROY
    public function destroy($id){
        try {
            $partner = Partner::findOrFail($id);
            $partner->delete();
            return response()->json(204);
        }
        catch (\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }


}
