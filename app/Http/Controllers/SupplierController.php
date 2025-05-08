<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        // Get the current project_id from the authenticated user
        $projectId = Auth::user()->project_id;

        // Retrieve paginated suppliers related to the current project with their materials
        $suppliers = Supplier::whereHas('materials', function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->with(['materials' => function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        }])->paginate(10);

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Display the specified supplier.
     */
    public function show($id)
    {
        $projectId = Auth::user()->project_id;

        // Retrieve supplier with materials filtered by project
        $supplier = Supplier::whereHas('materials', function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->with(['materials' => function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        }])->findOrFail($id);

        return view('suppliers.show', compact('supplier'));
    }

    // public function store(Request $request)
    // {
    //     // Validate request
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'contact_info' => 'required|string|max:255',
    //     ]);

    //     // Create new supplier
    //     $supplier = Supplier::create([
    //         'name' => $request->name,
    //         'contact_info' => $request->contact_info,
    //     ]);

    //     // Return supplier ID for dropdown population
    //     return response()->json(['id' => $supplier->id]);
    // }

    public function ajaxStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
        ]);
    
        // Ensure only one entry is created
        $supplier = Supplier::firstOrCreate([
            'name' => $request->name,
            'contact_info' => $request->contact_info,
        ]);
    
        return response()->json($supplier);
    }
    

    // public function create()
    // {
    //     $suppliers = Supplier::all();
    //     return view('materials.create', compact('suppliers'));
    // }

    // Method for supplier name autocomplete
    public function autocomplete(Request $request)
    {
        $term = $request->input('term');
        $projectId = Auth::user()->project_id;

        // Filter suppliers by project
        $suppliers = Supplier::where('name', 'LIKE', '%' . $term . '%')
            ->whereHas('materials', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->pluck('name');

        return response()->json($suppliers);
    }

    // Method for supplier contact autocomplete
    public function autocompleteContact(Request $request)
    {
        $term = $request->get('term');
        $projectId = Auth::user()->project_id;

        // Filter suppliers by project and contact info
        $suppliers = Supplier::where('contact_info', 'LIKE', '%' . $term . '%')
            ->whereHas('materials', function($query) use ($projectId) {
                $query->where('project_id', $projectId);
            })
            ->get();

        $results = [];
        foreach ($suppliers as $supplier) {
            $results[] = ['value' => $supplier->contact_info, 'id' => $supplier->id];
        }

        return response()->json($results);
    }
}
