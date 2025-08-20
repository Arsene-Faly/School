<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MembreController extends Controller
{
    public function index(Request $request, string $role)
    {
        $validRoles = ['user', 'owner', 'pending_owner'];
        if (!in_array($role, $validRoles)) {
            abort(404);
        }

        $users = User::filter($request, $role)
            ->paginate(7)
            ->withQueryString();

        $roleCounts = User::getRoleCounts();

        return Inertia('admin/membre/Index', [
            'users' => $users,
            'filters' => [
                'search' => $request->input('search'),
                'active' => $request->input('active'),
            ],
            'roleCounts' => $roleCounts,
            'currentRole' => $role,
        ]);
    }



    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }
}
