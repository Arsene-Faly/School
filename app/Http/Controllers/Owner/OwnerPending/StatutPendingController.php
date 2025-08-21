<?php

namespace App\Http\Controllers\Owner\OwnerPending;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerPendingvalidationRequest;
use App\Models\Category;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class StatutPendingController extends Controller
{
    public function index()
    {
        $category = Category::all();
        $school = Auth::user()->school;
        return Inertia('owner/ownerPending/Index', [
            'ecole' => $school,
            'categories' => $category
        ]);
    }

    public function store(OwnerPendingvalidationRequest $request)
    {
        $validated = $request->validated();

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        $slug = Str::slug($validated['name']) . '-' . rand(10000, 99999);

        School::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'name' => $validated['name'],
                'slug' => $slug,
                'nif' => $validated['nif'],
                'stat' => $validated['stat'],
                'adresse' => $validated['adresse'],
                'contact' => $validated['contact'],
                'document' => $documentPath ? 'storage/' . $documentPath : null,
                'category_id' => $validated['category_id'], // ajouté
                'type' => $validated['type'], // ajouté
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Informations mises à jour avec succès.');
    }
}
