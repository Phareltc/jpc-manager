<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\TypeContribution;
use App\Models\Membre;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Carbon\Carbon;

class ContributionController extends Controller
{
    /**
     * Afficher la liste de toutes les contributions.
     */
    public function index(Request $request): Response
    {
        $typesDisponibles = TypeContribution::select('id', 'libelle', 'categorie', 'montant_defaut')
            ->orderBy('libelle')
            ->get();

        $annee = (int) $request->input('annee', date('Y'));
        $search = $request->input('search');

        // Sélection du type filtré (ou le premier disponible par défaut)
        $defaultTypeId = $typesDisponibles->first()?->id;
        $typeId = $request->filled('type_id') ? (int) $request->input('type_id') : $defaultTypeId;

        $membresQuery = Membre::query()
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%");
                });
            })
            ->with(['contributions' => function ($query) use ($annee, $typeId) {
                $query->where('annee_concernee', $annee)
                      ->when($typeId, fn($q) => $q->where('type_contribution_id', $typeId));
            }])
            ->orderBy('nom')
            ->orderBy('prenom');

        $membres = $membresQuery->paginate(20)->withQueryString();

        $membres->getCollection()->transform(function ($membre) {
            $cotisationsIndexees = [];
            $totalPaye = 0;

            foreach ($membre->contributions as $contrib) {
                if ($contrib->mois_concerne) {
                    $dateFormatted = null;
                    if ($contrib->date_paiement) {
                        $dateFormatted = $contrib->date_paiement instanceof Carbon
                            ? $contrib->date_paiement->format('Y-m-d')
                            : (string) $contrib->date_paiement;
                    }

                    $cotisationsIndexees[$contrib->mois_concerne] = [
                        'id' => $contrib->id,
                        'montant' => (float) $contrib->montant,
                        'date_paiement' => $dateFormatted,
                        'mode_paiement' => $contrib->mode_paiement,
                    ];
                }
                $totalPaye += (float) $contrib->montant;
            }

            return [
                'id' => $membre->id,
                'nom' => $membre->nom,
                'prenom' => $membre->prenom,
                'cotisations' => (object) $cotisationsIndexees,
                'total_paye' => $totalPaye,
            ];
        });

        $totalGeneral = Contribution::query()
            ->where('annee_concernee', $annee)
            ->when($typeId, fn($q) => $q->where('type_contribution_id', $typeId))
            ->sum('montant');

        return Inertia::render('Contributions/IndexMatrice', [
            'matrice' => $membres,
            'typesDisponibles' => $typesDisponibles,
            'filters' => [
                'search' => $search,
                'annee' => (string) $annee,
                'type_id' => $typeId,
            ],
            'stats' => [
                'total_general' => (float) $totalGeneral,
            ],
        ]);
    }

    /**
     * Enregistrer une nouvelle contribution.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'membre_id' => 'required|exists:membres,id',
            'type_contribution_id' => 'required|exists:type_contributions,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'annee_concernee' => 'required|integer|min:2000|max:2099',
            'mois_concerne' => 'nullable|integer|between:1,12',
            'mode_paiement' => 'required|string',
            'observation' => 'nullable|string|max:255',
        ]);

        Contribution::create($validated);

        return back()->with('success', 'Paiement enregistré avec succès.');
    }

    /**
     * Mettre à jour une contribution existante.
     */
    public function update(Request $request, Contribution $contribution): RedirectResponse
    {
        $validated = $request->validate([
            'type_contribution_id' => 'required|exists:type_contributions,id',
            'montant' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|string',
            'observation' => 'nullable|string|max:255',
        ]);

        $contribution->update($validated);

        return back()->with('success', 'Contribution mise à jour avec succès.');
    }

    /**
     * Supprimer une contribution.
     */
    public function destroy(Contribution $contribution): RedirectResponse
    {
        $contribution->delete();

        return back()->with('success', 'Contribution supprimée avec succès.');
    }
}