<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

            return redirect()->route('order.confirmation', $order)->with('success', 'Zamówienie zostało złożone pomyślnie!');

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
}
