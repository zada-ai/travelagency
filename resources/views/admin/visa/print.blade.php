<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visa Application Print Voucher | #{{ $application->id }}</title>
    <!-- Google Fonts: Plus Jakarta Sans for high-end typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 20px;
        }

        .print-card {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .header-bar {
            background: #0f172a;
            color: #ffffff;
            padding: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-bar h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .header-bar p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #94a3b8;
            font-weight: 600;
        }

        .status-badge {
            background: #3b82f6;
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .content-body {
            padding: 24px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .details-item {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            padding: 12px 16px;
            border-radius: 16px;
        }

        .details-item span.label {
            font-size: 9px;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            display: block;
        }

        .details-item span.value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 4px;
            display: block;
        }

        .billing-summary {
            background: #f0f7ff;
            border: 1px solid #e0f2fe;
            padding: 16px 20px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
        }

        .billing-summary h3 {
            margin: 0;
            font-size: 12px;
            font-weight: 800;
            color: #0369a1;
            text-transform: uppercase;
        }

        .billing-summary p.price {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            color: #0c4a6e;
        }

        .footer-bar {
            text-align: center;
            padding: 20px;
            border-top: 1px dashed #e2e8f0;
            font-size: 10px;
            color: #64748b;
            font-weight: 500;
        }

        .print-btn-bar {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }

        @media print {
            .print-btn-bar {
                display: none;
            }
            body {
                padding: 0;
            }
            .print-card {
                border: none;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button onclick="window.print()" class="btn">Print Voucher</button>
        <button onclick="window.close()" class="btn btn-secondary">Close Window</button>
    </div>

    <div class="print-card">
        <div class="header-bar">
            <div>
                <h1>Umrah ERP Visa Voucher</h1>
                <p>Application Reference: #VISA-{{ str_pad($application->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <span class="status-badge">{{ $application->status }}</span>
        </div>

        <div class="content-body">
            <div class="details-grid">
                <div class="details-item">
                    <span class="label">Customer Name</span>
                    <span class="value">{{ $application->customer_name }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Nationality</span>
                    <span class="value">{{ $application->nationality }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Passport Number</span>
                    <span class="value" style="font-family: monospace;">{{ $application->passport_number }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Passport Expiry</span>
                    <span class="value">{{ $application->passport_expiry?->format('d M Y') }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Departure Date</span>
                    <span class="value">{{ optional($application->travel_from)->format('d M Y') ?? $application->travel_from }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Return Date</span>
                    <span class="value">{{ optional($application->travel_to)->format('d M Y') ?? $application->travel_to ?? 'N/A' }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Visa Product type</span>
                    <span class="value">{{ $application->visaType?->name }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Booking Agent</span>
                    <span class="value">{{ $application->travelAgent?->company_name ?? 'Direct Client' }}</span>
                </div>
            </div>

            <div class="details-grid">
                <div class="details-item" style="grid-column: span 2;">
                    <span class="label">Remarks / Administrative Remarks</span>
                    <span class="value" style="font-weight: 500;">{{ $application->remarks ?? 'No specific instructions logged.' }}</span>
                </div>
            </div>

            <div class="billing-summary">
                <div>
                    <h3>Estimated Visa Fee & ERP Charges</h3>
                    <span style="font-size: 10px; color: #64748b; font-weight: 600;">(Base: SAR {{ number_format($application->visa_fee, 2) }} + Svc: SAR {{ number_format($application->service_charges, 2) }})</span>
                </div>
                <p class="price">SAR {{ number_format($application->total_amount, 2) }}</p>
            </div>
        </div>

        <div class="footer-bar">
            This is a system generated print voucher from Hujaj Umrah ERP System.<br>
            Verification Date: {{ now()->format('d M Y H:i:s') }}
        </div>
    </div>

</body>
</html>
