<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorites;
use App\Models\Rating;
use App\Models\School;
use App\Models\SchoolTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EtablissementController extends Controller
{
    public function index(Request $request, $categorie)
    {
        $categorieRoute = [
            'scolaire' => 1,
            'superieur' => 2,
            'formation' => 3,
            'specialisees' => 4
        ];

        if (!array_key_exists($categorie, $categorieRoute)) {
            return redirect()->route('etablissement.categorie', ['categorie' => 'scolaire'])
                ->with('error', 'Établissement non trouvé.');
        }

        $categoryId = $categorieRoute[$categorie];
        $search = trim($request->input('search'));
        $type = $request->input('type');

        $schools = School::where('category_id', $categoryId)
            ->where('status', 'validated')
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->when($type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->with('info')
            ->withAvg('ratings as average_rating', 'etoile')
            ->orderByDesc('average_rating')
            ->orderByDesc('views')
            ->paginate(6);


        return Inertia('user/etablissement/Index', [
            'data' => [
                'categorie' => $categorie,
                'schools' => $schools,
                'search' => $search,
                'type' => $type
            ]
        ]);
    }

    public function rate(Request $request, $schoolId)
    {
        $request->validate([
            'etoile' => 'required|integer|min:1|max:5',
        ]);

        $userId = auth()->id();

        Rating::updateOrCreate(
            [
                'school_id' => $schoolId,
                'user_id' => $userId,
            ],
            [
                'etoile' => $request->etoile,
            ]
        );

        return response()->json([
            'message' => 'Note enregistrée',
            'average' => Rating::where('school_id', $schoolId)->avg('etoile'),
        ]);
    }

    public function show($slug)
    {
        $ecole = School::with(['user', 'info', 'ratings', 'galeryImages', 'about', 'Schooltestimonials.user', 'formations' => function ($query) {
            $query->where('status', 'published');
        },])
            ->where('slug', $slug)
            ->first();

        if (!$ecole) {
            return redirect()
                ->route('etablissement.categorie', ['categorie' => 'scolaire']);
        }

        if (auth()->check()) {
            $userId = auth()->id();

            $alreadyViewed = DB::table('user_school_views')
                ->where('user_id', $userId)
                ->where('school_id', $ecole->id)
                ->exists();

            if (!$alreadyViewed) {
                $ecole->increment('views');

                DB::table('user_school_views')->insert([
                    'user_id' => $userId,
                    'school_id' => $ecole->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Sinon gérer visiteur anonyme (optionnel - voir plus bas)

        $averageRating = $ecole->ratings()->avg('etoile') ?? 0;

        $userRating = auth()->check()
            ? $ecole->ratings()->where('user_id', auth()->id())->value('etoile')
            : null;

        $isFavorited = false;

        if (Auth::check()) {
            $isFavorited = Auth::user()
                ->favorites()
                ->where('school_id', $ecole->id)
                ->exists();
        }

        // dd($isFavorited);
        // dd(Auth::user()->favorites()->pluck('school_id'));
        // dd(Auth::user()->favorites()->pluck('school_id'));

        return Inertia('user/etablissement/Show', [
            'schools' => $ecole,
            'average_rating' => round($averageRating, 1),
            'user_rating' => $userRating,
            'gallery' => $ecole->galeryImages ?? [],
            'isFavorited' => $isFavorited,
        ]);
    }

    public function incrementViews(Request $request, $id)
    {
        $school = School::findOrFail($id);
        $school->increment('views');

        return response()->json(['success' => true]);
    }

    public function Testimonials($slug)
    {
        $school = School::where('slug', $slug)->firstOrFail();

        $user = Auth::user()->id;

        // dd($user);

        $data = [
            'user' => $user,
            'slug' => $slug,
            'school' => $school,
        ];
        // dd($school);
        return Inertia('user/etablissement/Testimonials', [
            'data' =>  $data
        ]);
    }

    public function storeTestimonial(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'content' => 'required|string|max:100',
        ]);

        SchoolTestimonial::Create(
            [
                'user_id' => $request['user_id'],
                'school_id' => $request['school_id'],
                'content' => $request['content'],
            ]
        );
    }

    public function toggleFavorite(School $school)
    {
        $user = Auth::user();

        if ($user->favorites()->where('school_id', $school->id)->exists()) {
            $user->favorites()->detach($school->id); // retirer des favoris
        } else {
            $user->favorites()->attach($school->id); // ajouter aux favoris
        }

        return back();
    }

    public function favoris()
    {
        $user = Auth::user();

        $favorites = $user->favorites()->with('info')->get();

        // dd($favorites);

        return Inertia('user/etablissement/Favoris', [
            'favorites' => $favorites,
        ]);
    }

    public function destroy(Favorites $favoris)
    {
        $favoris->delete();
        return redirect()->back()->with('success', 'Favorite supprimé avec succès.');
    }
}
