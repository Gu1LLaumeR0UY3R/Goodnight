<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Biens - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <main>
        <h2>Gestion des Biens</h2>
        <a href="/admin/addBien">Ajouter un nouveau bien</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du bien</th>
                    <th>Propriétaire</th>
                    <th>Rue</th>
                    <th>Complément</th>
                    <th>Superficie</th>
                    <th>Description</th>
                    <th>Animaux autorisés</th>
                    <th>Couchage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($biens as $bien): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bien["id_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["designation_bien"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["proprietaire"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["rue_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["complement_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["superficie_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["description_biens"]); ?></td>
                        <td><?php echo $bien["animaux_biens"] ? "Oui" : "Non"; ?></td>
                        <td><?php echo htmlspecialchars($bien["nb_couchage"]); ?></td>
                        <td>
                            <a href="/admin/editBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>">Modifier</a>
                            <a href="/admin/deleteBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bien ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
