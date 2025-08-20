<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AboutController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && is_null($user->email_verified_at)) {
            return redirect()->route('verification.notice')
                ->with('message', 'Veuillez vérifier votre adresse email avant d’accéder.');
        }
        return Inertia("user/about/Index");
    }

    public function storeTestimonials(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'description' => 'required|max:30',
        ]);

        Testimonial::Create([
            'user_id' => Auth::user()->id,
            'description' => $validated['description'],
            'status' => false
        ]);
    }
}
