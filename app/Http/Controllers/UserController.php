<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SkydiveBooking;
use App\Notifications\UserApprovalStatusNotification;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;
use App\Models\Invoice;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // ✅ define user once

        $bookedFlights = $user->bookings()
            ->with('flight')
            ->latest()
            ->get();

        $maintenances = $user->maintenances()
            ->orderBy('maintenance_date', 'desc')
            ->get();

        $orders = $user->orders()
            ->with('items')
            ->latest()
            ->get();
        // User's skydiving bookings
        $bookings = SkydiveBooking::with('skydiving')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.dashboard', compact('bookedFlights', 'maintenances', 'orders', 'bookings'));
    }


public function show(User $user)
{
    /*
    |--------------------------------------------------------------------------
    | LOAD SELECTED USER DATA
    |--------------------------------------------------------------------------
    */

    $user->load([
        'application',

        /*
        |--------------------------------------------------------------------------
        | FLIGHT SCHEDULES
        |--------------------------------------------------------------------------
        */

        'flightSchedules' => function ($query) {
            $query->latest('start_time');
        },

        'flightSchedules.flight',

        'flightSchedules.instructor',


        /*
        |--------------------------------------------------------------------------
        | DISPATCHES
        |--------------------------------------------------------------------------
        */

        'flightSchedules.dispatches' => function ($query) {
            $query->latest('dispatch_time');
        },

        'flightSchedules.dispatches.aircraft',
        'flightSchedules.dispatches.pilot',
        'flightSchedules.dispatches.dispatcher',


        /*
        |--------------------------------------------------------------------------
        | LOGBOOKS
        |--------------------------------------------------------------------------
        */

        'flightSchedules.logbooks' => function ($query) {
            $query->latest('flight_date');
        },

        'flightSchedules.logbooks.instructor',
        'flightSchedules.logbooks.approver',
        'flightSchedules.logbooks.flight',
    ]);


    /*
    |--------------------------------------------------------------------------
    | LOAD INVOICES FOR SELECTED USER
    |--------------------------------------------------------------------------
    */

    $invoices = Invoice::with([
        'items.flight',
        'items.logbook',
    ])
        ->where('user_id', $user->id)
        ->latest('invoice_date')
        ->latest('id')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | GET SELECTED USER'S LOGBOOKS
    |--------------------------------------------------------------------------
    */

    $logbooks = $user->flightSchedules
        ->flatMap(function ($schedule) {
            return $schedule->logbooks;
        })
        ->sortByDesc('flight_date')
        ->values();


    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.users.show',
        compact(
            'user',
            'invoices',
            'logbooks'
        )
    );
}


    // List all users with pagination
    public function list(Request $request)
    {
        $query = User::whereIn('status', ['approved', 'pending']);

        $users = $query->paginate(5);

        // ✅ Fix invalid page after filtering/deletes
        if ($users->currentPage() > $users->lastPage()) {
            return redirect()->route('users.list', [
                'page' => $users->lastPage()
            ]);
        }

        return view('users.list', compact('users'));
    }


    // Approve user
    public function approve(User $user, Request $request)
    {
        $user->status = 'approved';
        $user->save();

        $user->notify(new UserApprovalStatusNotification('approved'));

        // ✅ mark specific notification as read
        if ($request->notification_id) {
            DatabaseNotification::find($request->notification_id)?->markAsRead();
        }

        return back()->with('success', 'User approved successfully.');
    }

    // Disapprove user
    public function disapprove(User $user)
    {
        $user->status = 'pending';
        $user->save();

        // ✅ notify user
        $user->notify(new UserApprovalStatusNotification('pending'));
        $user->unreadNotifications->markAsRead();
        return back()->with('success', 'User disapproved successfully.');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'inactive'
        ]);

        return back()->with('success', 'User deactivated successfully.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'User activated successfully.');
    }

}