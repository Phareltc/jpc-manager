<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Membre;
use Inertia\Inertia;

class MembreController extends Controller
{
    public function index()
    {
        return Inertia::render('Membres/Index', [
            'membres' => Membre::latest()->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Membres/Create');
    }

    public function store(Request $request)
    {
        // Affiche TOUT ce que Laravel reçoit réellement de Vue.js
        // Si 'role_spirituel' n'est pas dans la liste noire qui s'affiche, 
        // c'est qu'il n'est pas envoyé.
        // dd($request->all()); 
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'date_bapteme' => 'nullable',
            'telephone' => 'nullable|string',
            'niveau_etude' => 'required|string',
            'statut_professionnel' => 'required|string',
            'role_spirituel' => 'required|string',
            'statut_matrimonial' => 'required|string',
            // N'oublie pas celui-ci s'il est requis en base !
            'structure_mouvement' => 'nullable|string',
        ]);

        Membre::create($validated);

        return redirect()->route('membres.index');
    }
}
