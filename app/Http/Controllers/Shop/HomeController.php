<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\FlashSale;

class HomeController extends Controller
{
    /**
     * Display the shop home page
     */
    public function index()
    {
        // Get featured categories
        $categories = Category::where('is_active', true)
            ->take(6)
            ->get();

        // Get featured products
        $featuredProducts = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Get active flash sales with their items
        $activeFlashSale = FlashSale::with(['items.productCombination.product', 'items.productCombination.optionValues'])
            ->where('is_active', true)
            ->where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->first();

        // Get best selling products
        $bestSellingProducts = Product::with(['category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc') // In a real app, this would be ordered by sales count
            ->take(4)
            ->get();

        return view('shop.home', compact(
            'categories',
            'featuredProducts',
            'activeFlashSale',
            'bestSellingProducts'
        ));
    }
}