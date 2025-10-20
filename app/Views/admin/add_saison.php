<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une saison - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>

    <main>
        <h2>Ajouter une nouvelle Saison</h2>
        <form action="/admin/addSaison" method="POST">
            <label for="lib_saison">Libelle de la Saison :</label>
            <input type="text" id="lib_saison" name="lib_saison" required>
            <button type="submit">Ajouter la Saison</button>
        </form>

</body>
</html>
