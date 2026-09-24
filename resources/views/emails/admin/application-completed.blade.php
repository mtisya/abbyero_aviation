<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Onboarding Completed</title>
</head>

<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial, sans-serif;">

    <div style="max-width:650px; margin:30px auto; background:#ffffff; padding:30px;">

        <div style="text-align:center; margin-bottom:25px;">

            <img
                src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                alt="Abbyero Aviation"
                style="max-width:180px;"
            >

        </div>

        <h2 style="color:#435891;">
            Flight School Application Completed
        </h2>

        <p>
            A flight school applicant has successfully completed
            their onboarding process.
        </p>

        <hr>

        <p>
            <strong>Applicant:</strong>
            {{ $application->name }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ $application->email }}
        </p>

        <p>
            <strong>Phone:</strong>
            {{ $application->phone }}
        </p>

        @if ($application->program)
            <p>
                <strong>Program:</strong>
                {{ $application->program }}
            </p>
        @endif

        @if ($application->preferred_start_date)
            <p>
                <strong>Preferred Start Date:</strong>
                {{ $application->preferred_start_date }}
            </p>
        @endif

        <p>
            <strong>Status:</strong>
            Onboarding Completed
        </p>

        <p>
            <strong>Completed At:</strong>
            {{ optional($application->onboarding_completed_at)->format('F j, Y g:i A') }}
        </p>

        <hr>

        <p>
            The applicant has completed the required onboarding
            information and submitted their available documents.
        </p>

        <div style="margin-top:25px; text-align:center;">

            <a
                href="{{ route('admin.applications.show', $application->id) }}"
                style="
                    display:inline-block;
                    padding:12px 24px;
                    background:#435891;
                    color:#ffffff;
                    text-decoration:none;
                    border-radius:5px;
                "
            >
                View Application
            </a>

        </div>

        <p style="margin-top:30px; color:#777; font-size:13px;">
            This is an automated notification from Abbyero Aviation LLC.
        </p>

    </div>

</body>

</html>