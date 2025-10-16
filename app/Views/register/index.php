<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .form-section { margin-bottom: 1em; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <header>
        <h1>GlobeNight</h1>
        <nav>
            <ul>
                <li><a href="/home">Accueil</a></li>
                <li><a href="/register">Inscription</a></li>
                <li><a href="/login">Connexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Créez votre compte</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="/register/process" method="POST">
            <div class="form-section">
                <label for="user_type">Type de compte :</label>
                <select id="user_type" name="type_personne" onchange="toggleUserType()">
                    <option value="physique" <?php echo (isset($old_data['type_personne']) && $old_data['type_personne'] === 'physique') ? 'selected' : ''; ?>>Personne Physique</option>
                    <option value="morale" <?php echo (isset($old_data['type_personne']) && $old_data['type_personne'] === 'morale') ? 'selected' : ''; ?>>Personne Morale</option>
                </select>
            </div>

            <div class="form-section">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required value="<?php echo htmlspecialchars($old_data['nom'] ?? ''); ?>">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required value="<?php echo htmlspecialchars($old_data['prenom'] ?? ''); ?>">
            </div>

            <div id="form-physique" class="form-section hidden">
                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($old_data['date_naissance'] ?? ''); ?>">
            </div>

            <div id="form-morale" class="form-section hidden">
                <label for="raison_sociale">Raison Sociale :</label>
                <input type="text" id="raison_sociale" name="raison_sociale" value="<?php echo htmlspecialchars($old_data['raison_sociale'] ?? ''); ?>">
                <label for="siret">SIRET :</label>
                <input type="text" id="siret" name="siret" value="<?php echo htmlspecialchars($old_data['siret'] ?? ''); ?>" maxlength="14">
            </div>

            <div class="form-section">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($old_data['email'] ?? ''); ?>">

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <label for="tel">Téléphone :</label>
                <input type="tel" id="tel" name="tel" value="<?php echo htmlspecialchars($old_data['tel'] ?? ''); ?>">

                <label for="rue">Rue :</label>
                <input type="text" id="rue" name="rue" value="<?php echo htmlspecialchars($old_data['rue'] ?? ''); ?>">

                <label for="complement">Complément d'adresse :</label>
                <input type="text" id="complement" name="complement" value="<?php echo htmlspecialchars($old_data['complement'] ?? ''); ?>">

                <label for="id_commune">Commune :</label>
                <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>">
                <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">
            </div>

            <div class="role-choice-buttons form-section">
                <label>Je souhaite m'inscrire en tant que :</label>
                <input type="radio" name="role_choice" value="proprietaire" id="proprietaire" checked>
                <label for="proprietaire" class="btn-radio">Propriétaire</label>
                <input type="radio" name="role_choice" value="locataire" id="locataire">
                <label for="locataire" class="btn-radio">Locataire</label>
            </div>

            <button type="submit">S'inscrire</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script>
        function toggleUserType() {
            const userType = document.getElementById('user_type').value;
            const physiqueFields = document.getElementById('form-physique');
            const moraleFields = document.getElementById('form-morale');

            if (userType === 'physique') {
                physiqueFields.classList.remove('hidden');
                moraleFields.classList.add('hidden');
                // Activer le champ physique, désactiver les champs moraux
                document.getElementById('date_naissance').required = true;
                document.getElementById('raison_sociale').required = false;
                document.getElementById('siret').required = false;
            } else {
                physiqueFields.classList.add('hidden');
                moraleFields.classList.remove('hidden');
                // Activer les champs moraux, désactiver le champ physique
                document.getElementById('date_naissance').required = false;
                document.getElementById('raison_sociale').required = true;
                document.getElementById('siret').required = true;
            }
        }

        // Appeler la fonction au chargement pour initialiser l'état
        window.onload = toggleUserType;
    </script>
</body>
</html>