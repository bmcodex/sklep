<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $quantity = $validated['quantity'];

        // Check stock availability
        if ($product->stock < $quantity) {
            return back()->with('error', 'Niewystarczająca ilość produktu w magazynie.');
        }

        $userId = Auth::id();
        $sessionId = $this->getOrCreateSessionId();

        // Check if item already exists in cart
        $cartItem = CartItem::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Niewystarczająca ilość produktu w magazynie.');
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        return back()->with('success', 'Produkt dodany do koszyka!');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Verify ownership
        if (!$this->verifyCartItemOwnership($cartItem)) {
            abort(403);
        }

        // Check stock
        if ($cartItem->product->stock < $validated['quantity']) {
            return back()->with('error', 'Niewystarczająca ilość produktu w magazynie.');
        }

        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        return back()->with('success', 'Koszyk zaktualizowany!');
    }

    /**
     * Remove item from cart.
     */
    public function remove(CartItem $cartItem)
    {
        // Verify ownership
        if (!$this->verifyCartItemOwnership($cartItem)) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Produkt usunięty z koszyka!');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        $userId = Auth::id();
        $sessionId = Session::get('cart_session_id');

        CartItem::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else if ($sessionId) {
                $query->where('session_id', $sessionId);
            }
        })->delete();

        return back()->with('success', 'Koszyk został wyczyszczony!');
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
     * Get or create session ID for guest users.
     */
    private function getOrCreateSessionId()
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', uniqid('cart_', true));
        }
        return Session::get('cart_session_id');
    }

    /**
     * Verify cart item ownership.
     */
    private function verifyCartItemOwnership(CartItem $cartItem)
    {
        $userId = Auth::id();
        $sessionId = Session::get('cart_session_id');

        if ($userId && $cartItem->user_id == $userId) {
            return true;
        }

        if (!$userId && $sessionId && $cartItem->session_id == $sessionId) {
            return true;
        }

        return false;
    }
}
