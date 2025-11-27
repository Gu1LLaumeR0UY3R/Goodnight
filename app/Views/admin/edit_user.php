<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/lib/intl-tel-input/intlTelInput.min.css">
    <style>
        /* Correction de positionnement pour intl-tel-input */
        .iti {
            width: 100% !important;
            display: flex !important;
        }
        .iti .iti__country-container {
            flex-shrink: 0 !important;
        }
        .iti input.iti__tel-input {
            flex-grow: 1 !important;
            padding-right: 0 !important;
        }
        .iti__country-list, .iti__flag-list, .iti__country {
            z-index: 200000 !important;
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

    <main>
        <h2>Modifier l'utilisateur : <?php echo htmlspecialchars($user["email_locataire"]); ?></h2>
        <form action="/admin/editUser/<?php echo htmlspecialchars($user["id_locataire"]); ?>" method="POST">
            <?php if (empty($user["Siret"]) || empty($user["RaisonSociale"])): ?>
                <label for="nom_locataire">Nom :</label>
                <input type="text" id="nom_locataire" name="nom_locataire" value="<?php echo htmlspecialchars($user["nom_locataire"] ?? ''); ?>">

                <label for="prenom_locataire">Prénom :</label>
                <input type="text" id="prenom_locataire" name="prenom_locataire" value="<?php echo htmlspecialchars($user["prenom_locataire"] ?? ''); ?>">
            <?php else: ?>
                <label for="RaisonSociale">Raison Sociale :</label>
                <input type="text" id="RaisonSociale" name="RaisonSociale" value="<?php echo htmlspecialchars($user["RaisonSociale"] ?? ''); ?>">

                <label for="Siret">SIRET :</label>
                <input type="text" id="Siret" name="Siret" value="<?php echo htmlspecialchars($user["Siret"] ?? ''); ?>">
            <?php endif; ?>

            <label for="email_locataire">Email :</label>
            <input type="email" id="email_locataire" name="email_locataire" value="<?php echo htmlspecialchars($user["email_locataire"]); ?>" required>

            <label for="tel_locataire">Téléphone :</label>
            <input type="tel" id="tel_locataire" name="tel_locataire" value="<?php echo htmlspecialchars($user["tel_locataire"] ?? ''); ?>" maxlength="20">
            <input type="hidden" id="full_tel_locataire" name="full_tel_locataire" value="<?php echo htmlspecialchars($user["tel_locataire"] ?? ''); ?>">

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
            <?php foreach ($roles as $role): ?>
                <input type="checkbox" id="role_<?php echo htmlspecialchars($role["id_roles"]); ?>" name="roles[]" value="<?php echo htmlspecialchars($role["id_roles"]); ?>"
                    <?php echo in_array($role["id_roles"], $userRoleIds) ? "checked" : ""; ?>>
                <label for="role_<?php echo htmlspecialchars($role["id_roles"]); ?>"><?php echo htmlspecialchars($role["nom_roles"]); ?></label><br>
            <?php endforeach; ?>

            <button type="submit">Mettre à jour l'utilisateur</button>
        </form>

        <button onclick="window.location.href='/admin/users'">Retour</button>
        
    </main>

    <script src="/lib/intl-tel-input/intlTelInput.min.js"></script>
    <script>
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
            const input = document.querySelector('#tel_locataire');
            const fullTelInput = document.querySelector('#full_tel_locataire');
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

            // Si un numéro existe déjà, le définir
            if (input.value) {
                try {
                    iti.setNumber(input.value);
                } catch (e) {
                    console.warn('Error setting number', e);
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