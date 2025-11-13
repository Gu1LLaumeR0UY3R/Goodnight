<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - Locataire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
        .btn-cancel {
            background: #dc3545; color: white; border: none;
            padding: 6px 12px; border-radius: 4px; cursor: pointer;
            font-size: 0.9em; transition: background 0.2s;
        }
        .btn-cancel:hover { background: #c82333; }
        .alert { padding: 12px; margin: 15px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .photo-thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; vertical-align: middle; margin-right: 8px; }
    </style>
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