<?php

namespace App\Services;

class CsvExporter
{
    public function download($data, $filename)
    {
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Nom', 'Prénom', 'Sexe', 'Date de Naissance', 'Niveau Etude', 'Statut Matrimonial'];

        $callback = function() use ($data, $columns) {
            $file = fopen('php://output', 'w');
            // Ajout du BOM pour qu'Excel lise bien les accents
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($data as $m) {
                fputcsv($file, [
                    $m->nom, 
                    $m->prenom, 
                    $m->sexe, 
                    $m->date_naissance, 
                    $m->niveau_etude, 
                    $m->statut_matrimonial
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}