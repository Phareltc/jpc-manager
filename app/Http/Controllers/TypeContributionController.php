<?php

namespace App\Http\Controllers;

use App\Models\TypeContribution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TypeContributionController extends Controller
{
    /**
     * Enregistre un nouveau type de contribution.
     */

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'categorie' => 'required|in:mensuelle,exceptionnelle,evenement', // Alignement avec la migration
            'montant_defaut' => 'nullable|numeric|min:0',
        ]);

        // Génération automatique d'un code unique
        $baseCode = Str::upper(Str::slug($request->libelle, '_'));
        $code = $baseCode;
        $counter = 1;

        while (TypeContribution::where('code', $code)->exists()) {
            $code = $baseCode . '_' . $counter;
            $counter++;
        }

        $validated['code'] = $code;

        TypeContribution::create($validated);

        return back()->with('success', 'Type de contribution créé.');
    }

    /**
     * Met à jour un type de contribution existant.
     */

    public function update(Request $request, TypeContribution $typeContribution): RedirectResponse
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'categorie' => 'required|in:mensuelle,exceptionnelle,evenement',
            'montant_defaut' => 'nullable|numeric|min:0',
        ]);

        // Si le libellé a changé, on met à jour le code en conservant son unicité
        if ($typeContribution->libelle !== $validated['libelle']) {
            $baseCode = Str::upper(Str::slug($validated['libelle'], '_'));
            $code = $baseCode;
            $counter = 1;

            while (TypeContribution::where('code', $code)->where('id', '!=', $typeContribution->id)->exists()) {
                $code = $baseCode . '_' . $counter;
                $counter++;
            }

            $validated['code'] = $code;
        }

        $typeContribution->update($validated);

        return back()->with('success', 'Type de contribution mis à jour.');
    }

    /**
     * Supprime un type de contribution s'il n'est pas utilisé.
     */
    public function destroy(TypeContribution $typeContribution): RedirectResponse
    {
        // Sécurité : Empêche la suppression si des contributions/cotisations lui sont rattachées
        if ($typeContribution->contributions()->exists()) {
            return back()->with('error', 'Impossible de supprimer ce type car des paiements y sont déjà associés.');
        }

        $typeContribution->delete();

        return back()->with('success', 'Type de contribution supprimé avec succès.');
    }
}
