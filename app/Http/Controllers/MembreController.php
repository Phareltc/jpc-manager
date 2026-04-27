<?php

namespace App\Http\Controllers;

use App\Models\Membre;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facade\Pdf; // Import crucial pour le PDF

class MembreController extends Controller
{
    /**
     * Affichage de la liste des membres avec filtres
     */
    public function index(Request $request)
    {
        $query = Membre::query();

        // Système de recherche et filtres
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                    ->orWhere('prenom', 'like', "%{$request->search}%");
            });
        }

        if ($request->sexe) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->niveau_etude) {
            $query->where('niveau_etude', $request->niveau_etude);
        }

        return Inertia::render('Membres/Index', [
            'membres' => $query->latest()->get(),
            'filters' => $request->only(['search', 'sexe', 'niveau_etude'])
        ]);
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return Inertia::render('Membres/Create');
    }

    /**
     * Enregistrement d'un nouveau membre
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'niveau_etude' => 'nullable|string',
            'statut_matrimonial' => 'nullable|string',
        ]);

        Membre::create($validated);

        return Redirect::route('membres.index')->with('success', 'Membre ajouté avec succès !');
    }

    /**
     * Affichage des détails d'un membre
     */
    public function show(Membre $membre)
    {
        return Inertia::render('Membres/Show', [
            // On charge les scolarités triées par année la plus récente
            'membre' => $membre->load(['scolarites' => function ($query) {
                $query->orderBy('annee_scolaire', 'desc');
            }])
        ]);
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Membre $membre)
    {
        return Inertia::render('Membres/Edit', [
            'membre' => $membre
        ]);
    }

    /**
     * Mise à jour des données
     */
    public function update(Request $request, Membre $membre)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'sexe' => 'required|in:M,F',
            'date_naissance' => 'required|date',
            'niveau_etude' => 'nullable|string',
            'statut_matrimonial' => 'nullable|string',
        ]);

        $membre->update($validated);

        return Redirect::route('membres.index')->with('success', 'Informations mises à jour.');
    }

    /**
     * Suppression d'un membre
     */
    public function destroy(Membre $membre)
    {
        $membre->delete();

        return Redirect::route('membres.index')->with('danger', 'Membre supprimé.');
    }

    /**
     * EXPORT EXCEL (Format CSV)
     */
    public function export()
    {
        $membres = Membre::all();
        $filename = "liste-membres-jpc.csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nom', 'Prenom', 'Sexe', 'Date Naissance', 'Etudes'];

        $callback = function () use ($membres, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM pour les accents
            fputcsv($file, $columns, ';');

            foreach ($membres as $m) {
                fputcsv($file, [$m->nom, $m->prenom, $m->sexe, $m->date_naissance, $m->niveau_etude], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * EXPORT PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Membre::query();

        // On applique les mêmes filtres que sur la page de liste
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                    ->orWhere('prenom', 'like', "%{$request->search}%");
            });
        }

        if ($request->sexe) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->niveau_etude) {
            $query->where('niveau_etude', $request->niveau_etude);
        }

        $membres = $query->get();

        $pdf = Pdf::loadView('pdf.liste-membres', compact('membres'));

        return $pdf->download('liste-membres-filtree.pdf');
    }
}
