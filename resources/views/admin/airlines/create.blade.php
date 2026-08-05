@extends('admin.layouts.app')

@section('title', 'Add Airline')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-slate-900">Add Airline</h1>
            <p class="mt-2 text-sm text-slate-500">Create a new airline master record for ticket inventory.</p>
        </div>

        <form action="{{ route('admin.airlines.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Name</span>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none" />
                    @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Code</span>
                    <input type="text" name="code" value="{{ old('code') }}" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none" />
                    @error('code')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Country</span>
                    <input type="text" name="country" value="{{ old('country') }}" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none" />
                    @error('country')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-slate-700">Status</span>
                    <select name="status" class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 focus:border-blue-500 focus:outline-none">
                        <option value="Active"{{ old('status') === 'Active' ? ' selected' : '' }}>Active</option>
                        <option value="Inactive"{{ old('status') === 'Inactive' ? ' selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="flex items-center justify-between gap-3 pt-4">
                <a href="{{ route('admin.airlines.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">Save Airline</button>
            </div>
        </form>
    </div>
</div>
@endsection
