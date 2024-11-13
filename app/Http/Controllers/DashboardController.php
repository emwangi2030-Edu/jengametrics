<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {


           $user = Auth::user();
        if (!$user->has_project) {
            return redirect()->route('wizard.step1');
        }


        // Get the current project ID the user is working on
        $currentProjectId = Auth::user()->project_id;

        // Get the total number of workers for the current project
        $totalWorkers = Worker::where('project_id', $currentProjectId)->count();

        // Retrieve total material cost for the current project
        $totalMaterialExpenses = Material::where('project_id', $currentProjectId)
                                        ->sum(\DB::raw('unit_price * quantity_in_stock'));

        return view('dashboard', compact('totalWorkers', 'totalMaterialExpenses'));
    }
}
