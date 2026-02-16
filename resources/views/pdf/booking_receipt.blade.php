<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Flight Ticket</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 10px;
            line-height: 1.15;
            position: relative;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 35%;
            left: 18%;
            opacity: 0.08;
            font-size: 90px;
            transform: rotate(-25deg);
            z-index: -1;
            color: #999;
        }

        .header {
            text-align: center;
            margin-bottom: 8px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .section {
            margin-top: 8px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #cfcfcf;
            background: #f8f8f8;
        }

        .section h3 {
            font-size: 14px;
            margin-bottom: 4px;
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 2px;
        }

        .section p {
            margin: 3px 0;
        }

        /* Center QR */
        .qr {
            text-align: center;
            margin-top: 10px;
        }

        .qr img {
            width: 140px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 18px;
            font-size: 10px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <!-- Watermark -->
    <div class="watermark">
        ABBYERO AVIATION
    </div>

    <div class="header">
        <img 
            src="{{ public_path('assets/images/logos/abbyerologo.png') }}" 
            style="width:150px; margin-bottom:2px;"
        >
        
        <div class="title">✈ Flight Booking Ticket</div>
        <p style="margin:0; font-size:12px;">
            <strong>Reference:</strong> {{ $booking->reference }}
        </p>
    </div>


    <div class="section">
        <h3>User Information</h3>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
    </div>

    <div class="section">
        <h3>Flight Information</h3>
        <p><strong>Aircraft:</strong> {{ $flight->aircraft_model }} ({{ $flight->registration_number }})</p>
        <p><strong>From:</strong> {{ $flight->departure_location }}</p>
        <p><strong>Departure:</strong> {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y H:i') }}</p>
        <p><strong>To:</strong> {{ $flight->arrival_location }}</p>
        <p><strong>Arrival:</strong> {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d M Y H:i') }}</p>
        <p><strong>Price:</strong> ${{ number_format($flight->price, 2) }}</p>
    </div>

    <div class="section">
        <h3>Booking Details</h3>
        <p><strong>Booking ID:</strong> {{ $booking->id }}</p>
        <p><strong>Status:</strong> {{ $booking->status }}</p>
        <p><strong>Booked At:</strong> {{ $booking->created_at->format('d M Y H:i') }}</p>
    </div>

    <!-- Centered QR Code -->
    <div class="qr">
        <p style="margin-bottom:4px;"><strong>Scan QR Code:</strong></p>
        @if(!empty($qrCodePng))
            <img src="data:image/png;base64,{{ $qrCodePng }}" alt="QR Code">
        @else
            <p style="color:red; font-size:11px;">QR Code unavailable — missing booking reference.</p>
        @endif
    </div>

    <div class="footer">
        Abbyero Aviation • Texas, America 
        | Email: support@abbyeroaviation.com  
        | Phone: +1 (915) 9995352 
        <br>© {{ date('Y') }} Abbyero Aviation. All rights reserved.
    </div>

</body>
</html>
