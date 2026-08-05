@extends('visa_officer.layouts.app')

@section('title', 'Notifications | Visa Officer')

@section('content')
    <section class="glass-panel rounded-3xl p-6 shadow-xs border border-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Notifications</h1>
                <p class="mt-2 text-sm text-slate-500">Your latest unread and read notifications.</p>
            </div>
            <form action="{{ route('visa-office.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="rounded-2xl bg-blue-600 px-4 py-3 text-sm font-bold text-white hover:bg-blue-700 transition">Mark all read</button>
            </form>
        </div>

        @if($notifications->isEmpty())
            <div class="mt-6 rounded-3xl border border-dashed border-slate-300 bg-slate-50 p-6 text-center text-sm text-slate-500">No notifications available.</div>
        @else
            <div class="mt-6 space-y-3">
                @foreach($notifications as $notification)
                    <div class="rounded-3xl border {{ $notification->is_read ? 'border-slate-200 bg-white' : 'border-blue-300 bg-blue-50' }} p-4 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div>
                                <div class="text-sm font-bold text-slate-900">{{ $notification->title ?? 'Visa Notification' }}</div>
                                <div class="mt-1 text-sm text-slate-600">{{ $notification->message }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] uppercase tracking-wider {{ $notification->is_read ? 'text-slate-400' : 'text-blue-700' }} font-bold">{{ $notification->is_read ? 'Read' : 'Unread' }}</span>
                                @if(! $notification->is_read)
                                    <form action="{{ route('visa-office.notifications.mark-read', $notification) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-slate-900 px-3 py-2 text-[10px] font-bold text-white hover:bg-slate-800 transition">Mark read</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
