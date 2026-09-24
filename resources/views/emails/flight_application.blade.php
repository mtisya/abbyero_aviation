@component('mail::message')
# New Flight School Application

Hello Admin,

A new flight school application has been submitted. Here are the details:

- **Name:** {{ $data['name'] }}
- **Email:** {{ $data['email'] }}
- **Phone:** {{ $data['phone'] }}
- **Message:** {{ $data['message'] ?? 'Enquiring Process For Flight School Application' }}

A PDF copy of this application is attached for your records.

@component('mail::button', ['url' => route('flight.school')])
View Flight School
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent