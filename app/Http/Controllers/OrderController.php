<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Show checkout form.
     */
    public function checkout()
    {
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Twój koszyk jest pusty!');
        }

        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout', compact('cartItems', 'total'));
    }

    /**
     * Process the order.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
            'guest_email' => ['required_if:user_id,null', 'nullable', 'email'],
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Twój koszyk jest pusty!');
        }

        // Calculate total
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Create order in a transaction
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'guest_email' => $validated['guest_email'] ?? null,
                'total_price' => $total,
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'],
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                // Check stock availability
                if ($cartItem->product->stock < $cartItem->quantity) {
                    DB::rollBack();
                    return back()->with('error', "Produkt {$cartItem->product->name} nie jest dostępny w wymaganej ilości.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price_per_item' => $cartItem->product->price,
                ]);
            }

            // Clear cart
            $userId = Auth::id();
            $sessionId = Session::get('cart_session_id');

            CartItem::where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else if ($sessionId) {
                    $query->where('session_id', $sessionId);
                }
            })->delete();

            DB::commit();

            // Przekierowanie do PayU
            return $this->initiatePayUPayment($order);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Wystąpił błąd podczas składania zamówienia. Spróbuj ponownie.');
        }
    }

    /**
     * Show order confirmation.
     */
    public function confirmation(Order $order)
    {
        // Verify ownership
        if (Auth::id() != $order->user_id && !Auth::guest()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('order-confirmation', compact('order'));
    }

    /**
     * Show user's order history.
     */
    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order-history', compact('orders'));
    }

    /**
     * Show specific order details.
     */
    public function show(Order $order)
    {
        // Verify ownership
        if (Auth::id() != $order->user_id) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('order-detail', compact('order'));
    }

    /**
     * Get cart items for current user/session.
     */
    private function getCartItems()
    {
        $userId = Auth::id();
        $sessionId = Session::get('cart_session_id');

        return CartItem::with('product')
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else if ($sessionId) {
                    $query->where('session_id', $sessionId);
                }
            })
            ->get();
    }

    /**
     * Initiate PayU payment.
     */
    private function initiatePayUPayment(Order $order)
    {
        $posId = env('PAYU_POS_ID', '300746');
        $secondKey = env('PAYU_SECOND_KEY', 'b6ca15b0d1020e8094d9b5f8d163db54');
        $apiUrl = env('PAYU_API_URL', 'https://secure.snd.payu.com');
        
        // Przygotowanie danych zamówienia
        $orderData = [
            'notifyUrl' => route('payu.notify'),
            'customerIp' => request()->ip(),
            'merchantPosId' => $posId,
            'description' => 'Zamówienie #' . $order->id,
            'currencyCode' => 'PLN',
            'totalAmount' => (int)($order->total_price * 100), // w groszach
            'extOrderId' => $order->id . '-' . time(),
            'buyer' => [
                'email' => $order->guest_email ?? Auth::user()->email ?? 'test@example.com',
                'firstName' => 'Klient',
                'lastName' => 'BMCODEX',
            ],
            'products' => [
                [
                    'name' => 'Zamówienie #' . $order->id,
                    'unitPrice' => (int)($order->total_price * 100),
                    'quantity' => 1,
                ]
            ],
            'continueUrl' => route('order.confirmation', $order),
        ];

        // Pobierz token OAuth
        try {
            $tokenResponse = Http::asForm()->post($apiUrl . '/pl/standard/user/oauth/authorize', [
                'grant_type' => 'client_credentials',
                'client_id' => env('PAYU_CLIENT_ID', '300746'),
                'client_secret' => env('PAYU_CLIENT_SECRET', '2ee86a66e5d97e3fadc400c9f19b065d'),
            ]);

            if (!$tokenResponse->successful()) {
                return redirect()->route('order.confirmation', $order)
                    ->with('error', 'Nie udało się połączyć z bramką płatności.');
            }

            $accessToken = $tokenResponse->json('access_token');

            // Utwórz zamówienie w PayU
            $paymentResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($apiUrl . '/api/v2_1/orders', $orderData);

            if ($paymentResponse->successful()) {
                $redirectUri = $paymentResponse->json('redirectUri');
                return redirect($redirectUri);
            } else {
                return redirect()->route('order.confirmation', $order)
                    ->with('error', 'Nie udało się zainicjować płatności.');
            }

        } catch (\Exception $e) {
            return redirect()->route('order.confirmation', $order)
                ->with('error', 'Wystąpił błąd podczas inicjowania płatności: ' . $e->getMessage());
        }
    }
}
