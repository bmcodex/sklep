<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PayUController extends Controller
{
    /**
     * Show PayU sandbox payment form.
     */
    public function showPaymentForm(Order $order)
    {
        // Sprawdź czy zamówienie istnieje
        if (!$order) {
            return redirect()->route('home')->with('error', 'Zamówienie nie zostało znalezione.');
        }

        return view('payu-sandbox', compact('order'));
    }

    /**
     * Process PayU payment (sandbox simulation).
     */
    public function processPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
        ]);

        // Symulacja płatności - w trybie sandbox zawsze sukces
        $order->update([
            'status' => 'processing', // Płatność zaakceptowana, zamówienie w realizacji
        ]);

        return redirect()->route('order.confirmation', $order)
            ->with('success', 'Płatność została zrealizowana pomyślnie! (tryb sandbox)');
    }

    /**
     * Handle PayU notification (webhook).
     */
    public function notify(Request $request)
    {
        // W trybie sandbox - tylko logowanie
        \Log::info('PayU notification received', $request->all());

        return response()->json(['status' => 'OK']);
    }

    /**
     * Handle payment cancellation.
     */
    public function cancel(Order $order)
    {
        $order->update(['status' => 'cancelled']);

        return redirect()->route('home')
            ->with('error', 'Płatność została anulowana.');
    }
}
