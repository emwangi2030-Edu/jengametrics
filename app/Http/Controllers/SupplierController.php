<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Material;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::with('materials')->get();
        return view('suppliers.index', compact('suppliers'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('suppliers.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'contact_info' => 'nullable|string|max:255',
    //         'address' => 'nullable|string|max:255',
    //     ]);
    
    //     Supplier::create($validatedData);
    
    //     return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully.');
    // }

    /**
     * Display the specified supplier.
     */
    public function show($id)
    {
        $supplier = Supplier::with('materials')->findOrFail($id);
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    // public function edit(string $id)
    // {
    //     $supplier = Supplier::findOrFail($id);
    //     return view('suppliers.edit', compact('supplier'));
    // }

    /**
     * Update the specified supplier in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'contact_info' => 'nullable|string|max:255',
    //         'address' => 'nullable|string|max:255',
    //     ]);
    
    //     $supplier = Supplier::findOrFail($id);
    //     $supplier->update($request->all());
    
    //     return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    // }

    /**
     * Remove the specified supplier from storage.
     */
    // public function destroy(string $id)
    // {
    //     $supplier = Supplier::findOrFail($id);
    //     $supplier->delete();

    //     return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    // }

    // Method for supplier name autocomplete
    public function autocomplete(Request $request)
    {
        $term = $request->get('term');
        $suppliers = Supplier::where('name', 'LIKE', '%' . $term . '%')->get();
        
        $results = [];
        foreach ($suppliers as $supplier) {
            $results[] = ['value' => $supplier->name, 'id' => $supplier->id];
        }

        return response()->json($results);
    }

//     // Method for supplier contact autocomplete
    public function autocompleteContact(Request $request)
    {
        $term = $request->get('term');
        $suppliers = Supplier::where('contact_info', 'LIKE', '%' . $term . '%')->get();
        
        $results = [];
        foreach ($suppliers as $supplier) {
            $results[] = ['value' => $supplier->contact_info, 'id' => $supplier->id];
        }

        return response()->json($results);
    }
}
