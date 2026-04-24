<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Membre;
use Inertia\Inertia;

class MembreController extends Controller
{
    public function index(Request $request)
    {
        // On commence la requête
        $query = Membre::query();

        // 1. Filtre de recherche textuelle (Nom/Prénom)
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                    ->orWhere('prenom', 'like', "%{$request->search}%");
            });
        }

        // 2. Filtres exacts (Sexe, Niveau d'étude, Statut Matrimonial)
        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->filled('niveau_etude')) {
            $query->where('niveau_etude', $request->niveau_etude);
        }

        if ($request->filled('statut_matrimonial')) {
            $query->where('statut_matrimonial', $request->statut_matrimonial);
        }

        // 3. Filtre de date exacte
        if ($request->filled('date_naissance')) {
            $query->whereDate('date_naissance', $request->date_naissance);
        }

        return Inertia::render('Membres/Index', [
            // On récupère les résultats filtrés
            'membres' => $query->latest()->get(),
            // On renvoie les filtres au frontend pour que les champs restent remplis
            'filters' => $request->only(['search', 'sexe', 'niveau_etude', 'statut_matrimonial', 'date_naissance']),
        ]);
    }

    public function show(Membre $membre)
    {
        return Inertia::render('Membres/Show', [
            'membre' => $membre
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
            'structure_mouvement' => 'nullable|string',
        ]);

        Membre::create($validated);

        return redirect()->route('membres.index')->with('success', 'Nouveau membre ajouté avec succès !');
    }

    public function edit(Membre $membre)
    {
        // On formate la date pour l'input HTML5 (YYYY-MM-DD)
        $membre->date_naissance = \Carbon\Carbon::parse($membre->date_naissance)->format('Y-m-d');

        if ($membre->date_bapteme) {
            $membre->date_bapteme = \Carbon\Carbon::parse($membre->date_bapteme)->format('Y-m-d');
        }

        return Inertia::render('Membres/Edit', [
            'membre' => $membre
        ]);
    }

    public function update(Request $request, Membre $membre)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'date_bapteme' => 'nullable|date',
            'telephone' => 'nullable|string|max:20',
            'niveau_etude' => 'required|string',
            'statut_professionnel' => 'required|string',
            'role_spirituel' => 'required|string',
            'statut_matrimonial' => 'required|string',
            'structure_mouvement' => 'nullable|string',
        ]);

        $membre->update($validated);

        return redirect()->route('membres.index')->with('warning', 'Les informations du membre ont été modifiées.');
    }

    public function destroy(Membre $membre)
    {
        $membre->delete();

        return redirect()->route('membres.index')->with('danger', 'Le membre a été supprimé de la base de données.');
    }
}
