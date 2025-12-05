<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $productsCount = Product::count();
        $ordersCount = Order::count();
        $usersCount = User::where('role', 'user')->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'productsCount',
            'ordersCount',
            'usersCount',
            'pendingOrdersCount',
            'totalRevenue',
            'lowStockCount',
            'recentOrders'
        ));
    }

    /**
     * Manage products.
     */
    public function products()
    {
        $products = Product::with('category')->paginate(20);
        $categories = Category::all();
        return view('admin.products', compact('products', 'categories'));
    }

    /**
     * Store new product.
     */
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['required', 'string', 'max:100', 'unique:products'],
            'image_url' => ['nullable', 'string', 'max:255'],
        ]);

        Product::create($validated);

        return back()->with('success', 'Produkt został dodany!');
    }

    /**
     * Update product.
     */
    public function updateProduct(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'image_url' => ['nullable', 'string', 'max:255'],
        ]);

        $product->update($validated);

        return back()->with('success', 'Produkt został zaktualizowany!');
    }

    /**
     * Delete product.
     */
    public function deleteProduct(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Produkt został usunięty!');
    }

    /**
     * Manage orders.
     */
    public function orders()
    {
        $orders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    /**
     * Update order status.
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status zamówienia został zaktualizowany!');
    }

    /**
     * Manage users.
     */
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Update user role.
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,employee,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', 'Rola użytkownika została zaktualizowana!');
    }

    /**
     * Delete user.
     */
    public function deleteUser(User $user)
    {
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Nie można usunąć ostatniego administratora!');
        }

        $user->delete();
        return back()->with('success', 'Użytkownik został usunięty!');
    }

    /**
     * Manage categories.
     */
    public function categories()
    {
        $categories = Category::withCount('products')->get();
        return view('admin.categories', compact('categories'));
    }

    /**
     * Store new category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories'],
            'description' => ['nullable', 'string'],
        ]);

        Category::create($validated);

        return back()->with('success', 'Kategoria została dodana!');
    }

    /**
     * Update category.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return back()->with('success', 'Kategoria została zaktualizowana!');
    }

    /**
     * Delete category.
     */
    public function deleteCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Nie można usunąć kategorii, która zawiera produkty!');
        }

        $category->delete();
        return back()->with('success', 'Kategoria została usunięta!');
    }


}
