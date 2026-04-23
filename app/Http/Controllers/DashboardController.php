<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_membres' => Membre::count(),
                'hommes' => Membre::where('sexe', 'M')->count(),
                'femmes' => Membre::where('sexe', 'F')->count(),
                // On pourra ajouter ici la répartition par niveau d'étude plus tard
            ]
        ]);
    }
}