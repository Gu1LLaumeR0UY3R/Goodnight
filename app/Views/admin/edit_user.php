<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur - Admin</title>
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
        <h2>Modifier l'utilisateur : <?php echo htmlspecialchars($user["email_locataire"]); ?></h2>
        <form action="/admin/editUser/<?php echo htmlspecialchars($user["id_locataire"]); ?>" method="POST">
            <?php if (!$user["is_moral"]): ?>
                <label for="nom_locataire">Nom :</label>
                <input type="text" id="nom_locataire" name="nom_locataire" value="<?php echo htmlspecialchars($user["nom_locataire"]); ?>">

                <label for="prenom_locataire">Prénom :</label>
                <input type="text" id="prenom_locataire" name="prenom_locataire" value="<?php echo htmlspecialchars($user["prenom_locataire"]); ?>">
            <?php else: ?>
                <label for="RaisonSociale">Raison Sociale :</label>
                <input type="text" id="RaisonSociale" name="RaisonSociale" value="<?php echo htmlspecialchars($user["RaisonSociale"]); ?>">

                <label for="Siret">SIRET :</label>
                <input type="text" id="Siret" name="Siret" value="<?php echo htmlspecialchars($user["Siret"]); ?>">
            <?php endif; ?>

            <label for="email_locataire">Email :</label>
            <input type="email" id="email_locataire" name="email_locataire" value="<?php echo htmlspecialchars($user["email_locataire"]); ?>" required>

            <label for="tel_locataire">Téléphone :</label>
            <input type="tel" id="tel_locataire" name="tel_locataire" value="<?php echo htmlspecialchars($user["tel_locataire"]); ?>">

            <label for="id_commune">Commune :</label>
            <select id="id_commune" name="id_commune">
                <option value="">Sélectionner une commune</option>
                <?php foreach ($communes as $commune): ?>
                    <option value="<?php echo htmlspecialchars($commune["id_commune"]); ?>" <?php echo ($user["id_commune"] == $commune["id_commune"]) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($commune["ville_nom"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h3>Rôles de l'utilisateur :</h3>
            <?php foreach ($allRoles as $role): ?>
                <input type="checkbox" id="role_<?php echo htmlspecialchars($role["id_roles"]); ?>" name="roles[]" value="<?php echo htmlspecialchars($role["id_roles"]); ?>"
                    <?php echo in_array($role["nom_roles"], $userRoles) ? "checked" : ""; ?>>
                <label for="role_<?php echo htmlspecialchars($role["id_roles"]); ?>"><?php echo htmlspecialchars($role["nom_roles"]); ?></label><br>
            <?php endforeach; ?>

            <button type="submit">Mettre à jour l'utilisateur</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
