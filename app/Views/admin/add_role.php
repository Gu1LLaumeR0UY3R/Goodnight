<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Rôle - Admin</title>
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
        <h2>Ajouter un nouveau Rôle</h2>
        <form action="/admin/addRole" method="POST">
            <label for="nom_roles">Nom du rôle :</label>
            <input type="text" id="nom_roles" name="nom_roles" required>
            <button type="submit">Ajouter le rôle</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
