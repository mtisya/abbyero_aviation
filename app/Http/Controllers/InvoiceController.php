<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Display an invoice.
     */
    public function show(Invoice $invoice)
    {
        /*
        |--------------------------------------------------------------------------
        | PREVENT USERS FROM VIEWING OTHER USERS' INVOICES
        |--------------------------------------------------------------------------
        */

        abort_unless(
            Auth::user()->role === 'admin'
                || $invoice->user_id === Auth::id(),
            403
        );


        /*
        |--------------------------------------------------------------------------
        | LOAD INVOICE DETAILS
        |--------------------------------------------------------------------------
        */

        $invoice->load([
            'user',
            'items.flight',
            'items.logbook',
        ]);


        /*
        |--------------------------------------------------------------------------
        | DISPLAY INVOICE
        |--------------------------------------------------------------------------
        */

        return view('invoices.show', compact('invoice'));
    }
}