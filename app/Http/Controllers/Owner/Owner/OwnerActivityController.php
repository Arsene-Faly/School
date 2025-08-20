<?php

namespace App\Http\Controllers\Owner\Owner;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OwnerActivityController extends Controller
{
    public function indexByStatus($status)
    {
        $school = Auth::user()->school()->first();

        $activity = $school
            ->Activity()
            ->where('status', $status)
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $counts = [
            'published' => $school->Activity()->where('status', 'published')->count(),
            'draft'     => $school->Activity()->where('status', 'draft')->count(),
            'archived'  => $school->Activity()->where('status', 'archived')->count(),
        ];

        $viewMap = [
            'published' => 'owner/owner/activity/Index',
            'draft'     => 'owner/owner/activity/Index2',
            'archived'  => 'owner/owner/activity/Index3',
        ];

        if (!isset($viewMap[$status])) {
            abort(404);
        }

        $data = [
            'school' => $school,
            'activity' => $activity,
            'counts' => $counts,
        ];

        return Inertia($viewMap[$status], [
            'data' => $data
        ]);
    }

    public function add()
    {
        $schoolId = Auth::user()->school->id;
        return Inertia('owner/owner/activity/page/Add', [
            'schoolId' => $schoolId
        ]);
    }

    public function edit($slug)
    {
        $schoolId = Auth::user()->school->id;

        $activity = Activity::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$activity) {
            return redirect()->route('owner.activity.status', ['status' => 'draft'])
                ->with('error', 'Activitée introuvable ou non autorisée.');
        }

        return Inertia('owner/owner/activity/page/Edit', [
            'activity' => $activity
        ]);
    }

    public function show($slug)
    {
        $schoolId = Auth::user()->school->id;

        $activity = Activity::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$activity) {
            return redirect()->route('owner.activity.status', ['status' => 'draft'])
                ->with('error', 'Activitée introuvable ou non autorisée.');
        }

        return Inertia('owner/owner/activity/page/Show', [
            'activity' => $activity
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
            $coverPath = $request->file('cover_photo')->store('activity', 'public');
        }

        $slug = Str::slug($validated['title']) . '-' . rand(10000, 99999);

        Activity::create([
            'school_id' => $validated['school_id'],
            'cover_photo' => $coverPath ? '/storage/' . $coverPath : null,
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Activitée Ajoutée avec succès.');
    }

    public function update(Request $request, $slug)
    {
        // dd($request->all());
        $activity = Activity::where('slug', $slug)->firstOrFail();

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
            $coverPath = $request->file('cover_photo')->store('activity', 'public');
            $activity->cover_photo = '/storage/' . $coverPath;
        }

        $activity->school_id = $validated['school_id'];
        $activity->title = $validated['title'];
        $activity->description = $validated['description'];

        if ($activity->isDirty('title')) {
            $activity->slug = Str::slug($validated['title']) . '-' . rand(10000, 99999);
        }

        $activity->save();

        return redirect()->back()->with('success', 'Activitée mise à jour avec succès.');
    }

    public function published($slug)
    {
        $schoolId = Auth::user()->school->id;

        $activity = Activity::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$activity) {
            return response()->json(['error' => 'Activitée introuvable ou non autorisée.'], 404);
        }

        $activity->status = 'published';
        $activity->save();
    }

    public function draft($slug)
    {
        $schoolId = Auth::user()->school->id;

        $activity = Activity::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$activity) {
            return response()->json(['error' => 'Activitée introuvable ou non autorisée.'], 404);
        }

        $activity->status = 'draft';
        $activity->save();
    }

    public function archived($slug)
    {
        $schoolId = Auth::user()->school->id;

        $activity = Activity::where('slug', $slug)
            ->where('school_id', $schoolId)
            ->first();

        if (!$activity) {
            return response()->json(['error' => 'Activitée introuvable ou non autorisée.'], 404);
        }

        $activity->status = 'archived';
        $activity->save();
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->back()->with('success', 'Activitée supprimé avec succès.');
    }
}
