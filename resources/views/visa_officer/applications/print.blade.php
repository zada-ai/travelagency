<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Visa Application #{{ $application->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 24px; color: #111; }
        .header { text-align: center; margin-bottom: 24px; }
        .section { margin-bottom: 18px; }
        .section-title { font-weight: bold; margin-bottom: 8px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 6px; }
        .label { font-weight: 700; width: 40%; }
        .value { width: 55%; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; background: #0f766e; color: #fff; font-size: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .table th, .table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background: #f3f4f6; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Visa Application #{{ $application->id }}</h1>
        <p>{{ $application->customer_name }} &middot; {{ $application->passport_number }}</p>
        <span class="badge">Status: {{ $application->status }}</span>
    </div>

    <div class="section">
        <div class="section-title">Applicant Details</div>
        <div class="row"><span class="label">Customer Name</span><span class="value">{{ $application->customer_name }}</span></div>
        <div class="row"><span class="label">Passport Number</span><span class="value">{{ $application->passport_number }}</span></div>
        <div class="row"><span class="label">Nationality</span><span class="value">{{ $application->nationality }}</span></div>
        <div class="row"><span class="label">Travel Date</span><span class="value">{{ optional($application->travel_date)->format('M d, Y') }}</span></div>
        <div class="row"><span class="label">Return Date</span><span class="value">{{ optional($application->return_date)->format('M d, Y') ?? 'N/A' }}</span></div>
    </div>

    <div class="section">
        <div class="section-title">Visa Details</div>
        <div class="row"><span class="label">Visa Type</span><span class="value">{{ $application->visaType->name ?? 'N/A' }}</span></div>
        <div class="row"><span class="label">Travel Agent</span><span class="value">{{ $application->travelAgent->company_name ?? 'Direct Client' }}</span></div>
        <div class="row"><span class="label">Assigned Officer</span><span class="value">{{ $application->visaOfficer->name ?? 'Unassigned' }}</span></div>
        <div class="row"><span class="label">Remarks</span><span class="value">{{ $application->remarks ?? 'None' }}</span></div>
    </div>

    <div class="section">
        <div class="section-title">Pricing</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Visa Fee</td><td>{{ number_format($application->visa_fee ?? 0, 2) }}</td></tr>
                <tr><td>Service Charges</td><td>{{ number_format($application->service_charges ?? 0, 2) }}</td></tr>
                <tr><td><strong>Total</strong></td><td><strong>{{ number_format($application->total_amount ?? 0, 2) }}</strong></td></tr>
            </tbody>
        </table>
    </div>
</body>
</html>
