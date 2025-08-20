<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\School;
use App\Models\Testimonial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data = [
            'countUser' => User::where('role', 'user')->count(),
            'userWeekCount' => User::where('role', 'user')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),

            'countOwner' => User::where('role', 'owner')->count(),
            'ownerWeekCount' => User::where('role', 'owner')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),

            'countOwnerPending' => User::where('role', 'pending_owner')->count(),
            'ownerendingWeekCount' => User::where('role', 'pending_owner')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),

            'countSchool' => School::where('status', 'validated')->count(),
            'countSchoolWeek' => School::where('status', 'validated')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),

            'countSchoolPending' => School::where('status', 'pending')->count(),
            'countSchoolPendingWeek' => School::where('status', 'pending')->whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),

            'CountAvis' => Testimonial::count(),
            'CountAvisWeek' => Testimonial::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),

            'ContactSite' => Contact::count(),
            'ContactSiteWeek' => Contact::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count(),
        ];

        return Inertia("admin/dashboard/Index", [
            'data' => $data
        ]);
    }
}
