<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rôles - Admin</title>
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
        <h2>Gestion des Rôles</h2>
        <a href="/admin/addRole">Ajouter un nouveau rôle</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($roles as $role): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($role["id_roles"]); ?></td>
                        <td><?php echo htmlspecialchars($role["nom_roles"]); ?></td>
                        <td>
                            <a href="/admin/editRole/<?php echo htmlspecialchars($role["id_roles"]); ?>">Modifier</a>
                            <a href="/admin/deleteRole/<?php echo htmlspecialchars($role["id_roles"]); ?>" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce rôle ?\');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
