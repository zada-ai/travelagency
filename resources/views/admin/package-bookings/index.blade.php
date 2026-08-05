@extends('admin.layouts.app')

@section('title', 'Package Bookings')
@section('page-heading', 'Package Bookings')
@section('page-description', 'Manage dynamic Umrah package bookings.')

@section('content')

<div class="bg-white border border-gray-200 shadow-sm rounded-2xl overflow-hidden">

    {{-- Header --}}
    <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h5 class="text-base sm:text-lg font-bold text-gray-900">All Bookings</h5>
            <p class="text-xs text-gray-500 mt-0.5">
                Review, approve or cancel customer package bookings.
            </p>
        </div>

        <div class="self-start sm:self-auto">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                {{ $bookings->total() }} Total
            </span>
        </div>
    </div>


    {{-- Mobile & Tablet Card View (Visible below 768px) --}}
    <div class="block md:hidden divide-y divide-gray-100">
        @forelse($bookings as $booking)
            @php
                $totalSeats = (int) $booking->adults + (int) $booking->children + (int) $booking->infants;
            @endphp

            <div class="p-4 space-y-3 bg-white">
                
                {{-- Ref & Status --}}
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <span class="font-bold text-blue-600 text-sm block">
                            {{ $booking->reference_number }}
                        </span>
                        <span class="text-[11px] text-gray-400">
                            {{ $booking->created_at->format('M d, Y h:i A') }}
                        </span>
                    </div>

                    <div>
                        @if($booking->status === 'Approved')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                <i class="bi bi-check-circle-fill"></i> Approved
                            </span>
                        @elseif($booking->status === 'Pending')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                <i class="bi bi-clock-fill"></i> Pending
                            </span>
                        @elseif($booking->status === 'Cancelled')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                <i class="bi bi-x-circle-fill"></i> Cancelled
                            </span>
                        @elseif($booking->status === 'Completed')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                                <i class="bi bi-check2-all"></i> Completed
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                {{ $booking->status }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Details Grid --}}
                <div class="grid grid-cols-2 gap-2 pt-2 text-xs border-t border-gray-50">
                    <div>
                        <span class="text-gray-400 block">Package</span>
                        <span class="font-medium text-gray-800">{{ $booking->package->title ?? 'N/A' }}</span>
                        @if($booking->package)
                            <span class="text-[11px] text-gray-400 block">{{ $booking->package->airline ?? 'N/A' }}</span>
                        @endif
                    </div>

                    <div>
                        <span class="text-gray-400 block">Total Price</span>
                        <span class="font-bold text-gray-900 text-sm">SAR {{ number_format($booking->total_price, 2) }}</span>
                    </div>

                    <div class="col-span-2 pt-1">
                        <span class="text-gray-400 block">Customer</span>
                        <span class="font-semibold text-gray-800">{{ $booking->contact_name }}</span>
                        <span class="text-gray-400 block">{{ $booking->contact_phone }} · {{ $booking->contact_email }}</span>
                    </div>

                    <div class="col-span-2 pt-1">
                        <span class="text-gray-400 block">Seats</span>
                        <span class="font-bold text-gray-800">{{ $totalSeats }} Seats</span>
                        <span class="text-[11px] text-gray-400 block">
                            Adults: {{ $booking->adults }} · Children: {{ $booking->children }} · Infants: {{ $booking->infants }}
                        </span>
                    </div>
                </div>

                {{-- Mobile Actions --}}
                <div class="pt-2 flex flex-wrap items-center justify-end gap-2 border-t border-gray-50">
                    
                    <a
                        href="{{ route('admin.package-bookings.show', $booking->id) }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50"
                    >
                        <i class="bi bi-eye"></i> View
                    </a>

                    @if($booking->status === 'Pending')
                        <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Approved">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700"
                                onclick="return confirm('Are you sure you want to approve this booking?')"
                            >
                                <i class="bi bi-check-lg"></i> Approve
                            </button>
                        </form>

                        <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Cancelled">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50"
                                onclick="return confirm('Are you sure you want to cancel this booking?')"
                            >
                                <i class="bi bi-x-lg"></i> Cancel
                            </button>
                        </form>
                    @endif

                    @if($booking->status === 'Approved')
                        <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Completed">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700"
                                onclick="return confirm('Mark this booking as completed?')"
                            >
                                <i class="bi bi-check2-all"></i> Complete
                            </button>
                        </form>

                        <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Cancelled">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50"
                                onclick="return confirm('Cancel this approved booking?')"
                            >
                                <i class="bi bi-x-lg"></i> Cancel
                            </button>
                        </form>
                    @endif

                </div>

            </div>
        @empty
            <div class="text-center py-10 text-gray-400 p-4">
                <i class="bi bi-box-seam text-4xl text-gray-300 mb-2 block"></i>
                <p class="font-bold text-gray-700 text-sm">No Package Bookings Found</p>
            </div>
        @endforelse
    </div>


    {{-- Desktop Table View (Visible on 768px and above) --}}
    <div class="hidden md:block overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                    <th class="pl-6 pr-4 py-3.5">REFERENCE #</th>
                    <th class="px-4 py-3.5">PACKAGE</th>
                    <th class="px-4 py-3.5">CUSTOMER</th>
                    <th class="px-4 py-3.5">SEATS</th>
                    <th class="px-4 py-3.5">TOTAL PRICE</th>
                    <th class="px-4 py-3.5">STATUS</th>
                    <th class="pr-6 pl-4 py-3.5 text-right">ACTIONS</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 text-sm">

                @forelse($bookings as $booking)

                    @php
                        $totalSeats = (int) $booking->adults + (int) $booking->children + (int) $booking->infants;
                    @endphp

                    <tr class="hover:bg-gray-50/60 transition-colors">

                        {{-- Reference --}}
                        <td class="pl-6 pr-4 py-4 whitespace-nowrap">
                            <div class="font-bold text-blue-600">
                                {{ $booking->reference_number }}
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $booking->created_at->format('M d, Y h:i A') }}
                            </div>
                        </td>

                        {{-- Package --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-medium">
                                {{ $booking->package->title ?? 'N/A' }}
                            </div>
                            @if($booking->package)
                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ $booking->package->airline ?? 'N/A' }}
                                </div>
                            @endif
                        </td>

                        {{-- Customer --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-semibold">
                                {{ $booking->contact_name }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $booking->contact_phone }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $booking->contact_email }}
                            </div>
                        </td>

                        {{-- Seats --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-800">
                                {{ $totalSeats }} Seats
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                Adults: {{ $booking->adults }} · Children: {{ $booking->children }} · Infants: {{ $booking->infants }}
                            </div>
                        </td>

                        {{-- Price --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">
                                SAR {{ number_format($booking->total_price, 2) }}
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($booking->status === 'Approved')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <i class="bi bi-check-circle-fill"></i> Approved
                                </span>
                            @elseif($booking->status === 'Pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                                    <i class="bi bi-clock-fill"></i> Pending
                                </span>
                            @elseif($booking->status === 'Cancelled')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200/60">
                                    <i class="bi bi-x-circle-fill"></i> Cancelled
                                </span>
                            @elseif($booking->status === 'Completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60">
                                    <i class="bi bi-check2-all"></i> Completed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $booking->status }}
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="pr-6 pl-4 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">

                                {{-- View --}}
                                <a
                                    href="{{ route('admin.package-bookings.show', $booking->id) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition"
                                    title="View Booking"
                                >
                                    <i class="bi bi-eye"></i> View
                                </a>

                                {{-- Pending Actions --}}
                                @if($booking->status === 'Pending')
                                    <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Approved">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition"
                                            onclick="return confirm('Are you sure you want to approve this booking?')"
                                        >
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50 transition"
                                            onclick="return confirm('Are you sure you want to cancel this booking? The held seats will be released.')"
                                        >
                                            <i class="bi bi-x-lg"></i> Cancel
                                        </button>
                                    </form>
                                @endif

                                {{-- Approved Actions --}}
                                @if($booking->status === 'Approved')
                                    <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Completed">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition"
                                            onclick="return confirm('Mark this booking as completed?')"
                                        >
                                            <i class="bi bi-check2-all"></i> Complete
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Cancelled">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-rose-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50 transition"
                                            onclick="return confirm('Cancel this approved booking? Its held seats will be released.')"
                                        >
                                            <i class="bi bi-x-lg"></i> Cancel
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            <div class="mb-2">
                                <i class="bi bi-box-seam text-4xl text-gray-300"></i>
                            </div>
                            <p class="font-bold text-gray-700">No Package Bookings Found</p>
                            <p class="text-xs text-gray-400 mt-1">Customer package bookings will appear here.</p>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    @if($bookings->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white overflow-x-auto">
            {{ $bookings->links() }}
        </div>
    @endif

</div>

@endsection