<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Types de Biens - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

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
</body>
</html>
