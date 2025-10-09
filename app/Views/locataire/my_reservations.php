<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - Locataire</title>
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
        <h2>Mes Réservations</h2>
        <?php if (!empty($reservations)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Bien</th>
                        <th>Date de début</th>
                        <th>Date de fin</th>
                        <th>Commune</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation["designation_bien"]); ?></td>
                            <td><?php echo htmlspecialchars($reservation["date_debut"]); ?></td>
                            <td><?php echo htmlspecialchars($reservation["date_fin"]); ?></td>
                            <td><?php echo htmlspecialchars($reservation["ville_nom"]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez aucune réservation.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
