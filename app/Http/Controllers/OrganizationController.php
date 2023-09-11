<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationsOffer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{

     //INDEX
    public function index(){
        $organizations = Organization::with('user', 'city', 'organization_type')->get();
        return response()->json($organizations, 200);
    }
    //SHOW ALL ORGANIZATIONS OF USER
    public function show_organizations_of_user($id){
        $organizations = Organization::where('user_id', $id)->with('user', 'city', 'organization_type')->get();
        return response()->json($organizations, 200);
    }

    //STORE
    public function store(Request $request){

        try{
            $validator = Validator::make($request->all(),[
                'name' => 'required|max:255|unique:organizations',
                'address' => 'required|max:255',
                'description' => 'max:255',
                'city_id' => 'required|exists:cities,id',
                'user_id' => 'required|exists:users,id',
                'organization_type_id' => 'required|exists:organization_types,id',
            ]);
            if($validator->fails()) {

                return response()->json($validator->errors(),422);
            }

            $organization = Organization::create([
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,
                'approved' => false,
                'publishable_number' => 0,
                'usage_number' => 0,
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
          $validator=Validator::make($request->all(),[
                'name' => 'required|max:255|unique:organizations,name,' . $id,
                'address' => 'required|max:255',
                'description' => 'max:255',

                'city_id' => 'required|exists:cities,id',
                'user_id' => 'required|exists:users,id',
                'organization_type_id' => 'required|exists:organization_types,id',

            ]);

            if($validator->fails()) {

                return response()->json($validator->errors(),422);
            }
            $organization = Organization::with('city', 'user', 'organization_type')->findOrFail($id);
            $organization->update([
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,

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

    //UPDATE APPROVAL -STAVITI U URL
    public function updateApproval(Request $request, $id){
        try{
            $request->validate([
                'approved'=> 'boolean|required',

            ]);
            $organization = Organization::with('city', 'user', 'organization_type')->findOrFail($id);
            $organization->update([
                'approved' => $request->approved,
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


    //**********************SUBSCRIPTIONS**************************************************

    //STORE ORGANIZATION SUBSCRIPTION

    public function storeOrganizationSubscription(Request $request, $organization_id){

        try {
            $organization = Organization::findOrFail($organization_id);
            $request->validate([

                'organizations_offer_id' => 'required|exists:organizations_offers,id'
            ]);
            $organizations_offer_id = $request->input('organizations_offer_id');

            $subscription = $organization->organizations_offers()->attach($organizations_offer_id, [
                'paid' => false,

            ]);

            return response()->json('ok',201);

        }
        catch (\Exception $e){
            return response()->json($e->getMessage(),400);
        }
    }


    //UPDATE PAYMENT (Paid and its date)
    public function updatePaymentStatus(Request $request, $id){
        try {
            $request->validate([
                'paid' => 'required|boolean',
                'payment_date' => $request->paied ? 'required_if:paid,true|date': 'nullable|date'
            ]);

            $subscription = DB::table('offer_organizations')->where('id',$id)->first();

            if ($subscription == null){
                return response()->json('Subscription not found', 404);
            }
            $organization_id = $subscription->organization_id;
            $organization = Organization::findOrFail($organization_id);

            $publishable_number = $organization->publishable_number;
            $usage_number = $organization->usage_number;
            $available_to_publish = $publishable_number-$usage_number;

            $offer_id = $subscription->organizations_offer_id;
            $offer = OrganizationsOffer::findOrFail($offer_id);
            $publishable_conferences_offer = $offer->publishable_conferences;

            if ($request->paid && ($subscription->paid == false || $subscription->paid == null)){

                $payment_date = $request->payment_date;
                $publishable_number += $publishable_conferences_offer;

                DB::table('offer_organizations')->where('id', $id)->update([
                    'paid' => $request->paid,
                    'payment_date' => $payment_date
                ]);

                $organization->update([
                    'publishable_number' => $publishable_number
                ]);

                return response()->json('Organization' . $organization->name . 'payd for subscription, now they can publish ' . $publishable_number . ' conferences' , 201);

            }elseif ($request->paid && $subscription->paid==true ){
                return response()->json('Already paid!', 200);

            }
            else{
                $payment_date = null;
                if ($available_to_publish >= $publishable_conferences_offer){
                    $publishable_number -= $publishable_conferences_offer;
                    DB::table('offer_organizations')->where('id', $id)->update([
                        'paid' => $request->paid,
                        'payment_date' => $payment_date
                    ]);

                    Organization::update([
                        'publishable_number' => $publishable_number
                    ]);
                    return response()->json( 'Canceled subscription for ' . $organization->name, 201);

                }else{
                    return response()->json('Not possible to cancel subscription because you used it already!');
                }



            }

        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }


    //SHOW ALL SUBSCRIPTIONS OF ONE ORGANIZATION
    public function showAllSubscriptionsOfOrganization ($id){
        try {
            $organization = Organization::findOrFail($id);
            $subscription = $organization->organizations_offers;

            return response()->json($subscription, 200);
        } catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    //SHOW ALL PAID SUBSCRIPTIONS OF ONE ORGANIZATION
    public function showAllPaidSubscriptionsOfOrganization ($id){
        try {
            $organization = Organization::findOrFail($id);
            $subscription = $organization->organizations_offers->where('pivot.paid', true);

            return response()->json($subscription, 200);
        } catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    //SHOW ALL UNPAID SUBSCRIPTIONS OF ONE ORGANIZATION
    public function showAllUnpaidSubscriptionsOfOrganization ($id){
        try {
            $organization = Organization::findOrFail($id);
            $subscription = $organization->organizations_offers->where('pivot.paid', false);

            return response()->json($subscription, 200);
        } catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    //SHOW ALL ORGANIZATIONS THAT HAVE SAME SUBSCRIPTION

    public function showAllOrganisationsOfSubscription($id){

        try {
            $offer = OrganizationsOffer::findOrFail($id);
            $organizations = $offer->organizations->where('pivot.paid', true);

            return response()->json($organizations, 200);
        }
        catch (\Exception $e){
            return response()->json($e->getMessage());
        }
    }

    //DESTROY SUBSCRIPTION
    public function destroySubscription($id)
    {
        try {

           $subscription = DB::table('offer_organizations')->where('id', $id)->first();
            if (!$subscription) {
                return response()->json('Subscription not found.', 404);
            }

           $org_id = $subscription->organization_id;
           $offer_id = $subscription->organizations_offer_id;
           $organization = Organization::findOrFail($org_id);
           $used = $organization->usage_number;
           $publishable = $organization->publishable_number;
           $available = $publishable - $used;
           $offer = OrganizationsOffer::findOrFail($offer_id);

           if($subscription->paid==false){
               DB::table('offer_organizations')->where('id', $id)->delete();
               return response()->json(['message' => 'Successfully deleted.'], 200);
           }elseif($subscription->paid == true && $available>=$offer->publishable_conferences){
               DB::table('offer_organizations')->where('id', $id)->delete();
               $publishable -= $offer->publishable_conferences;
               $organization->update([
                   'publishable_number' => $publishable
               ]);
               return response()->json(['message' => 'Successfully deleted.'], 200);
           }else{
               return response()->json(['message' => 'Subscription is already user therefore it can not be deleted'], 400);
           }

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }


}
