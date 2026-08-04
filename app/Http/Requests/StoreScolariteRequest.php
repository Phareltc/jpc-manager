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
            'annee_academique' => 'required|string|max:20', // Harmonisé avec la migration
            'etablissement'    => 'required|string|max:255',
            'niveau_etude'     => 'required|string|max:100', // Harmonisé avec la migration
            'resultat'         => 'required|in:Admis,Échec,En cours',
        ];
    }
}
