@extends('admin.layouts.app')

@section('title', 'Package Management')
@section('page-heading', 'Package Builder')
@section('page-description', 'Manage dynamic Umrah packages.')

@section('content')
<div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">
    
    {{-- Header --}}
    <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <h5 class="text-base sm:text-lg font-bold text-gray-900">All Packages</h5>
        <a href="{{ route('admin.packages.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-full shadow-sm transition">
            <i class="bi bi-plus-circle"></i> Build Package
        </a>
    </div>

    {{-- Mobile & Tablet Card View (Visible below 768px) --}}
    <div class="block md:hidden divide-y divide-gray-100">
        @forelse($packages as $pkg)
            <div class="p-4 space-y-3 bg-white">
                {{-- Title & Status --}}
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h6 class="font-bold text-gray-900 text-sm">{{ $pkg->title }}</h6>
                        <span class="text-xs text-gray-400 block mt-0.5">{{ $pkg->duration }}</span>
                    </div>
                    <div>
                        @if($pkg->status == 'Active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Active</span>
                        @elseif($pkg->status == 'Draft')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">Draft</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">{{ $pkg->status }}</span>
                        @endif
                    </div>
                </div>

                {{-- Details Grid --}}
                <div class="grid grid-cols-2 gap-2 pt-2 text-xs border-t border-gray-50">
                    <div>
                        <span class="text-gray-400 block">Airline & Route</span>
                        <span class="font-medium text-gray-800">{{ $pkg->airline ?? 'N/A' }}</span>
                        <span class="text-[11px] text-gray-400 block">{{ $pkg->origin }} &rarr; {{ $pkg->destination }}</span>
                    </div>

                    <div>
                        <span class="text-gray-400 block">Price</span>
                        <span class="font-bold text-gray-900 text-sm">SAR {{ number_format($pkg->price, 2) }}</span>
                    </div>

                    <div class="pt-1">
                        <span class="text-gray-400 block">Dates</span>
                        <span class="text-gray-700 block">
                            <i class="bi bi-calendar-event me-1 text-blue-500"></i>
                            {{ $pkg->departure_date ? $pkg->departure_date->format('M d, Y') : 'N/A' }}
                        </span>
                        <span class="text-gray-400 block">
                            <i class="bi bi-calendar-check me-1"></i>
                            {{ $pkg->return_date ? $pkg->return_date->format('M d, Y') : 'N/A' }}
                        </span>
                    </div>

                    <div class="pt-1">
                        <span class="text-gray-400 block">Seats</span>
                        <span class="inline-flex items-center px-2 py-0.5 mt-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $pkg->available_seats }} / {{ $pkg->total_seats }}
                        </span>
                    </div>
                </div>

                {{-- Mobile Actions --}}
                <div class="pt-2 flex items-center justify-end gap-2 border-t border-gray-50">
                    <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this package?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-400 p-4">
                <i class="bi bi-box-seam text-4xl text-gray-300 mb-2 block"></i>
                <p class="font-bold text-gray-700 text-sm">No Packages Found</p>
                <p class="text-xs text-gray-400 mt-0.5">Start by building a new package.</p>
            </div>
        @endforelse
    </div>

    {{-- Desktop Table View (Visible on 768px and above) --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                    <th class="pl-6 pr-4 py-3.5">TITLE</th>
                    <th class="px-4 py-3.5">AIRLINE & ROUTE</th>
                    <th class="px-4 py-3.5">DATES</th>
                    <th class="px-4 py-3.5">PRICE</th>
                    <th class="px-4 py-3.5">SEATS</th>
                    <th class="px-4 py-3.5">STATUS</th>
                    <th class="pr-6 pl-4 py-3.5 text-right">ACTIONS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($packages as $pkg)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="pl-6 pr-4 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $pkg->title }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $pkg->duration }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">{{ $pkg->airline ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $pkg->origin }} &rarr; {{ $pkg->destination }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-900 flex items-center gap-1">
                                <i class="bi bi-calendar-event text-blue-500"></i>
                                {{ $pkg->departure_date ? $pkg->departure_date->format('M d, Y') : 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-400 flex items-center gap-1 mt-0.5">
                                <i class="bi bi-calendar-check"></i>
                                {{ $pkg->return_date ? $pkg->return_date->format('M d, Y') : 'N/A' }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">SAR {{ number_format($pkg->price, 2) }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $pkg->available_seats }} / {{ $pkg->total_seats }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($pkg->status == 'Active')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">Active</span>
                            @elseif($pkg->status == 'Draft')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">Draft</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">{{ $pkg->status }}</span>
                            @endif
                        </td>
                        <td class="pr-6 pl-4 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.packages.edit', $pkg->id) }}" class="p-2 text-blue-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.packages.destroy', $pkg->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this package?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-rose-50 transition" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <div class="mb-2">
                                <i class="bi bi-box-seam text-4xl text-gray-300"></i>
                            </div>
                            <p class="font-bold text-gray-700">No Packages Found</p>
                            <p class="text-xs text-gray-400 mt-1">Start by building a new package.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($packages->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white overflow-x-auto">
            {{ $packages->links() }}
        </div>
    @endif
</div>
@endsection