<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une saison - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/admin-content.css">
</head>
<body>

    <main>
        <h2>Ajouter une nouvelle Saison</h2>
        <form action="/admin/addSaison" method="POST">
            <label for="lib_saison">Libelle de la Saison :</label>
            <input type="text" id="lib_saison" name="lib_saison" required>
            
            <label for="date_debut">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" required>
            
            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" required>
            
            <button type="submit">Ajouter la Saison</button>
        </form>

        <button onclick="window.location.href='/admin/saisons'">Retour</button>

    </main>

</body>
</html>
