<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <main>
        <h2>Gestion des Utilisateurs</h2>
        <a href="/admin/addUser" class="button">Ajouter un nouvel utilisateur</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom/Raison Sociale</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Type</th>
                    <th>Rôles</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user["id_locataire"]); ?></td>
                        <td>
                            <?php 
                                if (!empty($user["Siret"]) && !empty($user["RaisonSociale"])) {
                                    echo htmlspecialchars($user["RaisonSociale"]);
                                } else {
                                    echo htmlspecialchars($user["nom_locataire"] . " " . $user["prenom_locataire"]);
                                }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($user["email_locataire"]); ?></td>
                        <td><?php echo htmlspecialchars($user["tel_locataire"] ?? ''); ?></td>
                        <td>
                            <?php 
                                echo (!empty($user["Siret"]) && !empty($user["RaisonSociale"])) ? "Morale" : "Physique"; 
                            ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($user["roles"] ?? 'Aucun rôle'); ?>
                        </td>
                        <td>
                            <a href="/admin/editUser/<?php echo htmlspecialchars($user["id_locataire"]); ?>">Modifier</a>
                            <a href="/admin/deleteUser/<?php echo htmlspecialchars($user["id_locataire"]); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>