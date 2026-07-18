<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class PublicTicketController extends Controller
{
    public function show(Ticket $ticket)
    {
        if ($ticket->status !== 'Approved') {
            abort(404);
        }

        return view('user.ticket', compact('ticket'));
    }
}
