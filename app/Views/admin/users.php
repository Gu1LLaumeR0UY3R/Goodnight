<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs - Admin</title>
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
        <h2>Gestion des Utilisateurs</h2>
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
                                if ($user["is_moral"]) {
                                    echo htmlspecialchars($user["RaisonSociale"]);
                                } else {
                                    echo htmlspecialchars($user["nom_locataire"] . " " . $user["prenom_locataire"]);
                                }
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($user["email_locataire"]); ?></td>
                        <td><?php echo htmlspecialchars($user["tel_locataire"]); ?></td>
                        <td><?php echo $user["is_moral"] ? "Morale" : "Physique"; ?></td>
                        <td>
                            <?php 
                                $userRoles = $this->userModel->getUserRoles($user["id_locataire"]);
                                echo implode(", ", array_map("htmlspecialchars", $userRoles));
                            ?>
                        </td>
                        <td>
                            <a href="/admin/editUser/<?php echo htmlspecialchars($user["id_locataire"]); ?>">Modifier</a>
                            <a href="/admin/deleteUser/<?php echo htmlspecialchars($user["id_locataire"]); ?>" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cet utilisateur ?\');">Supprimer</a>
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
