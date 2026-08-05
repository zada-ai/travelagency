@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Notifications</h2>
    @if($notifications->isEmpty())
        <div class="bg-white p-4 rounded shadow">No notifications.</div>
    @else
        <ul class="space-y-3">
            @foreach($notifications as $note)
                <li class="bg-white p-4 rounded shadow">{{ $note->data['message'] ?? $note->data ?? $note->type }}</li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
