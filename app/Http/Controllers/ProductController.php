<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = UnitOfMeasurement::all();
        $products = Product::orderBy('name', 'asc')->get();
        return response(view('products.index', compact('products', 'units')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $units = UnitOfMeasurement::all();
        return response(view('products.create', compact('units')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255'
        ]);

        Product::create($request->all());

        return response(redirect()->route('products.index')
                         ->with('success', 'Material added successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response(view('products.show', compact('product')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return response(view('products.edit', compact('product')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:255'
        ]);

        $product->update($request->all());

        return response(
            redirect()->route('products.index')
                      ->with('success', 'Material updated successfully.')
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response(redirect()->route('products.index')
                         ->with('success', 'Material deleted successfully.'));
    }
}
