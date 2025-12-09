<?php

namespace App\Http\Controllers\Laravel;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class ViewController extends Controller
{

    public function landing(): Response 
    {
        return Inertia::render('Welcome', [
            'canRegister' => Features::enabled(Features::registration()),
        ]);
    }

    public function dashboard(): Response
    {
        return Inertia::render('Dashboard');
    }
}
