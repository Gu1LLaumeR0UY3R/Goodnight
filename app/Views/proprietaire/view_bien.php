<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - Détails</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="bien-details-container">
            <a href="/proprietaire/myBiens" class="back-button">← Retour à mes biens</a>

            <div class="bien-header">
                <h1 class="bien-title"><?php echo htmlspecialchars($bien["designation_bien"]); ?></h1>
                <p class="bien-address">
                    📍 <?php echo htmlspecialchars($bien["rue_biens"]); ?>
                    <?php if (!empty($bien["complement_biens"])): ?>
                        , <?php echo htmlspecialchars($bien["complement_biens"]); ?>
                    <?php endif; ?>
                    - <?php echo htmlspecialchars($bien["ville_nom"] ?? ''); ?> 
                    (<?php echo htmlspecialchars($bien["ville_code_postal"] ?? ''); ?>)
                </p>
            </div>

            <div class="details-grid">
                <div class="details-card">
                    <h2 class="card-title">🏠 Informations Générales</h2>
                    <div class="detail-row">
                        <span class="detail-label">Type de bien</span>
                        <span class="detail-value"><?php echo htmlspecialchars($bien["desc_type_bien"] ?? 'Non spécifié'); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Superficie</span>
                        <span class="detail-value"><?php echo htmlspecialchars($bien["superficie_biens"]); ?> m²</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Nombre de couchages</span>
                        <span class="detail-value"><?php echo htmlspecialchars($bien["nb_couchage"]); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Animaux acceptés</span>
                        <span class="detail-value <?php echo $bien["animaux_biens"] ? 'badge-yes' : 'badge-no'; ?>">
                            <?php echo $bien["animaux_biens"] ? '✓ Oui' : '✗ Non'; ?>
                        </span>
                    </div>
                </div>

                <div class="details-card">
                    <h2 class="card-title">📋 Statistiques</h2>
                    <div class="detail-row">
                        <span class="detail-label">Prestations</span>
                        <span class="detail-value"><?php echo count($prestations); ?> équipement(s)</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date d'ajout</span>
                        <span class="detail-value">-</span>
                    </div>
                </div>
            </div>

            <?php if (!empty($bien["description_biens"])): ?>
                <div class="description-card">
                    <h2 class="card-title">📝 Description</h2>
                    <p class="description-text"><?php echo nl2br(htmlspecialchars($bien["description_biens"])); ?></p>
                </div>
            <?php endif; ?>

            <div class="prestations-card">
                <h2 class="card-title">✨ Prestations et Équipements</h2>
                <?php if (!empty($prestations)): ?>
                    <div class="prestations-grid">
                        <?php foreach ($prestations as $prestation): ?>
                            <div class="prestation-badge">
                                <span><?php echo htmlspecialchars($prestation["lib_prestation"]); ?></span>
                                <span class="prestation-qty">x<?php echo htmlspecialchars($prestation["quantite_prestation"] ?? 1); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-prestations">
                        Aucune prestation n'a été ajoutée à ce bien pour le moment.
                    </div>
                <?php endif; ?>
            </div>

            <div class="actions-footer">
                <a href="/proprietaire/editBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-action btn-edit-full">
                    ✏️ Modifier le bien
                </a>
                <a href="/proprietaire/managePrestations/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-action btn-manage-prestations">
                    ⚙️ Gérer les prestations
                </a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
