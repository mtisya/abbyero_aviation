<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function downloadInvoice(Order $order)
    {
        // Ensure the logged-in user owns this order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $pdf = Pdf::loadView('orders.invoice', compact('order'));

        return $pdf->download("invoice-order-{$order->id}.pdf");
    }
}
