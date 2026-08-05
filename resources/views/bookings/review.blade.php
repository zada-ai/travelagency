@extends('layouts.app')

@section('content')
    {{-- Reuse agent review view but within customer layout --}}
    @includeIf('travel_agents.bookings.review', ['ticket' => $ticket, 'booking' => $booking])
@endsection
