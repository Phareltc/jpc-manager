<?php

namespace App\Http\Controllers;

use App\Models\Membre; // IMPORTANT : pour accéder à la table membres
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = now();
        
        return Inertia::render('Dashboard', [
            'stats' => [
                'total_membres' => Membre::count(),
                'hommes' => Membre::where('sexe', 'M')->count(),
                'femmes' => Membre::where('sexe', 'F')->count(),
            ],
            'anniversaires' => Membre::whereMonth('date_naissance', $now->month)
                ->orderByRaw('DAY(date_naissance) ASC')
                ->get(),
            'recents' => Membre::latest()->take(5)->get(),
        ]);
    }
}
