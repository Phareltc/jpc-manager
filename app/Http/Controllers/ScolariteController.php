<?php

namespace App\Http\Controllers;

use App\Models\Scolarite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScolariteRequest;
use Illuminate\Http\RedirectResponse;

class ScolariteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Enregistre une nouvelle scolarité pour un membre.
     */
    public function store(StoreScolariteRequest $request): RedirectResponse
    {
        // Les données sont déjà validées ici grâce à StoreScolariteRequest
        Scolarite::create($request->validated());

        // On redirige vers la page précédente avec un message de succès
        // Inertia interceptera cela pour mettre à jour les données sans recharger la page
        return back()->with('message', 'Scolarité ajoutée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scolarite $scolarite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scolarite $scolarite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scolarite $scolarite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scolarite $scolarite)
    {
        //
    }
}
