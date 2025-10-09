<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Locataire - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>Tableau de bord Locataire</h1>
        <nav>
            <ul>
                <li><a href="/locataire">Accueil Locataire</a></li>
                <li><a href="/locataire/myReservations">Mes Réservations</a></li>
                <li><a href="/logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Bienvenue sur votre tableau de bord, <?php echo htmlspecialchars($_SESSION["user_email"]); ?></h2>
        <p>Consultez vos réservations depuis cet espace.</p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
