<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::with('user')
            ->orderBy('created_at','desc')
            ->paginate(7);

        $data = [
            'contacts' => $contacts,
            'countTestimonial' => Testimonial::count(),
            'countContact' => Contact::count()
        ];

        return Inertia("admin/testimonial_contact/Contact", [
            'data' => $data
        ]);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->back()->with('success', 'Contact supprimé avec succès.');
    }
}
