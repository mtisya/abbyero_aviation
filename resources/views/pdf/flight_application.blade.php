<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Flight Application</title>

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
        
        <div class="title">✈ Flight School Application</div>
        <p style="margin:0; font-size:12px;">
            <strong>Reference:</strong> {{ now()->format('YmdHis') }}
        </p>
    </div>

    <div class="section">
        <h3>Applicant Information</h3>
        <p><strong>Name:</strong> {{ $data['name'] }}</p>
        <p><strong>Email:</strong> {{ $data['email'] }}</p>
        <p><strong>Phone:</strong> {{ $data['phone'] }}</p>
        <p><strong>Message:</strong> {{ $data['message'] ?? 'N/A' }}</p>
        <p><strong>Submitted At:</strong> {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="footer">
        Abbyero Aviation • Texas, America 
        | Email: support@abbyeroaviation.com  
        | Phone: +1 (915) 9995352 
        <br>© {{ date('Y') }} Abbyero Aviation. All rights reserved.
    </div>

</body>
</html>