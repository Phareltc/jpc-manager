<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Membres JPC</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2563eb; color: white; text-transform: uppercase; }
        h2 { text-align: center; color: #333; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <h2>LISTE GÉNÉRALE DES MEMBRES - JPC</h2>
    
    <table>
        <thead>
            <tr>
                <th>Nom & Prénom</th>
                <th>Sexe</th>
                <th>Niveau d'étude</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($membres as $m)
            <tr>
                <td>{{ $m->nom }} {{ $m->prenom }}</td>
                <td>{{ $m->sexe }}</td>
                <td>{{ $m->niveau_etude }}</td>
                <td>{{ $m->statut_matrimonial }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Imprimé le {{ date('d/m/Y') }}
    </div>
</body>
</html>