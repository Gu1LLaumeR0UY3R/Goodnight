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
            /* Ensure the country dropdown is above other elements */
            .iti__country-list, .iti__flag-list, .iti__country { z-index: 200000 !important; }
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
                <input type="tel" id="tel" name="tel_locataire" value="<?php echo htmlspecialchars($old_data['tel_locataire'] ?? ''); ?>" maxlength="20">
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

        function removeError(input) {
            const errorMsg = input.parentNode.querySelector('.tel-error');
            if (errorMsg) errorMsg.remove();
        }

        // Map intl-tel-input validation error codes to user-friendly messages
        function getErrorMessage(errorCode) {
            if (!window.intlTelInputUtils || !window.intlTelInputUtils.validationError) return 'Numéro de téléphone invalide';
            const v = window.intlTelInputUtils.validationError;
            switch (errorCode) {
                case v.INVALID_COUNTRY_CODE:
                    return 'Code pays invalide';
                case v.TOO_SHORT:
                    return 'Numéro trop court';
                case v.TOO_LONG:
                    return 'Numéro trop long';
                case v.NOT_A_NUMBER:
                    return 'Ce n\'est pas un numéro de téléphone';
                default:
                    return 'Numéro de téléphone invalide';
            }
        }

        // Utility: sanitize input but allow international prefixes (+) and common separators
        // We intentionally DO NOT strip the leading + because intl-tel-input needs it
        function attachDigitsOnlyBehavior(el) {
            if (!el) return;
            const max = parseInt(el.getAttribute('maxlength') || '0', 10) || null;

            // sanitize on input: keep digits, plus sign, spaces, parentheses and dashes
            el.addEventListener('input', function() {
                let v = this.value || '';
                // remove any characters except digits and +, space, (), -
                const cleaned = v.replace(/[^0-9+\s()\-]/g, '');
                this.value = (max ? cleaned.slice(0, max) : cleaned);
            });

            // allow navigation and numeric characters + plus and separators
            el.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.metaKey || e.altKey) return; // allow shortcuts
                const allowed = ['Backspace','Tab','ArrowLeft','ArrowRight','Delete','Home','End','Enter'];
                if (allowed.includes(e.key)) return;
                // allow digits, +, space, parentheses and dash
                if (!/^[0-9+\s()\-]$/.test(e.key)) {
                    e.preventDefault();
                }
            });
        }

        // Initialize intl-tel-input and wire up sanitization + validation
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize user type on page load
            toggleUserType();
            
            const input = document.querySelector('#tel');
            const fullTelInput = document.querySelector('#full_tel');
            if (!input) return;

            attachDigitsOnlyBehavior(input);

            // init iti
            const iti = window.intlTelInput(input, {
                initialCountry: 'fr',
                separateDialCode: true,
                // load utils so that isValidNumber, getNumber, getValidationError work
                utilsScript: '/lib/intl-tel-input/utils.js'
            });

            function updatePhoneNumber() {
                const raw = input.value.trim();
                if (!raw) {
                    input.classList.remove('error');
                    input.setCustomValidity('');
                    removeError(input);
                    if (fullTelInput) fullTelInput.value = '';
                    return;
                }

                try {
                    if (typeof iti.isValidNumber === 'function' && iti.isValidNumber()) {
                        const number = (window.intlTelInputUtils && window.intlTelInputUtils.numberFormat)
                            ? iti.getNumber(window.intlTelInputUtils.numberFormat.E164) || ''
                            : iti.getNumber() || '';
                        if (fullTelInput) fullTelInput.value = number;
                        input.classList.remove('error');
                        input.setCustomValidity('');
                        removeError(input);
                        return;
                    }

                    let errorCode = null;
                    if (typeof iti.getValidationError === 'function') {
                        errorCode = iti.getValidationError();
                    }
                    const errorMsg = (typeof errorCode !== 'number') ? 'Numéro de téléphone invalide' : getErrorMessage(errorCode);
                    input.classList.add('error');
                    input.setCustomValidity(errorMsg);
                    showError(input, errorMsg);
                    if (fullTelInput) fullTelInput.value = '';
                } catch (err) {
                    console.warn('Phone validation error', err);
                    input.classList.add('error');
                    input.setCustomValidity('Numéro de téléphone invalide');
                    showError(input, 'Numéro de téléphone invalide');
                    if (fullTelInput) fullTelInput.value = '';
                }
            }

            input.addEventListener('blur', updatePhoneNumber);
            input.addEventListener('change', updatePhoneNumber);
            input.addEventListener('keyup', updatePhoneNumber);

            if (iti && iti.promise && typeof iti.promise.then === 'function') {
                iti.promise.then(function() {
                    input.addEventListener('countrychange', updatePhoneNumber);
                    updatePhoneNumber();
                });
            } else {
                input.addEventListener('countrychange', updatePhoneNumber);
                setTimeout(updatePhoneNumber, 200);
            }

            // form submit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    updatePhoneNumber();
                    if (typeof iti.isValidNumber === 'function' && !iti.isValidNumber() && input.value.trim()) {
                        e.preventDefault();
                        input.focus();
                    }
                });
            }
        });
    </script>
</body>
</html>