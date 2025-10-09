<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Types de Biens - Admin</title>
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
        <h2>Gestion des Types de Biens</h2>
        <a href="/admin/addTypeBien">Ajouter un nouveau type de bien</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Désignation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($typesBiens as $typeBien): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($typeBien["id_typebien"]); ?></td>
                        <td><?php echo htmlspecialchars($typeBien["desc_type_bien"]); ?></td>
                        <td>
                            <a href="/admin/editTypeBien/<?php echo htmlspecialchars($typeBien["id_typebien"]); ?>">Modifier</a>
                            <a href="/admin/deleteTypeBien/<?php echo htmlspecialchars($typeBien["id_typebien"]); ?>" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce type de bien ?\');">Supprimer</a>
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
