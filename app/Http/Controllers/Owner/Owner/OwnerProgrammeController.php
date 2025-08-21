<?php

namespace App\Http\Controllers\Owner\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerProgrammeRequest;
use App\Models\Category;
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
        $school = Auth::user()->school;
        $schoolId = $school->id;

        $category = $school->category->name ?? null;

        $types = [
            'Établissement Scolaire',
            'Établissement Supérieur',
            'Centre de formation',
            'École Spécialisée',
        ];

        // Initialisation
        $niveaux = [];
        $categories = [];
        $modes = ['Présentiel', 'En ligne', 'Hybride'];
        $langues = ['fr', 'en', 'es'];
        $statusOptions = ['draft', 'published', 'archived'];

        // Définir niveaux et catégories selon le type
        switch ($category) {
            case $types[0]: // Établissement Scolaire
                $niveaux = ['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale'];
                $categories = ['Mathématiques', 'Français', 'Sciences', 'Langues', 'Arts'];
                break;

            case $types[1]: // Établissement Supérieur
                $niveaux = ['L1', 'L2', 'L3', 'Master', 'Doctorat'];
                $categories = ['Informatique', 'Marketing', 'Finance', 'Droit', 'Gestion', 'Santé', 'Arts'];
                break;

            case $types[2]: // Centre de formation
                $niveaux = ['Certification', 'Diplôme'];
                $categories = ['Informatique', 'Marketing', 'Bureautique', 'Langues', 'Commerce', 'Design'];
                break;

            case $types[3]: // École Spécialisée
                $niveaux = ['Certificat', 'Diplôme', 'Licence'];
                $categories = ['Arts', 'Musique', 'Sport', 'Cuisine', 'Mode', 'Design'];
                break;

            default:
                $niveaux = ['6ème', '5ème', '4ème', '3ème'];
                $categories = ['Mathématiques', 'Français', 'Sciences', 'Langues', 'Arts'];
                break;
        }

        return Inertia('owner/owner/formation/page/Add', [
            'schoolId' => $schoolId,
            'categories' => $categories,
            'niveaux' => $niveaux,
            'modes' => $modes,
            'langues' => $langues,
            'statusOptions' => $statusOptions,
            'schoolCategory' => $category,
            'types' => $types
        ]);
    }

    public function edit($slug)
    {
        $school = Auth::user()->school;
        $schoolId = $school->id;
        $category = $school->category->name ?? null;

        // Récupérer la formation
        $formation = Formation::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$formation) {
            return redirect()->route('owner.formation.status', ['status' => 'draft'])
                ->with('error', 'Formation introuvable ou non autorisée.');
        }

        // Définir niveaux et catégories selon le type (même logique que add)
        $types = [
            'Établissement Scolaire',
            'Établissement Supérieur',
            'Centre de formation',
            'École Spécialisée',
        ];

        $niveaux = [];
        $categories = [];
        $modes = ['Présentiel', 'En ligne', 'Hybride'];
        $langues = ['fr', 'en', 'es'];
        $statusOptions = ['draft', 'published', 'archived'];

        switch ($category) {
            case $types[0]:
                $niveaux = ['6ème', '5ème', '4ème', '3ème', '2nde', '1ère', 'Terminale'];
                $categories = ['Mathématiques', 'Français', 'Sciences', 'Langues', 'Arts'];
                break;
            case $types[1]:
                $niveaux = ['L1', 'L2', 'L3', 'Master', 'Doctorat'];
                $categories = ['Informatique', 'Marketing', 'Finance', 'Droit', 'Gestion', 'Santé', 'Arts'];
                break;
            case $types[2]:
                $niveaux = ['Certification', 'Diplôme'];
                $categories = ['Informatique', 'Marketing', 'Bureautique', 'Langues', 'Commerce', 'Design'];
                break;
            case $types[3]:
                $niveaux = ['Certificat', 'Diplôme', 'Licence'];
                $categories = ['Arts', 'Musique', 'Sport', 'Cuisine', 'Mode', 'Design'];
                break;
            default:
                $niveaux = ['6ème', '5ème', '4ème', '3ème'];
                $categories = ['Mathématiques', 'Français', 'Sciences', 'Langues', 'Arts'];
                break;
        }

        return Inertia('owner/owner/formation/page/Edit', [
            'formation' => $formation,
            'schoolId' => $schoolId,
            'categories' => $categories,
            'niveaux' => $niveaux,
            'modes' => $modes,
            'langues' => $langues,
            'statusOptions' => $statusOptions,
            'schoolCategory' => $category,
            'types' => $types
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
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'required|array|min:1',
            'category.*' => 'string|max:50',
            'level' => 'required|array|min:1',
            'level.*' => 'string|max:50',
            'duration' => 'nullable|integer|min:1',
            'mode' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
            'price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:draft,published',
        ]);

        $coverPath = $request->hasFile('cover_photo')
            ? '/storage/' . $request->file('cover_photo')->store('formations', 'public')
            : null;

        $slug = Str::slug($validated['title']) . '-' . rand(10000, 99999);

        Formation::create([
            'school_id' => $validated['school_id'],
            'cover_photo' => $coverPath,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'category' => json_encode($validated['category']),
            'level' => json_encode($validated['level']),
            'duration' => $validated['duration'] ?? null,
            'mode' => $validated['mode'] ?? null,
            'language' => $validated['language'] ?? 'fr',
            'price' => $validated['price'] ?? null,
            'status' => $validated['status'] ?? 'draft',
        ]);

        return redirect()->back()->with('success', 'Formation ajoutée avec succès.');
    }

    public function update(Request $request, $slug)
    {
        $formation = Formation::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'category' => 'required|array|min:1',
            'category.*' => 'string|max:50',
            'level' => 'required|array|min:1',
            'level.*' => 'string|max:50',
            'duration' => 'nullable|integer|min:1',
            'mode' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:10',
            'price' => 'nullable|numeric|min:0',
            'status' => 'nullable|string|in:draft,published',
        ]);

        if ($request->hasFile('cover_photo')) {
            $formation->cover_photo = '/storage/' . $request->file('cover_photo')->store('formations', 'public');
        }

        $formation->school_id = $validated['school_id'];
        $formation->title = $validated['title'];
        $formation->description = $validated['description'] ?? null;
        $formation->category = json_encode($validated['category']);
        $formation->level = json_encode($validated['level']);
        $formation->duration = $validated['duration'] ?? null;
        $formation->mode = $validated['mode'] ?? null;
        $formation->language = $validated['language'] ?? 'fr';
        $formation->price = $validated['price'] ?? null;
        $formation->status = $validated['status'] ?? 'draft';

        // Regénérer le slug si le titre a changé
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
