<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProjectWizardController extends Controller
{
    public function wizard(Request $request)
    {
        if (Auth::check() && (Auth::user()->isSubAccount() || Auth::user()->is_admin())) {
            return redirect()->route('dashboard')->with('warning', 'You are not allowed to create projects.');
        }

        $step = (int) $request->query('step', 1);
        $step = $step === 2 ? 2 : 1;

        return view('wizard.index', compact('step'));
    }

    public function step1()
    {
        if (Auth::check() && (Auth::user()->isSubAccount() || Auth::user()->is_admin())) {
            return redirect()->route('dashboard')->with('warning', 'You are not allowed to create projects.');
        }
        return redirect()->route('wizard');
    }

    public function step2()
    {
        if (Auth::check() && (Auth::user()->isSubAccount() || Auth::user()->is_admin())) {
            return redirect()->route('dashboard')->with('warning', 'You are not allowed to create projects.');
        }
        return redirect()->route('wizard', ['step' => 2]);
    }

    public function step1Fragment()
    {
        if (Auth::check() && (Auth::user()->isSubAccount() || Auth::user()->is_admin())) {
            return response()->json(['error' => 'You are not allowed to create projects.'], 403);
        }

        return view('wizard.partials.step1');
    }

    public function step2Fragment(Request $request)
    {
        if (Auth::check() && (Auth::user()->isSubAccount() || Auth::user()->is_admin())) {
            return response()->json(['error' => 'You are not allowed to create projects.'], 403);
        }

        return view('wizard.partials.step2', [
            'wizardProject' => $this->wizardSessionData($request),
        ]);
    }

    public function step1Post(Request $request)
    {
        $validatedData = $request->validate([
            'project_uid' => 'required|string|max:100|alpha_dash|unique:projects,project_uid',
            'name' => 'required|string|max:255',
            'description' => 'required',
            'project_duration' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'budget' => 'required|string|max:255',
        ]);

        // Store in session
        $request->session()->put('wizard_project', $validatedData);
        $request->session()->put('project_uid', $validatedData['project_uid']);
        $request->session()->put('name', $validatedData['name']);
        $request->session()->put('description', $validatedData['description']);
        $request->session()->put('project_duration', $validatedData['project_duration']);
        $request->session()->put('address', $validatedData['address']);
        $request->session()->put('budget', $validatedData['budget']);

        // Redirect to the confirmation step
        return redirect()->route('wizard', ['step' => 2]);
    }

    public function step2Post(Request $request)
    {
        // Step 2 is merged into step 1; keep for backward compatibility.
        return redirect()->route('wizard');
    }

public function complete(Request $request)
{
    if (Auth::check() && (Auth::user()->isSubAccount() || Auth::user()->is_admin())) {
        return redirect()->route('dashboard')->with('warning', 'You are not allowed to create projects.');
    }

    $wizardProject = $this->wizardSessionData($request);
    $request->merge([
        'project_uid' => $request->input('project_uid', $wizardProject['project_uid']),
        'name' => $request->input('name', $wizardProject['name']),
        'description' => $request->input('description', $wizardProject['description']),
        'project_duration' => $request->input('project_duration', $wizardProject['project_duration']),
        'address' => $request->input('address', $wizardProject['address']),
        'budget' => $request->input('budget', $wizardProject['budget']),
    ]);

    $validated = $request->validate([
        'project_uid' => 'required|string|max:100|alpha_dash|unique:projects,project_uid',
        'name' => 'required|string|max:255',
        'description' => 'required',
        'project_duration' => 'required|integer|min:1',
        'address' => 'required|string|max:255',
        'budget' => 'required|string|max:255',
    ]);



    $user = Auth::user();

    $data = [
        'project_uid' => $validated['project_uid'],
        'name' => $validated['name'],
        'user_id' => $user->id,
        'address' => $validated['address'],
        'description' => $validated['description'],
        'project_duration' => $validated['project_duration'],
        'budget' => $validated['budget'],
    ];

   $project_created = Project::create($data);
   if($project_created) {
    $user->has_project = 1;
    $user->project_id=  $project_created->id;
    $user->save();

    $project_created->users()->syncWithoutDetaching([$user->id]);
    $subAccountIds = User::where('parent_user_id', $user->id)->pluck('id');
    if ($subAccountIds->isNotEmpty()) {
        $project_created->users()->syncWithoutDetaching($subAccountIds->all());
    }
   }

    // Clear session data
    $request->session()->forget(['wizard_project', 'project_uid', 'name', 'address', 'description', 'project_duration', 'budget']);

    return redirect()->to(url('dashboard'))->with('success', 'Project added successfully!');
}

    private function wizardSessionData(Request $request): array
    {
        $stored = $request->session()->get('wizard_project', []);

        return [
            'project_uid' => old('project_uid', $stored['project_uid'] ?? session('project_uid')),
            'name' => old('name', $stored['name'] ?? session('name')),
            'description' => old('description', $stored['description'] ?? session('description')),
            'project_duration' => old('project_duration', $stored['project_duration'] ?? session('project_duration')),
            'address' => old('address', $stored['address'] ?? session('address')),
            'budget' => old('budget', $stored['budget'] ?? session('budget')),
        ];
    }




}
