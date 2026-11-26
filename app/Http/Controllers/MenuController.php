<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->with(['products' => fn($q) => $q->where('is_available', true)])
            ->get();

        return view('menu.index', compact('categories'));
    }

    public function show(Product $product)
    {
        return view('menu.show', compact('product'));
    }
}