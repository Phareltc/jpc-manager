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
    public function store(StoreScolariteRequest $request)
    {
        // Cette ligne stoppe l'exécution et permet de voir les données
        //dd($request->all());

        Scolarite::create($request->validated());
        return back();
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
    public function update(StoreScolariteRequest $request, Scolarite $scolarite)
    {
        $scolarite->update($request->validated());
        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scolarite $scolarite)
    {
        $scolarite->delete();
        return back();
    }
}
