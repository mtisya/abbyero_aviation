<?php

// app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\FlightBookedMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{

    public function store(Flight $flight)
    {
        // prevent duplicate booking
        $alreadyBooked = Booking::where('user_id', Auth::id())
            ->where('flight_id', $flight->id)
            ->exists();

        if ($alreadyBooked) {
            return redirect()->route('flights.available')
                ->with('error', 'You have already booked this flight.');
        }

        $reference = "ABBYERO-" . strtoupper(Str::random(6));

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'flight_id' => $flight->id,
            'status' => 'Booked',
            'reference' => $reference,
        ]);

        // Generate QR as PNG base64
        $qrCodePng = base64_encode(
            QrCode::format('png')->size(70)->generate($booking->reference)
        );

        // Generate PDF from Blade view
        $pdf = Pdf::loadView('pdf.booking_receipt', [
            'flight' => $flight,
            'user' => Auth::user(),
            'booking' => $booking,
            'qrCodePng' => $qrCodePng,
        ]);

        // Send email
        Mail::to(Auth::user()->email)->send(
            new FlightBookedMail($pdf, $flight, Auth::user(), $booking)
        );
        // Mail::to(env('ADMIN_EMAIL'))->send(
        //     new FlightBookedMail($pdf, $flight, Auth::user(), $booking)
        // );

        return redirect()->route('flights.available')
            ->with('success', 'Flight booked successfully! Check your email for details.');
    }

    public function show($id)
    {
        $booking = Booking::with('user') // JOIN users table
            ->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }


    // Admin: List all booked flights
    public function adminIndex(Request $request)
    {
        // 🔐 Admin-only check (adjust field if needed)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $query = Booking::with(['user', 'flight'])
            ->orderBy('booked_at', 'desc');

        // 🔍 Search by reference
        if ($request->filled('reference')) {
            $query->where('reference', 'like', '%' . $request->reference . '%');
        }

        // 📄 Pagination
        $bookings = $query->paginate(30)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }



    public function downloadTicket(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        // Generate QR as PNG base64
        $qrCodePng = base64_encode(
            QrCode::format('png')->size(70)->generate($booking->reference)
        );

        $pdf = Pdf::loadView('pdf.booking_receipt', [
            'flight' => $booking->flight,
            'user'   => Auth::user(),
            'booking'=> $booking,
            'qrCodePng' => $qrCodePng,
        ]);

        return $pdf->download("Ticket-{$booking->reference}.pdf");
    }

    public function destroy(Request $request, $id)
    {
        // Admin check (extra safety)
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking = Booking::findOrFail($id);

        // Optional: store reason if column exists
        if ($request->filled('reason')) {
            $booking->cancellation_reason = $request->reason; // optional column
        }

        $booking->status = 'Cancelled';
        $booking->save();

        // Or delete if you prefer hard delete
        // $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully.'
        ]);
    }


}

