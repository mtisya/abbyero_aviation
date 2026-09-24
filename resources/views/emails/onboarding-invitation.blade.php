<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Abbyero Aviation Onboarding
    </title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background: #f4f6f8;
    font-family: Arial, Helvetica, sans-serif;
">

    <div style="
        max-width: 650px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    ">

        {{-- HEADER --}}

        <div style="
            padding: 30px;
            text-align: center;
            background: #ffffff;
            border-bottom: 1px solid #eeeeee;
        ">

            <img
                src="{{ asset('assets/images/logos/abbyerologo.png') }}"
                alt="Abbyero Aviation LLC"
                style="
                    max-width: 220px;
                    height: auto;
                "
            >

        </div>


        {{-- CONTENT --}}

        <div style="
            padding: 35px;
            color: #333333;
        ">

            <h2 style="
                margin-top: 0;
                color: #7d93cf;
            ">
                Welcome to Abbyero Aviation LLC
            </h2>


            <p>
                Dear
                <strong>
                    {{ $application->name }}
                </strong>,
            </p>


            <p>
                Thank you for your interest in
                <strong>Abbyero Aviation LLC</strong>.
            </p>


            <p>
                We have received your application and are ready
                to proceed with the next stage of your enrollment.
            </p>


            <p>
                Please complete your onboarding information using
                the secure link below. This will allow our team to
                collect the information required to continue
                processing your aviation training application.
            </p>


            {{-- BUTTON --}}

            <div style="
                text-align: center;
                margin: 35px 0;
            ">

                <a href="{{ $onboardingUrl }}"
                    style="
                        display: inline-block;
                        padding: 14px 28px;
                        background: #435891;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 6px;
                        font-weight: bold;
                    ">

                    Complete Your Onboarding

                </a>

            </div>


            <p style="
                font-size: 14px;
                color: #666666;
            ">

                This onboarding link is valid for
                <strong>7 days</strong>.

                Please complete your onboarding information
                before the link expires.

            </p>


            <p style="
                font-size: 14px;
                color: #666666;
            ">

                If you did not submit an application with
                Abbyero Aviation LLC, you may safely disregard
                this email.

            </p>


            <p>
                We look forward to assisting you with your
                aviation training journey.
            </p>


            <p>
                <strong>
                    Abbyero Aviation LLC
                </strong>
                <br>

                Flight Training & Scheduling: Call or Whatsapp
                +1.316-302-6304

                <br>

                www.abbyeroaviation.com
            </p>

        </div>


        {{-- FOOTER --}}

        <div style="
            padding: 20px;
            text-align: center;
            background: #f8f8f8;
            color: #777777;
            font-size: 12px;
        ">

            © {{ date('Y') }}
            Abbyero Aviation LLC.
            All rights reserved.

        </div>

    </div>

</body>

</html>