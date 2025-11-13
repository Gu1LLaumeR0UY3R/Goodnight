<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2>Connectez-vous à votre compte</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<p class="success">' . htmlspecialchars($_SESSION['success']) . '</p>';
            unset($_SESSION['success']);
        }
        ?>
        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
            <p class="success">Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.</p>
        <?php endif; ?>

        <form action="/login/process" method="POST">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($old_email ?? ''); ?>">

            <label for="password">Mot de passe :</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" required>
                <button type="button" id="togglePassword" class="toggle-password" aria-label="Afficher le mot de passe"></button>
            </div>

            <button type="submit">Se connecter</button>
            <p class="reset-link">
                <a href="/login/reset">Mot de passe oublié ?</a>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>

<script>
// Basculer l'affichage du mot de passe (accessible, amélioration progressive)
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('togglePassword');
    var pwd = document.getElementById('password');
    if (!toggle || !pwd) return;

    // Icônes SVG fournies (œil ouvert / œil fermé)
    var svgClosed = '<svg fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">\n  <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"></path>\n</svg>';
    var svgOpen = '<svg fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">\n  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"></path>\n  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>\n</svg>';

    // initialiser l'icône en fonction du type actuel du champ
    function setIconForType(type) {
        if (type === 'password') {
            toggle.innerHTML = svgOpen;
            toggle.setAttribute('aria-label', 'Afficher le mot de passe');
        } else {
            toggle.innerHTML = svgClosed;
            toggle.setAttribute('aria-label', 'Masquer le mot de passe');
        }
    }

    setIconForType(pwd.getAttribute('type'));

    toggle.addEventListener('click', function () {
        var type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
        pwd.setAttribute('type', type);
        setIconForType(type);
    });

    // permettre l'activation au clavier (Entrée/Espace)
    toggle.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            toggle.click();
        }
    });
});
</script>