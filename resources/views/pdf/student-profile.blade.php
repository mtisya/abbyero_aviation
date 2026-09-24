<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        h2, h3 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; text-align: center; }
        .header { margin-bottom: 10px; text-align: left; }
        .charts { display: flex; justify-content: space-around; margin-top: 20px; }
        .chart { width: 48%; }
        .signature { margin-top: 30px; text-align: left; }
        .logo { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="logo">
    <img src="{{ $logoBase64 }}" alt="Logo" width="120">
</div>

<h2>FLIGHT TRAINING LOGBOOK</h2>

<div class="header">
    <strong>Name:</strong> {{ $student->user->name }}<br>
    <strong>Email:</strong> {{ $student->user->email }}<br>
    <strong>Total Hours:</strong> {{ $totalHours }}<br>
    <strong>Approved Hours:</strong> {{ $approvedHours }}<br>
    <strong>Pending Hours:</strong> {{ $totalHours - $approvedHours }}
</div>

<hr>

<h3>Flight Records</h3>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Aircraft</th>
            <th>Route</th>
            <th>Dual</th>
            <th>Solo</th>
            <th>Total</th>
            <th>Status</th>
            <th>Duration (hrs)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            @php
                $duration = round($log->hours, 2);
            @endphp
            <tr>
                <td>{{ $log->flight_date }}</td>
                <td>{{ $log->aircraft }}</td>
                <td>{{ $log->route }}</td>
                <td>{{ $log->type == 'dual' ? $log->hours : '' }}</td>
                <td>{{ $log->type == 'solo' ? $log->hours : '' }}</td>
                <td>{{ $log->hours }}</td>
                <td>
                    @if($log->approved)
                        ✔
                    @else
                        Pending
                    @endif
                </td>
                <td>{{ $duration }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Monthly Flight Analytics -->
<h3>Monthly Flight Summary</h3>
<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Total Flown Hours</th>
        </tr>
    </thead>
    <tbody>
        @foreach($monthlySummary as $month => $hours)
            <tr>
                <td>{{ $month }}</td>
                <td>{{ number_format($hours, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Charts side by side -->
<table width="100%" style="margin-top: 20px; border: none;">
    <tr>
        <!-- Left Chart: Doughnut -->
        <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
            <h3>Training Progress</h3>
            <img src="{{ $base64Doughnut }}" width="90%" style="display:block; margin:auto;">
        </td>

        <!-- Right Chart: Bar -->
        <td style="width: 50%; text-align: center; vertical-align: top; border: none;">
            <h3>Scheduled vs Flown Hours</h3>
            <img src="{{ $base64Bar }}" width="90%" style="display:block; margin:auto;">
        </td>
    </tr>
</table>

<div class="signature">
    <strong>Instructor Sign-off:</strong> ___________________________<br>
    <em>Date:</em> ___________________
</div>

</body>
</html>