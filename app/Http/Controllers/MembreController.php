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
        return Inertia::render('Membres/Index', [
            // On récupère les membres en filtrant par nom ou prénom si une recherche existe
            'membres' => Membre::query()
                ->when($request->input('search'), function ($query, $search) {
                    $query->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                })
                ->latest()
                ->get(),
            // On renvoie la valeur de recherche pour la réafficher dans l'input
            'filters' => $request->only(['search'])
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
