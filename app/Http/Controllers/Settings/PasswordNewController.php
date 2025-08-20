<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PasswordNewController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('settings/PasswordNew');
    }
}
