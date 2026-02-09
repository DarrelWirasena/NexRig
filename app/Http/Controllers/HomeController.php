<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        
        $products = Product::with(['series.category', 'images'])
                    ->where('is_active', true)
                    ->latest() 
                    ->get();

        
        $featured = $products->take(4);

        return view('home', [
            'products' => $products,
            'featured' => $featured
        ]);
    }
}