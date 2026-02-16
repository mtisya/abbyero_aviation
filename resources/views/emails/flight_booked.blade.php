@component('mail::message')
# Flight Booking Confirmation

Hello **{{ $user->name }}**,  
Your flight has been successfully booked.

---

## ✈ Flight Details
- **Aircraft:** {{ $flight->aircraft_model }} ({{ $flight->registration_number }})
- **From:** {{ $flight->departure_location }}
- **Departure Time:** {{ \Carbon\Carbon::parse($flight->departure_time)->format('d M Y H:i') }}
- **To:** {{ $flight->arrival_location }}
- **Arrival Time:** {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d M Y H:i') }}
- **Price:** ${{ number_format($flight->price, 2) }}

---

## 📄 Booking Info
- **Booking ID:** {{ $booking->id }}
- **Booking Status:** {{ $booking->status }}
- **Booked At:** {{ $booking->created_at->format('d M Y H:i') }}

---

@component('mail::button', ['url' => route('flights.available')])
View More Flights
@endcomponent

Thanks for choosing **{{ config('app.name') }}**  
@endcomponent
