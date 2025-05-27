<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        $units = UnitOfMeasurement::all();
        $products = Product::orderBy('name', 'asc')->get();
        return view('products.index', compact('products', 'units'));
    }

    
    public function create()
    {
        $units = UnitOfMeasurement::all();
        return view('products.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
                         ->with('success', 'Material added successfully.');
    }
 
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255'
        ]);

        $product->update($request->all());

        return
            redirect()->route('products.index')
                      ->with('success', 'Material updated successfully.');
    }
    
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Material deleted successfully.');
    }
}
