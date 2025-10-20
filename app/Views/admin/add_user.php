<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Utilisateur - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .form-section { margin-bottom: 1em; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <main>
        <h2>Ajouter un nouvel utilisateur</h2>
        <form action="/admin/addUser" method="POST">
            <div class="form-section">
                <label for="user_type">Type d'utilisateur :</label>
                <select id="user_type" name="user_type" onchange="toggleUserType()">
                    <option value="physique" <?php echo (isset($old_data['user_type']) && $old_data['user_type'] === 'physique') ? 'selected' : ''; ?>>Personne Physique</option>
                    <option value="morale" <?php echo (isset($old_data['user_type']) && $old_data['user_type'] === 'morale') ? 'selected' : ''; ?>>Personne Morale</option>
                </select>
            </div>

            <div id="physique_fields" class="form-section hidden">
                <label for="nom_locataire">Nom :</label>
                <input type="text" id="nom_locataire" name="nom_locataire" required value="<?php echo htmlspecialchars($old_data['nom_locataire'] ?? ''); ?>">
                <label for="prenom_locataire">Prénom :</label>
                <input type="text" id="prenom_locataire" name="prenom_locataire" required value="<?php echo htmlspecialchars($old_data['prenom_locataire'] ?? ''); ?>">
                <label for="dateNaissance_locataire">Date de Naissance :</label>
                <input type="date" id="dateNaissance_locataire" name="dateNaissance_locataire" value="<?php echo htmlspecialchars($old_data['dateNaissance_locataire'] ?? ''); ?>">
            </div>

            <div id="morale_fields" class="form-section hidden">
                <label for="RaisonSociale">Raison Sociale :</label>
                <input type="text" id="RaisonSociale" name="RaisonSociale" required value="<?php echo htmlspecialchars($old_data['RaisonSociale'] ?? ''); ?>">
                <label for="Siret">SIRET :</label>
                <input type="text" id="Siret" name="Siret" required value="<?php echo htmlspecialchars($old_data['Siret'] ?? ''); ?>">
            </div>

            <div class="form-section">
                <label for="email_locataire">Email :</label>
                <input type="email" id="email_locataire" name="email_locataire" required value="<?php echo htmlspecialchars($old_data['email_locataire'] ?? ''); ?>">

                <label for="password_locataire">Mot de passe :</label>
                <input type="password" id="password_locataire" name="password_locataire" required>

                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <label for="tel_locataire">Téléphone :</label>
                <input type="tel" id="tel_locataire" name="tel_locataire" value="<?php echo htmlspecialchars($old_data['tel_locataire'] ?? ''); ?>">

                <label for="rue_locataire">Rue :</label>
                <input type="text" id="rue_locataire" name="rue_locataire" value="<?php echo htmlspecialchars($old_data['rue_locataire'] ?? ''); ?>">

                <label for="complement_locataire">Complément d'adresse :</label>
                <input type="text" id="complement_locataire" name="complement_locataire" value="<?php echo htmlspecialchars($old_data['complement_locataire'] ?? ''); ?>">

                <label for="id_commune">Commune :</label>
                <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>">
                <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">

                <h3>Rôles de l'utilisateur :</h3>
                <?php foreach ($roles as $role): ?>
                    <input type="checkbox" id="role_<?php echo htmlspecialchars($role["id_roles"]); ?>" name="roles[]" value="<?php echo htmlspecialchars($role["id_roles"]); ?>">
                    <label for="role_<?php echo htmlspecialchars($role["id_roles"]); ?>"><?php echo htmlspecialchars($role["nom_roles"]); ?></label><br>
                <?php endforeach; ?>
            </div>

            <button type="submit">Ajouter l'utilisateur</button>
        </form>
    </main>

    <script>
        function toggleUserType() {
            const userType = document.getElementById('user_type').value;
            const physiqueFields = document.getElementById('physique_fields');
            const moraleFields = document.getElementById('morale_fields');

            if (userType === 'physique') {
                physiqueFields.classList.remove('hidden');
                moraleFields.classList.add('hidden');
                // Activer le champ physique, désactiver les champs moraux
                document.getElementById('dateNaissance_locataire').required = true;
                document.getElementById('RaisonSociale').required = false;
                document.getElementById('Siret').required = false;
            } else {
                physiqueFields.classList.add('hidden');
                moraleFields.classList.remove('hidden');
                // Activer les champs moraux, désactiver le champ physique
                document.getElementById('dateNaissance_locataire').required = false;
                document.getElementById('RaisonSociale').required = true;
                document.getElementById('Siret').required = true;
            }
        }

        // Appeler la fonction au chargement pour initialiser l'état
        window.onload = toggleUserType;
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
</body>
</html>