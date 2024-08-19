<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectWizardController extends Controller
{
    public function step1()
    {
        return view('wizard.step1');
    }

    public function step2()
    {
        return view('wizard.step2');
    }

    public function step3()
    {
        return view('wizard.step3');
    }



    public function step1Post(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required',
    ]);

    // Store in session
    $request->session()->put('name', $validatedData['name']);
    $request->session()->put('description', $validatedData['description']);

    // Redirect to the next step
    return redirect()->route('wizard.step2');
}
public function step2Post(Request $request)
{
    $validatedData = $request->validate([
        'address' => 'required|string|max:255',
        'budget' => 'required|string|max:255',
    ]);

    // Store in session
    $request->session()->put('address', $validatedData['address']);
    $request->session()->put('budget', $validatedData['budget']);

    // Redirect to the next step
    return redirect()->route('wizard.step3');
}

public function complete(Request $request)
{
    // Retrieve data from session
    $name = $request->session()->get('name');
    $address = $request->session()->get('address');
    $description = $request->session()->get('description');
    $budget = $request->session()->get('budget');



    $user = Auth::user();

    $data = [
        'name' => $name,
        'user_id' => $user->id,
        'address' => $address,
        'description' => $description,
        'budget' => $budget,
    ];

   $project_created = Project::create($data);
   if($project_created) {
    $user->has_project = 1;
    $user->project_id=  $project_created->id;
    $user->save();

   }

    // Clear session data
    $request->session()->forget(['name', 'address', 'description', 'budget']);

    return redirect()->url('dashboard')->with('success', 'Project added successfully!');
}




}
