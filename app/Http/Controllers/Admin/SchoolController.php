<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $search = $request->input('search');

        $status = match ($status) {
            'validated' => 'validated',
            'pending' => 'pending',
            default => null
        };

        $ecoles = School::with('user')
            ->when(!is_null($status), fn($query) => $query->where('status', $status))
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $count = [
            'countEcole' => School::where('status', 'validated')->count(),
            'countEcolePending' => School::where('status', 'pending')->count()
        ];

        $view = match ($status) {
            'validated' => "admin/school/School",
            'pending' => "admin/school/SchoolPending",
        };

        return Inertia($view, [
            'ecoles' => $ecoles,
            'filters' => $request->only('search'),
            'count' => $count
        ]);
    }


    public function valider($id)
    {
        $ecole = School::findOrFail($id);
        $ecole->status = 'validated';
        $ecole->save();

        $owner = $ecole->user;
        if ($owner && $owner->role === 'pending_owner') {
            $owner->role = 'owner';
            $owner->save();
        }

        return redirect()->back()->with('success', 'École validée avec succès.');
    }

    public function destroy(School $ecole)
    {
        $ecole->delete();
        return redirect()->back()->with('success', 'Ecole supprimé avec succès.');
    }
}
