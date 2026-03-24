<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        return ApiResponse::success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'is_sub_account' => $user->isSubAccount(),
            'avatar_url' => $user->avatar_url,
            'can_manage_boq' => (bool) $user->can_manage_boq,
            'can_manage_materials' => (bool) $user->can_manage_materials,
            'can_manage_labour' => (bool) $user->can_manage_labour,
            'linked_users_count' => $user->isSubAccount() ? 0 : $user->subAccounts()->count(),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('avatars', 'public');
            $user->photo = $path;
            $user->photo_storage = 'public';
        }

        $user->save();

        return ApiResponse::success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
        ], message: 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], (string) $request->user()->password)) {
            return ApiResponse::error(
                code: 'VALIDATION_ERROR',
                message: 'The given data was invalid.',
                status: 422,
                details: ['current_password' => ['The password is incorrect.']]
            );
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return ApiResponse::success(
            data: ['password_updated' => true],
            message: 'Password updated.'
        );
    }
}

