<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Saison - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>

    <main>
        <h2>Modifier la Saison</h2>
        <form action="/admin/editSaison/<?php echo htmlspecialchars($saison["id_saison"]); ?>" method="POST">
            <label for="lib_saison">Libelle de la Saison :</label>
            <input type="text" id="lib_saison" name="lib_saison" value="<?php echo htmlspecialchars($saison["lib_saison"]); ?>" required>
            <button type="submit">Mettre à jour la Saison</button>
        </form>
    </main>

</body>
</html>
