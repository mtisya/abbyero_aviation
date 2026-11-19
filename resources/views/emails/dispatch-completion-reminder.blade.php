<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Flight Completion Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <div style="max-width: 600px; margin: auto; padding: 25px;">

        <h2 style="color: #072743;">
            Abbyero Aviation
        </h2>

        <h3>Flight Completion Reminder</h3>

        <p>Hello {{ $dispatch->schedule->user->name }},</p>

        <p>
            This is a reminder that your flight has been dispatched
            and is still awaiting completion in the system.
        </p>

        <div style="background: #f4f6f8; padding: 18px; border-radius: 6px;">

            <p>
                <strong>Dispatch Number:</strong>
                {{ $dispatch->dispatch_no }}
            </p>

            <p>
                <strong>Aircraft:</strong>
                {{ $dispatch->aircraft->aircraft_model ?? 'N/A' }}
            </p>

            <p>
                <strong>Registration:</strong>
                {{ $dispatch->aircraft->registration_number ?? 'N/A' }}
            </p>

            <p>
                <strong>Dispatch Date:</strong>
                {{ $dispatch->dispatch_time?->format('d M Y H:i') ?? 'N/A' }}
            </p>

        </div>

        <p>
            Please complete the required flight logbook process
            so that the flight can be recorded as completed.
        </p>

        <p>
            If you have already completed the flight and submitted
            your logbook, no further action is necessary once the
            completion has been processed.
        </p>

        <p>
            Regards,<br>
            <strong>Abbyero Aviation</strong>
        </p>

    </div>

</body>
</html>