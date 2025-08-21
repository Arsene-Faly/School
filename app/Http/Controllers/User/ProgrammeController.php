<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityView;
use App\Models\Formation;
use App\Models\FormationView;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgrammeController extends Controller
{
    public function index(Request $request, string $categorie)
    {
        $categories = [
            'formation' => Formation::class,
            'activite'  => Activity::class,
        ];

        if (!array_key_exists($categorie, $categories)) {
            return redirect()
                ->route('programme.categorie', ['categorie' => 'formation'])
                ->with('error', 'Catégorie non trouvée.');
        }

        $model = $categories[$categorie];
        $search = trim($request->input('search', ''));

        $datas = $model::where('status', 'published')
            ->with('school')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(6)
            ->appends($request->only('search'));

        foreach ($datas as $item) {
            $item->views_count = $item->views()->count();
        }

        return Inertia::render('user/programme/Index', [
            'data' => [
                'categorie' => $categorie,
                'datas'     => $datas,
                'search'    => $search,
            ]
        ]);
    }

    public function show(string $categorie, string $slug)
    {
        $categories = [
            'formation' => Formation::class,
            'activite'  => Activity::class,
        ];

        if (!array_key_exists($categorie, $categories)) {
            return redirect()
                ->route('programme.categorie', ['categorie' => 'formation'])
                ->with('error', 'Catégorie non trouvée.');
        }

        $model = $categories[$categorie];

        $formation = $model::with('school')
            ->withCount('views')
            ->where('slug', $slug)
            ->first();

        if (!$formation) {
            return redirect()
                ->route('programme.categorie', ['categorie' => $categorie])
                ->with('error', ucfirst($categorie) . ' non trouvée.');
        }

        $userId = auth()->id();
        $ip = request()->ip();

        if ($categorie === 'formation') {
            FormationView::firstOrCreate([
                'formation_id' => $formation->id,
                'user_id'      => $userId,
                'ip_address'   => $userId ? null : $ip,
            ]);

            // Conversion JSON -> tableau pour Vue
            $formation->level = $formation->level ? json_decode($formation->level, true) : [];
            $formation->category = $formation->category ? json_decode($formation->category, true) : [];
        } else {
            ActivityView::firstOrCreate([
                'activity_id' => $formation->id,
                'user_id'     => $userId,
                'ip_address'  => $userId ? null : $ip,
            ]);
        }

        return Inertia::render('user/programme/Show', [
            'formation' => $formation,
            'categorie' => $categorie,
            'views_count' => $formation->views_count,
        ]);
    }
}
