<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && is_null($user->email_verified_at)) {
            return redirect()->route('verification.notice')
                ->with('message', 'Veuillez vérifier votre adresse email avant d’accéder.');
        }

        return Inertia('user/Faq');
    }
}
