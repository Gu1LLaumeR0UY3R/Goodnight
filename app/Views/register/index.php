<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/auth.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="/css/night-background.css">
    <link rel="stylesheet" href="/lib/intl-tel-input/intlTelInput.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/heroicons@2.0.18/outline/index.js"></script>

</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="auth-wrapper">
        <section class="auth-card">
            <h2>Créez votre compte</h2>
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
                unset($_SESSION['error']);
            }
            ?>

            <form action="/register/process" method="POST" class="auth-form register-form">
                <div class="choices-container">
                    <!-- Slider Type de Compte -->
                    <div class="choice-wrapper">
                        <label class="choice-label">Type de compte :</label>
                        <div class="toggle-switch">
                            <input type="radio" name="type_personne" value="physique" id="physique" <?php echo (!isset($old_data['type_personne']) || $old_data['type_personne'] === 'physique') ? 'checked' : ''; ?> onchange="toggleUserType()">
                            <input type="radio" name="type_personne" value="morale" id="morale" <?php echo (isset($old_data['type_personne']) && $old_data['type_personne'] === 'morale') ? 'checked' : ''; ?> onchange="toggleUserType()">
                            <label for="physique" class="toggle-label physique-label">Particulier</label>
                            <label for="morale" class="toggle-label morale-label">Entreprise</label>
                            <span class="toggle-slider"></span>
                        </div>
                    </div>

                    <!-- Choix Propriétaire/Locataire -->
                    <div class="choice-wrapper">
                        <label class="choice-label">Je m'inscris en tant que :</label>
                        <div class="radio-buttons-group">
                            <input type="radio" name="role_choice" value="locataire" id="locataire" checked>
                            <label for="locataire" class="btn-radio">Locataire</label>
                            <input type="radio" name="role_choice" value="proprietaire" id="proprietaire">
                            <label for="proprietaire" class="btn-radio">Propriétaire</label>
                        </div>
                    </div>
                </div>

                <div class="form-columns">
                    <div class="form-column">
                        <div class="form-section">
                            <label for="tel">Téléphone :</label>
                            <input type="tel" id="tel" name="tel_locataire" value="<?php echo htmlspecialchars($old_data['tel_locataire'] ?? ''); ?>" maxlength="20">
                            <input type="hidden" id="full_tel" name="tel_locataire_formatted">
                        </div>
                    </div>
                    <div class="form-column">
                        <div id="form-physique" class="form-section hidden">
                            <label for="date_naissance">Date de naissance :</label>
                            <input type="date" id="date_naissance" name="date_naissance" value="<?php echo htmlspecialchars($old_data['date_naissance'] ?? ''); ?>">
                        </div>
                        <div id="form-morale-raison" class="form-section hidden">
                            <label for="raison_sociale">Raison Sociale :</label>
                            <input type="text" id="raison_sociale" name="raison_sociale" value="<?php echo htmlspecialchars($old_data['raison_sociale'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div id="form-morale-siret" class="form-section full-width hidden">
                    <label for="siret">SIRET :</label>
                    <input type="text" id="siret" name="siret" value="<?php echo htmlspecialchars($old_data['siret'] ?? ''); ?>" maxlength="14">
                </div>

                <div class="form-columns">
                    <!-- COLONNE GAUCHE -->
                    <div class="form-column">
                        <div class="form-section">
                            <label for="nom">Nom :</label>
                            <input type="text" id="nom" name="nom" required value="<?php echo htmlspecialchars($old_data['nom'] ?? ''); ?>">
                        </div>

                        <div class="form-section">
                            <label for="prenom">Prénom :</label>
                            <input type="text" id="prenom" name="prenom" required value="<?php echo htmlspecialchars($old_data['prenom'] ?? ''); ?>">
                        </div>

                        <div class="form-section">
                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($old_data['email'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- COLONNE DROITE -->
                    <div class="form-column">
                        <div class="form-section">
                            <label for="rue">Rue :</label>
                            <input type="text" id="rue" name="rue" value="<?php echo htmlspecialchars($old_data['rue'] ?? ''); ?>">
                        </div>

                        <div class="form-section">
                            <label for="complement">Complément d'adresse :</label>
                            <input type="text" id="complement" name="complement" value="<?php echo htmlspecialchars($old_data['complement'] ?? ''); ?>">
                        </div>

                        <div class="form-section">
                            <label for="id_commune">Commune :</label>
                            <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>">
                            <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="form-section full-width">
                    <label for="password">Mot de passe :</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required minlength="12">
                        <button type="button" id="togglePassword1" class="toggle-password" aria-label="Afficher le mot de passe"></button>
                    </div>
                    <small class="password-requirements">
                        Minimum 12 caractères, 1 majuscule, 1 minuscule, 1 chiffre, 1 caractère spécial (@$!%*?&)
                    </small>
                    <div id="password-strength" class="password-strength" style="display: none;">
                        <div class="strength-bar">
                            <div class="strength-bar-fill" id="strength-bar-fill"></div>
                        </div>
                        <span id="strength-text"></span>
                    </div>
                </div>

                <div class="form-section full-width">
                    <label for="confirm_password">Confirmer le mot de passe :</label>
                    <div class="password-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" required minlength="12">
                        <button type="button" id="togglePassword2" class="toggle-password" aria-label="Afficher le mot de passe"></button>
                    </div>
                    <small id="confirm-password-feedback" class="password-feedback" style="display: none;"></small>
                </div>

                <button type="submit" class="full-width">S'inscrire</button>
            </form>
        </section>
    </main>

    <footer class="auth-footer">
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/lib/intl-tel-input/intlTelInput.min.js"></script>
    <script>
        function toggleUserType() {
            const physiqueRadio = document.getElementById('physique');
            const physiqueFields = document.getElementById('form-physique');
            const moraleRaisonFields = document.getElementById('form-morale-raison');
            const moraleSiretFields = document.getElementById('form-morale-siret');

            if (physiqueRadio.checked) {
                physiqueFields.classList.remove('hidden');
                moraleRaisonFields.classList.add('hidden');
                moraleSiretFields.classList.add('hidden');
                document.getElementById('date_naissance').required = true;
                document.getElementById('raison_sociale').required = false;
                document.getElementById('siret').required = false;
            } else {
                physiqueFields.classList.add('hidden');
                moraleRaisonFields.classList.remove('hidden');
                moraleSiretFields.classList.remove('hidden');
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
                formatOnDisplay: true,
                autoPlaceholder: 'aggressive',
                nationalMode: false,
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                    // Pour la France, afficher le format sans le 0 initial
                    if (selectedCountryData.iso2 === 'fr') {
                        return '6 12 34 56 78';
                    }
                    return selectedCountryPlaceholder;
                },
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
                    // Pour la France, si l'utilisateur tape sans le 0, on l'ajoute temporairement pour validation
                    const selectedCountry = iti.getSelectedCountryData();
                    let valueToValidate = raw;
                    
                    // Si c'est la France et que le numéro commence par 6 ou 7 (mobile), ajouter le 0
                    if (selectedCountry.iso2 === 'fr' && /^[67]/.test(raw.replace(/\s/g, ''))) {
                        valueToValidate = '0' + raw;
                        input.value = valueToValidate;
                    }
                    
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

            // Format automatique pendant la saisie
            input.addEventListener('input', function() {
                // Laisser intl-tel-input gérer le formatage si utils est chargé
                if (window.intlTelInputUtils) {
                    setTimeout(updatePhoneNumber, 50);
                }
            });

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
                    
                    // Validation du mot de passe
                    const password = document.getElementById('password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;
                    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/;
                    
                    if (!passwordRegex.test(password)) {
                        e.preventDefault();
                        alert('Le mot de passe doit contenir au minimum 12 caractères, 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial (@$!%*?&).');
                        document.getElementById('password').focus();
                        return;
                    }
                    
                    if (password !== confirmPassword) {
                        e.preventDefault();
                        alert('Les mots de passe ne correspondent pas.');
                        document.getElementById('confirm_password').focus();
                        return;
                    }
                });
            }
            
            // Validation en temps réel du mot de passe
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const strengthIndicator = document.getElementById('password-strength');
            const strengthBar = document.getElementById('strength-bar-fill');
            const strengthText = document.getElementById('strength-text');
            
            function checkPasswordStrength(password) {
                if (!password) {
                    return { score: 0, text: '', color: '' };
                }
                
                let score = 0;
                const criteria = {
                    length: password.length >= 12,
                    lowercase: /[a-z]/.test(password),
                    uppercase: /[A-Z]/.test(password),
                    digit: /\d/.test(password),
                    special: /[^A-Za-z\d]/.test(password)
                };
                
                // Calculer le score
                if (criteria.length) score++;
                if (criteria.lowercase) score++;
                if (criteria.uppercase) score++;
                if (criteria.digit) score++;
                if (criteria.special) score++;
                
                let text = '';
                let color = '';
                
                if (score === 5) {
                    text = 'Fort';
                    color = '#28a745';
                } else if (score >= 3) {
                    text = 'Moyen';
                    color = '#ffc107';
                } else {
                    text = 'Faible';
                    color = '#dc3545';
                }
                
                return { score: score * 20, text, color };
            }
            
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    
                    if (password.length > 0) {
                        strengthIndicator.style.display = 'block';
                        const strength = checkPasswordStrength(password);
                        strengthBar.style.width = strength.score + '%';
                        strengthBar.style.backgroundColor = strength.color;
                        strengthText.textContent = strength.text;
                        strengthText.style.color = strength.color;
                    } else {
                        strengthIndicator.style.display = 'none';
                    }
                });
            }
            
            if (confirmPasswordInput) {
                const confirmWrapper = confirmPasswordInput.closest('.password-wrapper');
                const confirmFeedback = document.getElementById('confirm-password-feedback');
                
                confirmPasswordInput.addEventListener('input', function() {
                    const password = passwordInput.value;
                    const confirmPassword = this.value;
                    
                    if (confirmPassword.length > 0) {
                        if (password === confirmPassword) {
                            if (confirmWrapper) {
                                confirmWrapper.style.borderColor = '#28a745';
                                confirmWrapper.style.borderWidth = '2px';
                            }
                            if (confirmFeedback) {
                                confirmFeedback.style.display = 'block';
                                confirmFeedback.textContent = '✓ Les mots de passe correspondent';
                                confirmFeedback.style.color = '#28a745';
                            }
                        } else {
                            if (confirmWrapper) {
                                confirmWrapper.style.borderColor = '#dc3545';
                                confirmWrapper.style.borderWidth = '1.5px';
                            }
                            if (confirmFeedback) {
                                confirmFeedback.style.display = 'block';
                                confirmFeedback.textContent = '✗ Les mots de passe ne correspondent pas';
                                confirmFeedback.style.color = '#dc3545';
                            }
                        }
                    } else {
                        if (confirmWrapper) {
                            confirmWrapper.style.borderColor = '';
                            confirmWrapper.style.borderWidth = '';
                        }
                        if (confirmFeedback) {
                            confirmFeedback.style.display = 'none';
                        }
                    }
                });
            }
            
            // Toggle visibilité du mot de passe
            const toggles = [
                { btn: 'togglePassword1', input: 'password' },
                { btn: 'togglePassword2', input: 'confirm_password' }
            ];
            
            // SVG Heroicon Eye
            const eyeIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
            
            // SVG Heroicon EyeSlash
            const eyeSlashIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
            
            toggles.forEach(({ btn, input }) => {
                const toggle = document.getElementById(btn);
                const inputField = document.getElementById(input);
                
                if (toggle && inputField) {
                    toggle.addEventListener('click', function(e) {
                        e.preventDefault();
                        const isPassword = inputField.type === 'password';
                        inputField.type = isPassword ? 'text' : 'password';
                        
                        // Changer l'icône
                        toggle.innerHTML = isPassword ? eyeSlashIcon : eyeIcon;
                    });
                    
                    // Initialiser l'icône
                    toggle.innerHTML = eyeIcon;
                }
            });
        });
    </script>
</body>
</html>