<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
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
            <input type="password" id="password" name="password" required>

            <button type="submit">Se connecter</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
