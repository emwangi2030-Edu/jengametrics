<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SubAccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isSubAccount()) {
            return redirect()->route('dashboard')->with('warning', 'Sub-accounts cannot manage other users.');
        }

        $subAccounts = $user->subAccounts()->orderBy('name')->get();

        return view('sub_accounts.index', compact('user', 'subAccounts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->isSubAccount()) {
            return redirect()->route('dashboard')->with('warning', 'Sub-accounts cannot manage other users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'can_manage_boq' => 'nullable|boolean',
            'can_manage_materials' => 'nullable|boolean',
            'can_manage_labour' => 'nullable|boolean',
        ]);

        $subAccount = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'parent_user_id' => $user->id,
            'user_type' => $user->user_type ?? 'user',
            'project_id' => $user->project_id,
            'has_project' => $user->has_project,
            'can_manage_boq' => (bool) ($request->input('can_manage_boq') ?? false),
            'can_manage_materials' => (bool) ($request->input('can_manage_materials') ?? false),
            'can_manage_labour' => (bool) ($request->input('can_manage_labour') ?? false),
        ]);

        return redirect()->route('sub_accounts.index')->with('success', 'Sub-account created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $owner = Auth::user();

        if ($owner->isSubAccount()) {
            return redirect()->route('dashboard')->with('warning', 'Sub-accounts cannot manage other users.');
        }

        if ((int) $user->parent_user_id !== (int) $owner->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'can_manage_boq' => 'nullable|boolean',
            'can_manage_materials' => 'nullable|boolean',
            'can_manage_labour' => 'nullable|boolean',
        ]);

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'can_manage_boq' => (bool) ($request->input('can_manage_boq') ?? false),
            'can_manage_materials' => (bool) ($request->input('can_manage_materials') ?? false),
            'can_manage_labour' => (bool) ($request->input('can_manage_labour') ?? false),
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('sub_accounts.index')->with('success', 'Sub-account updated successfully.');
    }

    public function destroy(User $user)
    {
        $owner = Auth::user();

        if ($owner->isSubAccount()) {
            return redirect()->route('dashboard')->with('warning', 'Sub-accounts cannot manage other users.');
        }

        if ((int) $user->parent_user_id !== (int) $owner->id) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('sub_accounts.index')->with('success', 'Sub-account deleted successfully.');
    }
}
