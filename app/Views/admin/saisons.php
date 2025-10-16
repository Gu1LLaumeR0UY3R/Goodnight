<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Saisons - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <main>
        <h2>Gestion des Saisons</h2>
        <a href="/admin/addSaison">Ajouter une nouvelle saison</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Désignation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($saisons as $saison): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($saison["id_saison"]); ?></td>
                        <td><?php echo htmlspecialchars($saison["lib_saison"]); ?></td>
                        <td>
                            <a href="/admin/editSaison/<?php echo htmlspecialchars($saison["id_saison"]); ?>">Modifier</a>
                            <a href="/admin/deleteSaison/<?php echo htmlspecialchars($saison["id_saison"]); ?>" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer ce type de bien ?\');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
