<?php

namespace App\Http\Controllers\Owner\Owner;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Formation;
use App\Models\School;
use App\Models\SchoolTestimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerStatController extends Controller
{
    public function index()
    {
        $school = Auth::user()
            ->school()
            ->with(['info'])
            ->first();

        $count_testimonial_true = SchoolTestimonial::where('school_id', $school->id)
            ->where('status', true)
            ->count();

        $count_testimonial_false = SchoolTestimonial::where('school_id', $school->id)
            ->where('status', false)
            ->count();

        $count_formation_published = Formation::where('school_id', $school->id)
            ->where('status', 'published')
            ->count();

        $count_formation_draft = Formation::where('school_id', $school->id)
            ->where('status', 'draft')
            ->count();

        $count_formation_archived = Formation::where('school_id', $school->id)
            ->where('status', 'archived')
            ->count();

        $count_Activity_published = Activity::where('school_id', $school->id)
            ->where('status', 'published')
            ->count();

        $count_Activity_draft = Activity::where('school_id', $school->id)
            ->where('status', 'draft')
            ->count();

        $count_Activity_archived = Activity::where('school_id', $school->id)
            ->where('status', 'archived')
            ->count();


        // dd($count_testimonial_false);

        $data = [
            'school' => $school,
            'count_testimonial_true' => $count_testimonial_true,
            'count_testimonial_false' => $count_testimonial_false,

            'count_formation_published' => $count_formation_published,
            'count_formation_draft' => $count_formation_draft,
            'count_formation_archived' => $count_formation_archived,

            'count_Activity_published' => $count_Activity_published,
            'count_Activity_draft' => $count_Activity_draft,
            'count_Activity_archived' => $count_Activity_archived
        ];

        // dd($school->info->date_creation);
        return Inertia('owner/owner/stat/Index', [
            'data' => $data
        ]);
    }
}
