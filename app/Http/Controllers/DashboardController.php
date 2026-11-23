<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Apenas mod e admin acedem ao dashboard
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        // Totais gerais
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalStockUnits = Product::sum('quantity');
        $totalUsers = User::count();

        // Alertas: Artigos esgotados ou com stock baixo (<= 2 unidades)
        $lowStockProducts = Product::with('category')
            ->where('quantity', '<=', 2)
            ->orderBy('quantity', 'asc')
            ->get();

        // Últimos 5 artigos registados
        $recentProducts = Product::with(['category', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalCategories',
            'totalProducts',
            'totalStockUnits',
            'totalUsers',
            'lowStockProducts',
            'recentProducts'
        ));
    }
}
