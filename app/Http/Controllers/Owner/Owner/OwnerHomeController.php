<?php

namespace App\Http\Controllers\Owner\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\OwnerSchoolInfoRequest;
use App\Http\Requests\Owner\OwnerSchoolRequest;
use App\Models\Category;
use App\Models\GalerySchool;
use App\Models\School;
use App\Models\SchoolAbout;
use App\Models\SchoolInfo;
use App\Models\SchoolTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OwnerHomeController extends Controller
{
    public function index()
    {
        return Inertia('owner/owner/school/Index');
    }

    public function edit()
    {
        $school = Auth::user()->school;
        $category = Category::all();

        $data = [
            'school' => $school,
            'category' => $category
        ];

        return Inertia('owner/owner/school/FormSchool', [
            'data' => $data
        ]);
    }

    public function about()
    {
        $school = Auth::user()->school;
        $about = $school->about ?? new SchoolAbout();

        return Inertia('owner/owner/school/FormAbout', [
            'schools' => $about
        ]);
    }

    public function aboutUpdate(Request $request)
    {
        $validated = $request->validate([
            'mission' => 'required|string|max:255',
            'vision' => 'required|string|max:255',
            'description' => 'required|string',
            'history' => 'nullable|string',
        ]);

        $school = Auth::user()->school()->firstOrFail();

        SchoolAbout::updateOrCreate(
            ['school_id' => $school->id],
            [
                'mission' => $validated['mission'],
                'vision' => $validated['vision'],
                'description' => $validated['description'],
                'history' => $validated['history'] ?? null,
            ]
        );

        return back()->with('success', 'Informations de l’école mises à jour avec succès.');
        // dd($request->all());
    }

    public function update(OwnerSchoolRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
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
                'category_id' => $validated['category_id'],
                'type' => $validated['type'],

            ]
        );
    }

    public function editInfo()
    {
        $user = auth()->user();

        $school = $user->school()->with('info')->first();

        $schoolInfo = $school ? $school->info : null;

        if ($schoolInfo) {
            $schoolInfo->logo_url = $schoolInfo->logo ? Storage::url($schoolInfo->logo) : null;
            $schoolInfo->cover_url = $schoolInfo->cover_photo ? Storage::url($schoolInfo->cover_photo) : null;
        }

        return Inertia('owner/owner/school/FormSchoolInfo', [
            'schoolInfo' => $schoolInfo,
        ]);
    }

    public function updateInfo(OwnerSchoolInfoRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        if (! $user || ! $user->school) {
            return redirect()->back()->with('error', 'École introuvable pour cet utilisateur.');
        }

        $school_id = $user->school->id;

        $data = [
            'latitude'    => $validated['latitude'] ?? null,
            'longitude'   => $validated['longitude'] ?? null,
            'description' => $validated['description'] ?? null,
            'date_creation' => $validated['date_creation'] ?? null,
            'nombre_etudiants' => $validated['nombre_etudiants'] ?? null,
        ];

        $schoolInfo = SchoolInfo::where('school_id', $school_id)->first();

        if ($request->hasFile('logo')) {
            $storedLogoPath = $request->file('logo')->store('logos', 'public');

            if ($schoolInfo && $schoolInfo->logo) {
                $oldPath = $this->stripStoragePrefix($schoolInfo->logo);
                Storage::disk('public')->delete($oldPath);
            }

            $data['logo'] = Storage::url($storedLogoPath);
        }

        if ($request->hasFile('cover_photo')) {
            $storedCoverPath = $request->file('cover_photo')->store('covers', 'public');

            if ($schoolInfo && $schoolInfo->cover_photo) {
                $oldCover = $this->stripStoragePrefix($schoolInfo->cover_photo);
                Storage::disk('public')->delete($oldCover);
            }

            $data['cover_photo'] = Storage::url($storedCoverPath);
        }

        $schoolInfo = SchoolInfo::updateOrCreate(
            ['school_id' => $school_id],
            $data
        );

        return redirect()->back()->with('success', 'Informations de l\'école enregistrées.');
    }

    private function stripStoragePrefix(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return preg_replace('#^/?storage/#', '', $path);
    }

    public function show()
    {
        $school = Auth::user()
            ->school()
            ->with([
                'info',
                'galeryImages',
                'Schooltestimonials' => function ($query) {
                    $query->where('status', true);
                },
                'Schooltestimonials.user',
                'formations' => function ($query) {
                    $query->where('status', 'published');
                },
                'about',
                'activity' => function ($query) {
                    $query->where('status', 'published');
                },
            ])
            ->first();

        $averageRating = $school->ratings()->avg('etoile') ?? 0;

        $userRating = auth()->check()
            ? $school->ratings()->where('user_id', auth()->id())->value('etoile')
            : null;

        // dd($school);
        return Inertia("owner/owner/school/Show", [
            'school' => $school,
            'average_rating' => round($averageRating, 1),
            'user_rating' => $userRating,
            'gallery' => $school->galeryImages ?? [],
        ]);
    }

    public function Gallery()
    {
        $schoolId = Auth::user()->school->id;

        // dd($schoolId);
        return Inertia('owner/owner/school/Gallery', [
            'schoolId' => $schoolId
        ]);
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        foreach ($request->file('images') as $image) {
            $path = $image->store('school_galleries', 'public');

            $publicPath = 'storage/' . $path;

            GalerySchool::create([
                'school_id' => $validated['school_id'],
                'image_path' => $publicPath,
            ]);
        }

        return back()->with('success', 'Images ajoutées avec succès');
    }

    public function destroyGallery(GalerySchool $image)
    {
        $image->delete();

        return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function Testimonials()
    {
        $school = Auth::user()
            ->school()
            ->first();

        $testimonials = $school
            ->Schooltestimonials()
            ->with('user')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $data = [
            'school' => $school,
            'testimonials' => $testimonials
        ];

        return Inertia('owner/owner/school/TestimonialsList', [
            'data' => $data
        ]);
    }

    public function toggle(SchoolTestimonial $testimonial)
    {
        $testimonial->status = !$testimonial->status;
        $testimonial->save();

        return redirect()->back()->with('success', 'Statut du témoignage mis à jour.');
    }

    public function destroy(SchoolTestimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->back()->with('success', 'Témoignage supprimé avec succès.');
    }
}
