@extends('admin.layouts.app')

@section('title', 'Booking Details: ' . $booking->reference_number)
@section('page-heading', 'Booking Details')
@section('page-description', 'View and manage package booking.')

@section('content')
<div class="row g-4">
    <form action="{{ route('admin.package-bookings.update', $booking->id) }}" method="POST" class="w-100">
        @csrf
        @method('PUT')
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-xl mb-4">
                <div class="card-header bg-white border-bottom border-light py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark">Passengers List</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>DOB</th>
                                <th>Passport No</th>
                                <th>Documents</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($booking->passengers as $index => $passenger)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-medium text-dark">{{ $passenger->name }}</td>
                                <td><span class="badge bg-secondary">{{ $passenger->type }}</span></td>
                                <td>{{ $passenger->dob->format('M d, Y') }}</td>
                                <td>
                                    <input type="hidden" name="passengers[{{ $index }}][id]" value="{{ $passenger->id }}" />
                                    <input
                                        type="text"
                                        name="passengers[{{ $index }}][passport_number]"
                                        value="{{ old('passengers.' . $index . '.passport_number', $passenger->passport_number ?? '') }}"
                                        class="form-control form-control-sm rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900"
                                        placeholder="Enter passport #"
                                        required
                                    />
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ Storage::url($passenger->cnic_document) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="View CNIC">CNIC</a>
                                        <a href="{{ Storage::url($passenger->passport_document) }}" target="_blank" class="btn btn-sm btn-outline-info" title="View Passport">Passport</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-xl mb-4">
            <div class="card-header bg-white border-bottom border-light py-3">
                <h6 class="mb-0 fw-bold text-dark">Booking Overview</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-4">
                    <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Reference:</span>
                        <span class="fw-bold text-primary">{{ $booking->reference_number }}</span>
                    </li>
                    <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Status:</span>
                        <span class="fw-bold">{{ $booking->status }}</span>
                    </li>
                    <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Total Price:</span>
                        <span class="fw-bold text-dark">SAR {{ number_format($booking->total_price, 2) }}</span>
                    </li>
                    <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Package:</span>
                        <span class="fw-bold text-dark text-end">{{ $booking->package->title ?? 'N/A' }}</span>
                    </li>
                    <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Contact Name:</span>
                        <span class="fw-medium">{{ $booking->contact_name }}</span>
                    </li>
                    <li class="mb-3 d-flex justify-content-between border-bottom pb-2">
                        <span class="text-muted">Contact Email:</span>
                        <span class="fw-medium">{{ $booking->contact_email }}</span>
                    </li>
                    <li class="d-flex justify-content-between">
                        <span class="text-muted">Contact Phone:</span>
                        <span class="fw-medium">{{ $booking->contact_phone }}</span>
                    </li>
                </ul>

                <h6 class="fw-bold text-dark mb-3">Update Status</h6>
                <div class="input-group">
                    <select name="status" class="form-select" required>
                        <option value="Pending" {{ $booking->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $booking->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Cancelled" {{ $booking->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="Completed" {{ $booking->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="btn btn-primary fw-bold">Save Changes</button>
                </div>
                @if($booking->status === 'Approved')
    <div class="mt-4">
        <a href="{{ route('admin.package-bookings.voucher', $booking->id) }}"
           target="_blank"
           class="btn btn-success w-100 fw-bold">
            <i class="bi bi-file-earmark-text me-1"></i>
            Create / View Voucher
        </a>
    </div>
@endif
            </div>
        </div>
    </div>
    </form>
</div>
@endsection
