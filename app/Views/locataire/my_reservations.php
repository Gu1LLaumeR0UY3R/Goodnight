<?php
// Vérification de connexion et de rôle pour les non-admins
if (!isset($_SESSION['user_id']) || isset($_SESSION['is_admin']) || !in_array('Locataire', $_SESSION['user_roles'] ?? [])) {
    header("Location: /login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">

</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="container">
        <h1>Mes Réservations</h1>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <?php if (!empty($reservations)): ?>
            <?php foreach ($reservations as $reservation): ?>
                <div class="reservation-card">
                    <img src="<?php echo htmlspecialchars($reservation["premiere_photo"] ?? '/images/default.jpg'); ?>" alt="Photo du bien">
                    <div class="reservation-details">
                        <h2><?php echo htmlspecialchars($reservation['designation_bien']); ?></h2>
                        <p><strong>Adresse :</strong> <?php echo htmlspecialchars($reservation['rue_biens']); ?> (<?php echo htmlspecialchars($reservation['commune_nom']); ?>)</p>
                        <p><strong>Période :</strong> Du <?php echo date('d/m/Y', strtotime($reservation['date_debut'])); ?> au <?php echo date('d/m/Y', strtotime($reservation['date_fin'])); ?></p>
                        <!-- La colonne statut n'existe pas dans la BDD -->
                    </div>
                    <!-- Actions de réservation (Annuler) - Supprimé car dépend de la colonne statut -->
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez effectué aucune réservation pour le moment.</p>
        <?php endif; ?>
    </main>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
