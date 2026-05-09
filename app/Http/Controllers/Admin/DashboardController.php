<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Cart;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ── Core counts ──────────────────────────────────────────────
        $totalOrders    = Order::count();
        $todayOrders    = Order::whereDate('created_at', today())->count();
        $monthOrders    = Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $lastMonthOrders= Order::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
        $orderGrowth    = $lastMonthOrders > 0 ? round((($monthOrders - $lastMonthOrders) / $lastMonthOrders) * 100) : 0;

        $totalRevenue   = Order::where('payment_status', 'paid')->sum('total_amount');
        $monthRevenue   = Order::where('payment_status', 'paid')->whereMonth('created_at', now()->month)->sum('total_amount');
        $lastMonthRev   = Order::where('payment_status', 'paid')->whereMonth('created_at', now()->subMonth()->month)->sum('total_amount');
        $revGrowth      = $lastMonthRev > 0 ? round((($monthRevenue - $lastMonthRev) / $lastMonthRev) * 100) : 0;

        $totalUsers     = User::count();
        $newUsers       = User::whereMonth('created_at', now()->month)->count();
        $totalProducts  = Product::withoutTrashed()->count();
        $activeProducts = Product::withoutTrashed()->where('status', 1)->count();
        $totalCategories= Category::withoutTrashed()->count();
        $featuredCats   = Category::withoutTrashed()->where('is_featured', true)->count();

        // ── Order statuses ────────────────────────────────────────────
        $pendingOrders   = Order::where('status', 'pending')->count();
        $processingOrders= Order::where('status', 'processing')->count();
        $shippedOrders   = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // ── Cart stats ────────────────────────────────────────────────
            $activeCarts = Cart::selectRaw('COUNT(DISTINCT COALESCE(user_id, cookie_id)) as count')
                ->value('count');
            $cartItems= Cart::sum('quantity');

        // ── Recent orders ─────────────────────────────────────────────
        $recentOrders = Order::with('user')
            ->latest()
            ->take(8)
            ->get();

        // ── Top products by order items ───────────────────────────────
        $topProducts =OrderItem::select('product_name', \DB::raw('SUM(quantity) as total_qty'), \DB::raw('COUNT(*) as order_count'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // ── Category distribution ─────────────────────────────────────
        $catDistribution = Category::withoutTrashed()
            ->withCount('products')
            ->orderByDesc('products_count')
            ->take(5)
            ->get();
        $maxCatProducts = $catDistribution->max('products_count') ?: 1;

        // ── Low stock ─────────────────────────────────────────────────
        $lowStock = Product::withoutTrashed()
            ->where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->count();
        $outOfStock = Product::withoutTrashed()->where('quantity', 0)->count();
        return view('admin.dashboard' ,get_defined_vars());  
    }

}
