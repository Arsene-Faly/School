<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && is_null($user->email_verified_at)) {
            return redirect()->route('verification.notice')
                ->with('message', 'Veuillez vérifier votre adresse email avant d’accéder.');
        }

        $testimonial = Testimonial::where('status', true)->with('user')->get();

        $schools = School::where('status', 'validated')
            ->with('info')
            ->withAvg('ratings as average_rating', 'etoile')
            ->with([
                'formations' => function ($query) {
                    $query->where('status', 'published');
                },
                'activity' => function ($query) {
                    $query->where('status', 'published');
                },
            ])
            ->orderByDesc('average_rating')
            ->orderByDesc('views')
            ->get();

        // dd($school);
        $data = [
            'testimonial' => $testimonial,
            'schools' => $schools
        ];

        return Inertia("user/home/Index", [
            'data' => $data
        ]);
    }
}
