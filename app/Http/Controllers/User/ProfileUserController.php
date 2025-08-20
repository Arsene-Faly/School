<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileUserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia("user/profile/Profile", [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile.user');
    }

    public function editPassword()
    {
        return Inertia('user/profile/Password');
    }

    public function editProfileDetail()
    {
        return Inertia('user/profile/ProfileDetail');
    }

    public function updateProfileDetail(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2024',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'bio' => 'nullable|string',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        UserInfo::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'avatar' => $avatarPath ? 'storage/' . $avatarPath : (Auth::user()->info->avatar ?? null),
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'birthdate' => $validated['birthdate'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ]
        );

        return back()->with('success', 'Informations mises à jour avec succès.');
    }

    public function show()
    {
        return Inertia('user/profile/Show');
    }
}
