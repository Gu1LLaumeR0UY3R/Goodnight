<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/lib/intl-tel-input/intlTelInput.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        .form-section { margin-bottom: 1em; }
        .hidden { display: none; }
            /* Style pour centrer le sélecteur de drapeau */
            .iti__selected-flag {
                padding: 0 6px 0 8px;
                display: flex;
                align-items: center;
                height: 100%;
            }
            .iti__flag-container {
                height: 100%;
            }
            input[type="tel"] {
                padding-left: 90px !important;
            }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

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
                <!-- inputmode + pattern aident les claviers mobiles et la validation HTML -->
                <input type="tel" id="tel" name="tel" inputmode="tel" pattern="^\+?[0-9\s\-\(\)]*$" title="Veuillez entrer uniquement des chiffres et les caractères + - ( )" maxlength="20" value="<?php echo htmlspecialchars($old_data['tel'] ?? ''); ?>">
                <input type="hidden" id="full_tel" name="full_tel">

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
    <script src="/lib/intl-tel-input/intlTelInput.min.js"></script>
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
        window.onload = function() {
            toggleUserType();
            // Initialisation de intl-tel-input
            const input = document.querySelector("#tel");
            const fullTelInput = document.querySelector("#full_tel");
            const form = document.querySelector('form[action="/register/process"]');
            const iti = window.intlTelInput(input, {
                initialCountry: "fr", // Pays initial par défaut
                separateDialCode: true,
                utilsScript: "" // Le script utils.js n'est pas nécessaire pour la validation de base
            });

            // Empêcher les lettres en temps réel : autoriser 0-9 + espaces - ( )
            input.addEventListener('input', function() {
                const clean = this.value.replace(/[^0-9+\s\-()]/g, '');
                if (this.value !== clean) {
                    this.value = clean;
                }
                // Mettre à jour le champ caché si le numéro est valide
                if (iti.isValidNumber()) {
                    fullTelInput.value = iti.getNumber();
                } else {
                    fullTelInput.value = '';
                }
            });

            // Mettre à jour aussi au blur (quand l'utilisateur quitte le champ)
            input.addEventListener('blur', function() {
                if (iti.isValidNumber()) {
                    fullTelInput.value = iti.getNumber();
                } else {
                    fullTelInput.value = '';
                }
            });

            // Validation finale à la soumission du formulaire
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!iti.isValidNumber()) {
                        e.preventDefault();
                        // Afficher une erreur simple sous le champ
                        let existing = document.getElementById('tel-error');
                        if (!existing) {
                            existing = document.createElement('p');
                            existing.id = 'tel-error';
                            existing.className = 'error';
                            existing.textContent = 'Veuillez entrer un numéro de téléphone valide.';
                            input.parentNode.insertBefore(existing, input.nextSibling);
                        }
                        input.focus();
                        return false;
                    }
                    // Assignation finale du numéro complet (E.164)
                    fullTelInput.value = iti.getNumber();
                });
            }
        };
    </script>
</body>
</html>