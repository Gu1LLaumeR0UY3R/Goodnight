<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>


    <main class="reset-password-container">
        <div class="form-card">
            <?php
            if (isset($_SESSION['error'])) {
                echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<p class="success">' . htmlspecialchars($_SESSION['success']) . '</p>';
                unset($_SESSION['success']);
            }

            // Vérification de l'email dans la base de données
            $email = $_POST['email'] ?? '';
            $userModel = new UserModel();
            $emailVerified = !empty($email) && $userModel->emailExists($email);
            
            if (!$emailVerified) :
            ?>
                <h2>Réinitialiser votre mot de passe</h2>
                <p class="info-text">Entrez votre adresse email. Nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
                <form action="/login/reset" method="POST">
                    <label for="email">Email :</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required 
                        value="<?php echo htmlspecialchars($email); ?>"
                    >
                    <button type="submit" class="btn-primary">Vérifier</button>
                </form>
            <?php else: ?>
                <h2>Définir un nouveau mot de passe</h2>
                <p class="info-text">Choisissez un mot de passe sécurisé (8 caractères minimum, incluant une majuscule, une minuscule, un chiffre et un symbole).</p>
                <form action="/login/reset-password/update" method="POST">
                    <!-- Email caché pour identifier l'utilisateur -->
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                    <!-- Nouveau mot de passe -->
                    <label for="password">Nouveau mot de passe :</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            minlength="8">
                        <button type="button" id="togglePassword1" class="toggle-password" aria-label="Afficher le mot de passe"></button>
                    </div>

                    <!-- Confirmation -->
                    <label for="password_confirm">Confirmer le mot de passe :</label>
                    <div class="password-wrapper">
                        <input 
                            type="password" 
                            id="password_confirm" 
                            name="password_confirm" 
                            required 
                            minlength="8">
                        <button type="button" id="togglePassword2" class="toggle-password" aria-label="Afficher le mot de passe"></button>
                    </div>

                    <button type="submit" class="btn-primary">Mettre à jour le mot de passe</button>
                </form>
            <?php endif; ?>

            <p class="back-link">
                <a href="/login">← Retour à la connexion</a>
            </p>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>

<script>
// Gestion du toggle pour les deux champs de mot de passe
document.addEventListener('DOMContentLoaded', function () {
    const toggles = [
        { btn: 'togglePassword1', input: 'password' },
        { btn: 'togglePassword2', input: 'password_confirm' }
    ];

    const svgOpen = '<svg fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path></svg>';

    const svgClosed = '<svg fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"></path></svg>';

    toggles.forEach(function (item) {
        const btn = item.btn;
        const input = item.input;
        const toggle = document.getElementById(btn);
        const field = document.getElementById(input);
        if (!toggle || !field) return;

        function setIcon(type) {
            toggle.innerHTML = type === 'password' ? svgOpen : svgClosed;
            toggle.setAttribute('aria-label', type === 'password' ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
        }

        setIcon(field.getAttribute('type'));

        toggle.addEventListener('click', function () {
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);
            setIcon(type);
        });

        toggle.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggle.click();
            }
        });
    });

    // Validation en temps réel : confirmation
    const pwd = document.getElementById('password');
    const confirm = document.getElementById('password_confirm');
    const form = document.querySelector('form');

    if (form) {
        form.addEventListener('submit', function (e) {
            if (pwd && confirm && pwd.value !== confirm.value) {
                e.preventDefault();
                alert('Les deux mots de passe ne correspondent pas.');
            }
        });
    }
});
</script>
