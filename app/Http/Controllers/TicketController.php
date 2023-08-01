<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    //INDEX
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json($tickets, 200);
    }

    //STORE
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'conference_day_id' => 'required|exists:conference_days,id',
            'users_offer_id' => 'nullable|exists:users_offers,id',
            'price' => 'required|integer',
        ]);

        $ticket = Ticket::create([
            'user_id' => $request->user_id,
            'conference_day_id' => $request->conference_day_id,
            'users_offer_id' => $request->users_offer_id,
            'price' => $request->price,
            'paid' => false,
            'payment_date' => null,
        ]);

        return response()->json($ticket, 201);
    }

    //UPDATE PAYMENT
    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'paid' => 'required|boolean',
            'payment_date' => $request->paid ? 'required|date' : 'nullable|date',
        ]);

        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'paid' => $request->paid,
            'payment_date' => $request->payment_date,
        ]);

        return response()->json($ticket, 200);
    }

    //UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'exists:users,id',
            'conference_day_id' => 'exists:conference_days,id',
            'users_offer_id' => 'nullable|exists:users_offers,id',
            'price' => 'integer',
            'paid' => 'boolean',
            'payment_date' => $request->paid ? 'required|date' : 'nullable|date',
        ]);

        $ticket = Ticket::findOrFail($id);

        $ticket->update($request->all());

        return response()->json($ticket, 200);
    }

    //SHOW
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        return response()->json($ticket, 200);
    }

    //DESTROY
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();
        return response()->json(['message' => 'Ticket successfully deleted.'], 200);
    }
}
