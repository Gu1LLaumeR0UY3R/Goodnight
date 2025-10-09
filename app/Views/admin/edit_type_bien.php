<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Type de Bien - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>Administration GlobeNight</h1>
        <nav>
            <ul>
                <li><a href="/admin">Tableau de bord</a></li>
                <li><a href="/admin/users">Utilisateurs</a></li>
                <li><a href="/admin/roles">Rôles</a></li>
                <li><a href="/admin/communes">Communes</a></li>
                <li><a href="/admin/typesBiens">Types de biens</a></li>
                <li><a href="/logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Modifier le Type de Bien</h2>
        <form action="/admin/editTypeBien/<?php echo htmlspecialchars($typeBien["id_typebien"]); ?>" method="POST">
            <label for="designation_bien">Désignation du type de bien :</label>
            <input type="text" id="designation_bien" name="desc_type_bien" value="<?php echo htmlspecialchars($typeBien["desc_type_bien"]); ?>" required>
            <button type="submit">Mettre à jour le type de bien</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
