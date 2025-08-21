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

        // École la plus populaire (moyenne des étoiles)
        $mostRatedSchool = School::where('status', 'validated')
            ->withAvg('ratings as average_rating', 'etoile')
            ->orderByDesc('average_rating')
            ->first();

        // École la plus consultée (vues)
        $mostViewedSchool = School::where('status', 'validated')
            ->orderByDesc('views')
            ->first();

        // Graphiques de tendances - utilisateurs par semaine
        $userTrendLabels = [];
        $userTrendData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $userTrendLabels[] = $date->format('d M');
            $userTrendData[] = User::whereDate('created_at', $date)
                ->count();
        }

        // Graphiques de tendances - écoles créées par semaine
        $schoolTrendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $schoolTrendData[] = School::where('status', 'validated')
                ->whereDate('created_at', $date)
                ->count();
        }

        // Graphiques de tendances - avis par semaine
        $avisTrendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $avisTrendData[] = Testimonial::where('status', true)
                ->whereDate('created_at', $date)
                ->count();
        }

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

            // Écoles populaires
            'mostRatedSchool' => $mostRatedSchool ? [
                'id' => $mostRatedSchool->id,
                'name' => $mostRatedSchool->name,
                'average_rating' => round($mostRatedSchool->average_rating, 2),
                'views' => $mostRatedSchool->views,
            ] : null,

            'mostViewedSchool' => $mostViewedSchool ? [
                'id' => $mostViewedSchool->id,
                'name' => $mostViewedSchool->name,
                'average_rating' => round($mostViewedSchool->ratings()->avg('etoile') ?? 0, 2),
                'views' => $mostViewedSchool->views,
            ] : null,

            // Données graphiques
            'userTrendLabels' => $userTrendLabels,
            'userTrendData' => $userTrendData,
            'schoolTrendData' => $schoolTrendData,
            'avisTrendData' => $avisTrendData,
        ];

        return Inertia("admin/dashboard/Index", [
            'data' => $data
        ]);
    }
}
