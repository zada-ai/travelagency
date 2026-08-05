<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <!-- Google Fonts: Plus Jakarta Sans for high-end typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background: #ffffff;
            margin: 0;
            padding: 30px;
        }

        h1 {
            font-size: 22px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }

        p.subtitle {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        .header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            text-align: left;
        }

        th {
            border-bottom: 2px solid #e2e8f0;
            padding: 10px 8px;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #f1f5f9;
            padding: 12px 8px;
            color: #334155;
        }

        .status-pill {
            background: #f1f5f9;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px dashed #e2e8f0;
            padding-top: 15px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }

        .print-btn-bar {
            margin-bottom: 20px;
            text-align: right;
        }

        .btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        @media print {
            .print-btn-bar {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button onclick="window.print()" class="btn">Print PDF Report</button>
        <button onclick="window.close()" class="btn" style="background: #f1f5f9; color: #475569;">Close</button>
    </div>

    <div class="header">
        <div>
            <h1>{{ $title }}</h1>
            <p class="subtitle">Generated on {{ now()->format('d M Y H:i:s') }}</p>
        </div>
        <div style="text-align: right;">
            <span style="font-size: 10px; font-weight: 800; color: #94a3b8; uppercase">ERP Audit Sheet</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Passport No</th>
                <th>Visa Product Type</th>
                <th>Travel Agent</th>
                <th>Status</th>
                <th style="text-align: right;">Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
                <tr>
                    <td style="font-weight: 700;">#{{ $app->id }}</td>
                    <td>{{ $app->customer_name }}</td>
                    <td style="font-family: monospace;">{{ $app->passport_number }}</td>
                    <td>{{ $app->visaType?->name }}</td>
                    <td>{{ $app->travelAgent?->company_name ?? 'Direct' }}</td>
                    <td><span class="status-pill">{{ $app->status }}</span></td>
                    <td style="text-align: right; font-weight: 700;">SAR {{ number_format($app->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Hujaj Umrah ERP Visa Reports Platform. Total Records: {{ count($applications) }}.
    </div>

</body>
</html>
