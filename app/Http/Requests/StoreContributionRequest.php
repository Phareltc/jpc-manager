<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContributionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'membre_id'         => 'required|exists:membres,id',
            'type_contribution' => 'required|string|max:255',
            'montant'           => 'required|numeric|min:0',
            'date_paiement'     => 'required|date',
            'annee_concernee'   => 'required|string|max:10',
            'mois_concerne'     => 'nullable|string|max:50',
            'mode_paiement'     => 'required|in:Espèces,Mobile Money,Virement,Chèque',
            'observation'       => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'membre_id.required'         => 'Le membre est obligatoire.',
            'membre_id.exists'           => 'Le membre sélectionné n\'existe pas.',
            'type_contribution.required' => 'Le type de contribution est requis.',
            'montant.required'            => 'Le montant est obligatoire.',
            'montant.numeric'            => 'Le montant doit être un chiffre valide.',
            'montant.min'                => 'Le montant ne peut pas être négatif.',
            'date_paiement.required'     => 'La date de paiement est requise.',
            'annee_concernee.required'   => 'L\'année concernée est requise.',
            'mode_paiement.required'     => 'Le mode de paiement est requis.',
            'mode_paiement.in'           => 'Le mode de paiement sélectionné n\'est pas valide.',
        ];
    }
}