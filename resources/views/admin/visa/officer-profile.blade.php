@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-md shadow">
        <h2 class="text-2xl font-bold mb-4">My Profile</h2>
        <div class="grid grid-cols-1 gap-4">
            <div><strong>Name:</strong> {{ $user->name }}</div>
            <div><strong>Employee ID:</strong> {{ $user->employee_id ?? 'N/A' }}</div>
            <div><strong>Email:</strong> {{ $user->email }}</div>
            <div><strong>Phone:</strong> {{ $user->phone ?? $user->mobile ?? 'N/A' }}</div>
            <div><strong>Department:</strong> {{ $user->department ?? 'N/A' }}</div>
            <div><strong>Designation:</strong> {{ $user->designation ?? 'N/A' }}</div>
            <div><strong>Role:</strong> {{ method_exists($user, 'getRoleNames') ? $user->getRoleNames()->first() : 'User' }}</div>
        </div>
    </div>
</div>
@endsection
