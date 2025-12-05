<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Show user's favorites.
     */
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product')->get();
        return view('favorites', compact('favorites'));
    }

    /**
     * Add product to favorites.
     */
    public function add(Product $product)
    {
        $user = Auth::user();
        
        // Check if already favorited
        $exists = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();
        
        if ($exists) {
            return back()->with('info', 'Ten produkt jest już w Twoich ulubionych.');
        }
        
        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
        
        return back()->with('success', 'Produkt dodany do ulubionych!');
    }

    /**
     * Remove product from favorites.
     */
    public function remove(Product $product)
    {
        $user = Auth::user();
        
        Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->delete();
        
        return back()->with('success', 'Produkt usunięty z ulubionych.');
    }

    /**
     * Toggle favorite status.
     */
    public function toggle(Product $product)
    {
        $user = Auth::user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
        
        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Produkt usunięty z ulubionych'
            ]);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Produkt dodany do ulubionych'
            ]);
        }
    }
}
