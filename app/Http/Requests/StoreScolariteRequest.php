<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreScolariteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ne pas oublier de mettre à true pour autoriser l'envoi
    }

    public function rules(): array
    {
        return [
            'membre_id' => 'required|exists:membres,id',
            'annee_scolaire' => 'required|string|max:20', // ex: 2025-2026
            'etablissement' => 'required|string|max:255',
            'classe' => 'required|string|max:100',
            'serie' => 'nullable|string|max:50',
            'decision_finale' => 'nullable|string|max:100',
        ];
    }
}
