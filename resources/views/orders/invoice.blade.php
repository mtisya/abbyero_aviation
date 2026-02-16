<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        /* Header Logo */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 100px;
            width: auto;
            border-radius: 12px;
        }

        /* Info Row */
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .info-left,
        .info-right {
            width: 48%;
        }

        .info-left {
            text-align: left;
            font-size: 13px;
        }

        .info-right {
            text-align: right;
            font-size: 13px;
        }

        .info-right h2 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        /* Items Table */
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items th,
        .items td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .items th {
            background: #f4f6f8;
            text-align: left;
        }

        /* Totals */
        .total {
            text-align: right;
            font-size: 15px;
            font-weight: bold;
            margin-top: 15px;
        }

        /* Signature */
        .signature {
            margin-top: 50px;
            text-align: right;
        }

        .signature p {
            margin-bottom: 50px;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-left: auto;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>

<body>
    <!-- Header Logo -->
    <div class="header">
        <img src="{{ public_path('assets/images/logos/abbyerologo.png') }}" alt="Company Logo">
    </div>

    <!-- Invoice & Company Info Row -->
    <table style="width: 100%; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
        <tr>
            <!-- Left: Invoice & Customer Info -->
            <td style="width: 50%; text-align: left; vertical-align: top; font-size: 13px; line-height: 1.6;">
                <p>
                    <strong>Invoice #: </strong>{{ $order->id }}<br>
                    <strong>Date: </strong>{{ $order->created_at->format('d M Y') }}<br>
                    <strong>Customer: </strong>{{ Auth::user()->name }}<br>
                    <strong>Email: </strong>{{ Auth::user()->email }}<br>
                    <strong>Status: </strong>{{ ucfirst($order->status) }}
                </p>
            </td>

            <!-- Right: Company Info -->
            <td style="width: 50%; text-align: right; vertical-align: top; font-size: 13px; line-height: 1.6;">
                <h2 style="margin: 0; font-size: 18px;">Abbyerro Aviation Services</h2>
                <p style="margin: 2px 0;">
                    11357 Lindenwood Ave El Paso Texas.<br>
                    Phone: +1 (915) 9995352<br>
                    Email: info@abbyeroaviation.com
                </p>
            </td>
        </tr>
    </table>


    <!-- Items Table -->
    <table class="items">
        <thead>
            <tr>
                <th style="width: 40%;">Item</th>
                <th style="width: 15%;">Qty</th>
                <th style="width: 20%;">Price</th>
                <th style="width: 25%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Grand Total -->
    <p class="total">Grand Total: ${{ number_format($order->total, 2) }}</p>

    <!-- Signature -->
    <div class="signature">
        <p>Authorized Signature</p>
        <div class="signature-line"></div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>Generated on {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>

</html>