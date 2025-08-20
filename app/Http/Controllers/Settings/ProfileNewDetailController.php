<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileNewDetailController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia("settings/ProfileDetail");
    }

    public function update(Request $request)
    {
        // dd($request->avatar);
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
        return Inertia('settings/ProfileShow');
    }
}
