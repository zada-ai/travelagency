@extends('admin.layouts.app')

@section('title', 'Visa Product Configuration')
@section('page-heading', 'Visa Types')
@section('page-description', 'Manage visa pricing models and base ERP charges.')

@section('content')
<section class="space-y-6">

    <header class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs flex flex-wrap justify-between items-center gap-4 bg-white border border-slate-200">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-blue-600 block mb-1">Configuration</span>
            <h2 class="text-xl font-bold text-slate-900">Visa Product Catalog</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.visa-types.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 hover:bg-blue-700 text-xs font-bold text-white px-4 py-2.5 shadow-sm transition">
                Add Visa Product
            </a>
            <a href="{{ route('admin.visa-management') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 hover:bg-slate-800 text-xs font-bold text-white px-4 py-2.5 shadow-sm transition">
                Back to Dashboard
            </a>
        </div>
    </header>

    <article class="glass-panel rounded-3xl p-5 md:p-6 shadow-xs bg-white border border-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-bold uppercase tracking-wider">
                        <th class="pb-3 pl-3">Product ID</th>
                        <th class="pb-3">Name</th>
                        <th class="pb-3">Code</th>
                        <th class="pb-3">Base Fee</th>
                        <th class="pb-3">Service Charge</th>
                        <th class="pb-3">Total Cost</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 pr-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($visaTypes as $type)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3.5 pl-3 font-bold text-slate-900">#{{ $type->id }}</td>
                            <td class="py-3.5 text-slate-850">{{ $type->name }}</td>
                            <td class="py-3.5 font-mono text-slate-800 font-semibold">{{ $type->code }}</td>
                            <td class="py-3.5 text-slate-600">SAR {{ number_format($type->base_fee, 2) }}</td>
                            <td class="py-3.5 text-slate-600">SAR {{ number_format($type->service_charge, 2) }}</td>
                            <td class="py-3.5 font-bold text-blue-600">SAR {{ number_format($type->base_fee + $type->service_charge, 2) }}</td>
                            <td class="py-3.5">
                                @if($type->is_active)
                                    <span class="rounded-full bg-emerald-50 text-emerald-600 px-2 py-0.5 text-[9px] font-bold uppercase">Active</span>
                                @else
                                    <span class="rounded-full bg-slate-100 text-slate-500 px-2 py-0.5 text-[9px] font-bold uppercase">Inactive</span>
                                @endif
                            </td>
                            <td class="py-3.5 pr-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.visa-types.edit', $type) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-slate-650 hover:text-amber-600 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 20.089a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                    </a>

                                    <form action="{{ route('admin.visa-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Delete this visa product config? All dependent records will be deleted.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-slate-50 border border-slate-200 text-slate-650 hover:bg-rose-50 hover:text-rose-600 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 font-bold">
                                No Visa Types are currently configured in database catalog.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </article>
</section>
@endsection
