<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user && is_null($user->email_verified_at)) {
            return redirect()->route('verification.notice')
                ->with('message', 'Veuillez vérifier votre adresse email avant d’accéder.');
        }

        return Inertia("user/contact/Index");
    }

    public function storeContact(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'message' => 'required|max:30',
        ]);

        Contact::Create([
            'user_id' => Auth::user()->id,
            'message' => $validated['message'],
        ]);
   }
}
