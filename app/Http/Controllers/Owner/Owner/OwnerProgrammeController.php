<?php

namespace App\Http\Controllers\Owner\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerProgrammeRequest;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OwnerProgrammeController extends Controller
{
    public function indexByStatus($status)
    {
        $school = Auth::user()->school()->first();

        $formations = $school
            ->formations()
            ->where('status', $status)
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $counts = [
            'published' => $school->formations()->where('status', 'published')->count(),
            'draft'     => $school->formations()->where('status', 'draft')->count(),
            'archived'  => $school->formations()->where('status', 'archived')->count(),
        ];

        $viewMap = [
            'published' => 'owner/owner/formation/Index',
            'draft'     => 'owner/owner/formation/Index2',
            'archived'  => 'owner/owner/formation/Index3',
        ];

        if (!isset($viewMap[$status])) {
            abort(404);
        }

        $data = [
            'school' => $school,
            'formations' => $formations,
            'counts' => $counts,
        ];

        return Inertia($viewMap[$status], [
            'data' => $data
        ]);
    }


    public function add()
    {
        $schoolId = Auth::user()->school->id;
        return Inertia('owner/owner/formation/page/Add', [
            'schoolId' => $schoolId
        ]);
    }

    public function edit($slug)
    {
        $schoolId = Auth::user()->school->id;

        $formation = Formation::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$formation) {
            return redirect()->route('owner.formation.status', ['status' => 'draft'])
                ->with('error', 'Formation introuvable ou non autorisée.');
        }

        return Inertia('owner/owner/formation/page/Edit', [
            'formation' => $formation
        ]);
    }

    public function show($slug)
    {
        $schoolId = Auth::user()->school->id;

        $formation = Formation::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$formation) {
            return redirect()->route('owner.formation.status', ['status' => 'draft'])
                ->with('error', 'Formation introuvable ou non autorisée.');
        }

        return Inertia('owner/owner/formation/page/Show', [
            'formation' => $formation
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'cover_photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096',
            ],
            'title' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_photo')) {
            $coverPath = $request->file('cover_photo')->store('formations', 'public');
        }

        $slug = Str::slug($validated['title']) . '-' . rand(10000, 99999);

        Formation::create([
            'school_id' => $validated['school_id'],
            'cover_photo' => $coverPath ? '/storage/' . $coverPath : null,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Formation Ajoutée avec succès.');
    }

    public function update(Request $request, $slug)
    {
        // dd($request->all());
        $formation = Formation::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'cover_photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:4096',
            ],
            'title' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        if ($request->hasFile('cover_photo')) {
            $coverPath = $request->file('cover_photo')->store('formations', 'public');
            $formation->cover_photo = '/storage/' . $coverPath;
        }

        $formation->school_id = $validated['school_id'];
        $formation->title = $validated['title'];
        $formation->description = $validated['description'];

        if ($formation->isDirty('title')) {
            $formation->slug = Str::slug($validated['title']) . '-' . rand(10000, 99999);
        }

        $formation->save();

        return redirect()->back()->with('success', 'Formation mise à jour avec succès.');
    }

    public function published($slug)
    {
        $schoolId = Auth::user()->school->id;

        $formation = Formation::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$formation) {
            return response()->json(['error' => 'Formation introuvable ou non autorisée.'], 404);
        }

        $formation->status = 'published';
        $formation->save();
    }

    public function draft($slug)
    {
        $schoolId = Auth::user()->school->id;

        $formation = Formation::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$formation) {
            return response()->json(['error' => 'Formation introuvable ou non autorisée.'], 404);
        }

        $formation->status = 'draft';
        $formation->save();
    }

    public function archived($slug)
    {
        $schoolId = Auth::user()->school->id;

        $formation = Formation::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$formation) {
            return response()->json(['error' => 'Formation introuvable ou non autorisée.'], 404);
        }

        $formation->status = 'archived';
        $formation->save();
    }

    public function destroy(Formation $formation)
    {
        $formation->delete();
        return redirect()->back()->with('success', 'Formation supprimé avec succès.');
    }
}
