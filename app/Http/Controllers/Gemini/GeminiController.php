<?php

namespace App\Http\Controllers\Gemini;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class GeminiController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('gemini/Gemini');
    }
}
