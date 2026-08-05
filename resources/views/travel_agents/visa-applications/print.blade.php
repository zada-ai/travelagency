<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Visa Application #{{ $visaApplication->id }} | Agent Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background-color: #fff;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .page {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 32px;
        }
        .section {
            margin-bottom: 24px;
        }
        .section-header {
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #2563eb;
            font-size: 11px;
            font-weight: 700;
        }
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 22px;
            background-color: #ffffff;
        }
        .row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        .label {
            font-size: 11px;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 8px;
        }
        .value {
            font-size: 16px;
            color: #111827;
            font-weight: 600;
            line-height: 1.55;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 32px;
        }
        .footer-item {
            color: #475569;
            font-size: 13px;
        }
        @media print {
            body { background-color: #fff; }
            .page { box-shadow: none; margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="section">
            <div class="card">
                <div class="row">
                    <div>
                        <div class="label">Visa Application #</div>
                        <div class="value">{{ $visaApplication->id }}</div>
                    </div>
                    <div>
                        <div class="label">Submitted</div>
                        <div class="value">{{ $visaApplication->created_at?->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Customer Information</div>
            <div class="card">
                <div class="row">
                    <div>
                        <div class="label">Customer Name</div>
                        <div class="value">{{ $visaApplication->customer_name }}</div>
                    </div>
                    <div>
                        <div class="label">Passport Number</div>
                        <div class="value">{{ $visaApplication->passport_number }}</div>
                    </div>
                    <div>
                        <div class="label">Nationality</div>
                        <div class="value">{{ $visaApplication->nationality }}</div>
                    </div>
                    <div>
                        <div class="label">Passport Expiry</div>
                        <div class="value">{{ $visaApplication->passport_expiry?->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Visa Details</div>
            <div class="card">
                <div class="row">
                    <div>
                        <div class="label">Visa Type</div>
                        <div class="value">{{ $visaApplication->visaType?->name }}</div>
                    </div>
                    <div>
                        <div class="label">Status</div>
                        <div class="value">{{ ucfirst($visaApplication->status) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Fees</div>
            <div class="card">
                <div class="row">
                    <div>
                        <div class="label">Visa Fee</div>
                        <div class="value">SAR {{ number_format($visaApplication->visa_fee, 2) }}</div>
                    </div>
                    <div>
                        <div class="label">Service Charges</div>
                        <div class="value">SAR {{ number_format($visaApplication->service_charges, 2) }}</div>
                    </div>
                    <div>
                        <div class="label">Total Amount</div>
                        <div class="value">SAR {{ number_format($visaApplication->total_amount, 2) }}</div>
                    </div>
                    <div>
                        <div class="label">Assigned Officer</div>
                        <div class="value">{{ $visaApplication->visaOfficer?->name ?? 'Not assigned' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Remarks</div>
            <div class="card">
                <div class="value">{{ $visaApplication->remarks ?? 'No remarks were added for this application.' }}</div>
            </div>
        </div>

        <div class="section footer no-print">
            <div class="footer-item">Generated by Hujaj Umrah Agent Portal</div>
            <div class="footer-item">Print date: {{ now()->format('d M Y, H:i') }}</div>
        </div>
    </div>
</body>
</html>
