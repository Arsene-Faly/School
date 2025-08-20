<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('user')
            ->orderBy('created_at','desc')
            ->paginate(7);

        $data = [
            'testimonial' => $testimonials,
            'countTestimonial' => Testimonial::count(),
            'countContact' => Contact::count()
        ];

        return Inertia("admin/testimonial_contact/Testimonial", [
            'data' => $data
        ]);
    }

    public function toggle(Testimonial $testimonial)
    {
        $testimonial->status = !$testimonial->status;
        $testimonial->save();

        return redirect()->back()->with('success', 'Statut du témoignage mis à jour.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->back()->with('success', 'Témoignage supprimé avec succès.');
    }
}
