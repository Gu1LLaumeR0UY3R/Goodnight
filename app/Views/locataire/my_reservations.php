<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - Locataire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2>Mes Réservations</h2>

        <!-- Messages -->
        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (!empty($reservations)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Bien</th>
                        <th>Propriétaire</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Commune</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td>
                                <?php if (!empty($reservation['premiere_photo'])): ?>
                                    <img src="<?= htmlspecialchars($reservation['premiere_photo']) ?>" 
                                         alt="Photo" class="photo-thumb">
                                <?php endif; ?>
                                <?= htmlspecialchars($reservation["designation_bien"]) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($reservation["proprietaire_nom"] . " " . $reservation["proprietaire_prenom"]) ?>
                            </td>
                            <td><?= htmlspecialchars($reservation["date_debut"]) ?></td>
                            <td><?= htmlspecialchars($reservation["date_fin"]) ?></td>
                            <td><?= htmlspecialchars($reservation["commune_nom"]) ?></td>
                            <td>
                                <form action="/reservation/cancel/<?= $reservation['id_reservation'] ?>" 
                                      method="POST" 
                                      style="display:inline;"
                                      onsubmit="return confirm('Annuler cette réservation ?');">
                                    <button type="submit" class="btn-cancel">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez aucune réservation en cours.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>