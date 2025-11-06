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
	        /* Correction de positionnement pour intl-tel-input */
	        .iti {
	            width: 100% !important; /* Assure que le conteneur intl-tel-input prend toute la largeur disponible */
	            display: flex !important; /* Utiliser flex pour forcer l'alignement */
	        }
	        .iti .iti__country-container {
	            flex-shrink: 0 !important; /* Empêche le sélecteur de rétrécir */
	        }
	        .iti input.iti__tel-input {
	            flex-grow: 1 !important; /* Permet au champ de saisie de prendre l'espace restant */
	            padding-right: 0 !important; /* Corrige le padding si nécessaire */
	        }
            .tel-error {
                color: #dc3545;
                font-size: 0.875rem;
                margin-top: 0.25rem;
                margin-bottom: 0.5rem;
            }
            input.error {
                border-color: #dc3545;
            }
            input.error:focus {
                box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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
                <input type="tel" id="tel" name="tel_locataire" value="<?php echo htmlspecialchars($old_data['tel_locataire'] ?? ''); ?>" maxlength="15">
                <input type="hidden" id="full_tel" name="tel_locataire_formatted">

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
                document.getElementById('date_naissance').required = true;
                document.getElementById('raison_sociale').required = false;
                document.getElementById('siret').required = false;
            } else {
                physiqueFields.classList.add('hidden');
                moraleFields.classList.remove('hidden');
                document.getElementById('date_naissance').required = false;
                document.getElementById('raison_sociale').required = true;
                document.getElementById('siret').required = true;
            }
        }

        // Afficher un message d'erreur sous le champ de téléphone
        function showError(input, message) {
            let errorMsg = input.parentNode.querySelector('.tel-error');
            if (!errorMsg) {
                errorMsg = document.createElement('p');
                errorMsg.className = 'tel-error error';
                input.parentNode.insertBefore(errorMsg, input.nextSibling);
            }
            errorMsg.textContent = message;
        }

        // Supprimer le message d'erreur
        function removeError(input) {
            const errorMsg = input.parentNode.querySelector('.tel-error');
            if (errorMsg) {
                errorMsg.remove();
            }
        }

        // Obtenir le message d'erreur approprié
        function getErrorMessage(errorCode) {
            switch(errorCode) {
                case intlTelInputUtils.validationError.INVALID_COUNTRY_CODE:
                    return "Code pays invalide";
                case intlTelInputUtils.validationError.TOO_SHORT:
                    return "Numéro trop court";
                case intlTelInputUtils.validationError.TOO_LONG:
                    return "Numéro trop long";
                case intlTelInputUtils.validationError.NOT_A_NUMBER:
                    return "Numéro invalide";
                default:
                    return "Numéro de téléphone invalide";
            }
        }

        window.onload = function() {
            toggleUserType();

            const input = document.querySelector("#tel");
            const fullTelInput = document.querySelector("#full_tel");
            const iti = window.intlTelInput(input, {
                allowDropdown: true,
                autoInsertDialCode: true,
                dropdownContainer: document.body,
                formatOnDisplay: true,
                initialCountry: "fr",
                nationalMode: false,
                preferredCountries: ["fr", "be", "ch", "lu"],
                separateDialCode: true,
                showFlags: true,
                utilsScript: "/lib/intl-tel-input/utils.js",
                onlyCountries: [
                    "fr", "be", "ch", "lu", "de", "es", "it", "gb", "pt", 
                    "nl", "at", "dk", "ie", "gr", "pl", "se", "no", "fi", 
                    "cz", "hu", "ro", "bg", "hr", "si", "sk", "ee", "lv", 
                    "lt", "cy", "mt"
                ],
                localizedCountries: {
                    'fr': 'France',
                    'be': 'Belgique',
                    'ch': 'Suisse',
                    'lu': 'Luxembourg',
                    'de': 'Allemagne',
                    'es': 'Espagne',
                    'it': 'Italie',
                    'gb': 'Royaume-Uni',
                    'pt': 'Portugal',
                    'nl': 'Pays-Bas',
                    'at': 'Autriche',
                    'dk': 'Danemark',
                    'ie': 'Irlande',
                    'gr': 'Grèce',
                    'pl': 'Pologne',
                    'se': 'Suède',
                    'no': 'Norvège',
                    'fi': 'Finlande',
                    'cz': 'République Tchèque',
                    'hu': 'Hongrie',
                    'ro': 'Roumanie',
                    'bg': 'Bulgarie',
                    'hr': 'Croatie',
                    'si': 'Slovénie',
                    'sk': 'Slovaquie',
                    'ee': 'Estonie',
                    'lv': 'Lettonie',
                    'lt': 'Lituanie',
                    'cy': 'Chypre',
                    'mt': 'Malte'
                }
            });

            // Fonction de validation et mise à jour du numéro
            function updatePhoneNumber() {
                if (input.value.trim()) {
                    if (iti.isValidNumber()) {
                        const number = iti.getNumber(intlTelInputUtils.numberFormat.E164);
                        fullTelInput.value = number;
                        input.classList.remove('error');
                        input.setCustomValidity('');
                        removeError(input);
                    } else {
                        const errorCode = iti.getValidationError();
                        const errorMsg = getErrorMessage(errorCode);
                        input.classList.add('error');
                        input.setCustomValidity(errorMsg);
                        showError(input, errorMsg);
                        fullTelInput.value = '';
                    }
                } else {
                    input.classList.remove('error');
                    input.setCustomValidity('');
                    removeError(input);
                    fullTelInput.value = '';
                }
            }

            // Écouteurs d'événements
            input.addEventListener('blur', updatePhoneNumber);
            input.addEventListener('change', updatePhoneNumber);
            input.addEventListener('keyup', updatePhoneNumber);
            iti.promise.then(function() {
                input.addEventListener('countrychange', updatePhoneNumber);
                updatePhoneNumber();
            });

            // Validation du formulaire
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                updatePhoneNumber();
                if (!iti.isValidNumber() && input.value.trim()) {
                    e.preventDefault();
                    input.focus();
                }
            });
        };
    </script>
</body>
</html>