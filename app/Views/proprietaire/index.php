<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Propriétaire - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <h1>Tableau de bord Propriétaire</h1>
        <nav>
            <ul>
                <li><a href="/proprietaire">Accueil Propriétaire</a></li>
                <li><a href="/proprietaire/myBiens">Mes Biens</a></li>
                <li><a href="/proprietaire/myReservations">Mes Réservations</a></li>
                <li><a href="/logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Bienvenue sur votre tableau de bord, <?php echo htmlspecialchars($_SESSION["user_email"]); ?></h2>
        <p>Gérez vos biens et vos réservations depuis cet espace.</p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
