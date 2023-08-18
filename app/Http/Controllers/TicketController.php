<?php

namespace App\Http\Controllers;

use App\Models\ConferenceDay;
use App\Models\Ticket;
use App\Models\User;
use App\Models\UsersOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class TicketController extends Controller
{

    //INDEX
    public function index()
    {
        $tickets = Ticket::with('user','conference_day', 'users_offer')->get();
        return response()->json($tickets, 200);
    }

    //STORE
    public function store(Request $request)
    {
        try{
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'conference_days' => 'required|array',
                'conference_days.*' => 'required|exists:conference_days,id',
                'users_offer_id' => 'nullable|exists:users_offers,id',
            ]);

            $user = User::findOrFail($request->user_id);
            $usersOffer = UsersOffer::findOrFail($request->users_offer_id);

            $selectedDays = ConferenceDay::whereIn('id', $request->conference_days)->get();

            $totalDays = count($selectedDays);
            $totalPrice = 0;


            foreach ($selectedDays as $day) {
                $totalPrice += $day->price;
            }

            if ($usersOffer && $usersOffer->number_of_days <= $totalDays) {
                $pricePerDay = $usersOffer->price / $usersOffer->number_of_days;
                $usersOfferId = $usersOffer->id;
            } else {
                $pricePerDay = $totalPrice / $totalDays;
                $usersOffer = null;
                $usersOfferId = null;
            }

            $tickets = [];

            foreach ($selectedDays as $day) {
                $ticket = Ticket::create([
                    'user_id' => $request->user_id,
                    'users_offer_id' => $usersOfferId,
                    'price' => $pricePerDay,
                    'paid' => false,
                    'payment_date' => null,
                    'conference_day_id' => $day->id,
                ]);

                $tickets[] = $ticket;
            }

            return response()->json($tickets, 201);

        }catch (\Exception $exception){
            return response()->json($exception->getMessage());

        }
           }

    //UPDATE PAYMENT
    public function updatePayment(Request $request, $id)
    {
        try {
            $request->validate([
                'paid' => 'required|boolean',
                'payment_date' => [$request->paid ? 'required|date' : 'nullable|date'],
            ]);

            $ticket = Ticket::findOrFail($id);
            $user_id = $ticket->user_id;
            $sameCreationTimeTickets = Ticket::where('created_at', $ticket->created_at)->where('user_id', $user_id)->get();

            if($request->payment_date ==null && $request->paid){
                $date = now()->format('Y-m-d');
            }else{
                $date = $request->payment_date;
            }

            DB::beginTransaction();

            try {
                foreach ($sameCreationTimeTickets as $sameCreationTimeTicket) {
                    $sameCreationTimeTicket->update([
                        'paid' => $request->paid,
                        'payment_date' => date('Y-m-d', strtotime($date)),
                    ]);
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['message' => 'Error updating tickets.', 'ERROR' => $e->getMessage()], 500);
            }

            return response()->json($sameCreationTimeTickets, 200);

        }catch (\Exception $exception){
            return response()->json($exception->getMessage());

        }

    }

    //UPDATE
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'conference_days' => 'required|array|distinct',
                'conference_days.*' => 'required|exists:conference_days,id',
                'users_offer_id' => 'nullable',
            ]);

            $ticket = Ticket::findOrFail($id);
            $user_id = $ticket->user_id;
            $usersOffer = null;

            if ($request->users_offer_id !== null) {

                    $usersOffer = UsersOffer::find($request->users_offer_id);

            }
            if($request->users_offer_id != null && $usersOffer == null){

                return response()->json(["message" => 'ticket does not exist'], 404 );

            }else{
                $selectedDay = ConferenceDay::findOrFail($request->conference_days);
                $sameCreationTimeTickets = Ticket::where('created_at', $ticket->created_at)->where('user_id', $user_id)->get();

                if(count($sameCreationTimeTickets) != count($selectedDay)){
                    return response()->json('You reserved more/less days than the number you decided to edit! Do you want to delete/add some reservations?');
                }

                if ($usersOffer && $usersOffer->number_of_days <= count($sameCreationTimeTickets)) {
                    $pricePerDay = $usersOffer->price / count($sameCreationTimeTickets);
                    $usersOfferId = $usersOffer->id;
                } else {
                    $totalPrice = 0;
                    foreach ($sameCreationTimeTickets as $dayTicket) {
                        $totalPrice += $dayTicket->conference_day->price;
                    }
                    $pricePerDay = $totalPrice / count($sameCreationTimeTickets);
                    $usersOffer = null;
                    $usersOfferId = null;
                }

                DB::beginTransaction();

                try {
                    foreach ($sameCreationTimeTickets as $index=>$sameCreationTimeTicket) {
                        $sameCreationTimeTicket->update([
                            'user_id' => $request->user_id,
                            'users_offer_id' => $usersOfferId,
                            'price' => $pricePerDay,
                            'conference_day_id' => $selectedDay[$index]->id,
                        ]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json(['message' => 'Error updating tickets.', 'ERROR' => $e->getMessage()], 500);
                }

                return response()->json($sameCreationTimeTickets, 200);

            }


        } catch (\Mockery\Exception $exception) {
            return response()->json($exception->getMessage());
        }
    }

    //SHOW
    public function show($id)
    {
        $ticket = Ticket::with('user','conference_day', 'users_offer')->findOrFail($id);
        $creationTime = $ticket->created_at;
        $user_id = $ticket->user_id;

        $sameCreationTimeTickets = Ticket::where('created_at', $creationTime)
            ->where('user_id', $user_id)
            ->get();
        $total_price =0;
        foreach ($sameCreationTimeTickets as $tickets){
            $total_price += $tickets->price;
        }

        return response()->json(["tickets" => $sameCreationTimeTickets,"total_price" => $total_price], 200);
    }


    public function showTicketsForConferenceDay($conferenceDayId)
    {
        $tickets = Ticket::with('user','conference_day', 'users_offer')->
        where('conference_day_id', $conferenceDayId)->get();
        return response()->json($tickets->groupBy(['created_at', 'user_id']), 200);
    }

    public function showTicketsForConference($conferenceId)
    {
        $conferenceDayIds = ConferenceDay::where('conference_id', $conferenceId)->pluck('id');
        $tickets = Ticket::with('user','conference_day', 'users_offer')->
        whereIn('conference_day_id', $conferenceDayIds)->get();
        return response()->json($tickets->groupBy(['created_at', 'user_id']), 200);
    }
    public function showTicketsForUser($userId)
    {
        $tickets = Ticket::with('user','conference_day', 'users_offer')->
        where('user_id', $userId)->get();
        return response()->json($tickets->groupBy(['created_at', 'user_id']), 200);
    }

    public function showPaidTicketsForUser($userId)
    {
        $tickets = Ticket::with('user','conference_day', 'users_offer')->
        where('user_id', $userId)->where('paid', true)->get();
        return response()->json($tickets->groupBy(['created_at', 'user_id']), 200);
    }

    public function showUnpaidTicketsForUser($userId)
    {
        $tickets = Ticket::with('user','conference_day', 'users_offer')->
        where('user_id', $userId)->where('paid', false)->get();
        return response()->json($tickets->groupBy(['created_at', 'user_id']), 200);
    }

    public function showPaidTicketsForConference($conferenceId)
    {
        $conferenceDayIds = ConferenceDay::where('conference_id', $conferenceId)->pluck('id');
        $tickets = Ticket::with('user','conference_day', 'users_offer')->
        whereIn('conference_day_id', $conferenceDayIds)->where('paid', true)->get();
        return response()->json($tickets->groupBy(['created_at', 'user_id']), 200);
    }

    public function showUnpaidTicketsForConference($conferenceId)
    {
        $conferenceDayIds = ConferenceDay::where('conference_id', $conferenceId)->pluck('id');
        $tickets = Ticket::with('user','conference_day', 'users_offer')->
        whereIn('conference_day_id', $conferenceDayIds)->where('paid', false)->get();
        return response()->json($tickets->groupBy(['created_at', 'user_id']), 200);
    }



    //DESTROY
    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $user_id = $ticket->user_id;
            $sameCreationTimeTickets = Ticket::where('created_at', $ticket->created_at)
                ->where('user_id', $user_id)
                ->get();
            foreach ($sameCreationTimeTickets as $sameCreationTimeTicket) {

                $sameCreationTimeTicket->delete();
            }

            return response()->json(['message' => 'Ticket successfully deleted.'], 200);

        }catch (\Mockery\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
}
