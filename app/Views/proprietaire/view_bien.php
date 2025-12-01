<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - Détails</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
        .bien-details-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #007bff;
            text-decoration: none;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .back-button:hover {
            text-decoration: underline;
        }

        .bien-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .bien-title {
            font-size: 2rem;
            margin: 0 0 0.5rem 0;
        }

        .bien-address {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .details-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: #666;
        }

        .detail-value {
            font-weight: 600;
            color: #333;
        }

        .description-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .description-text {
            line-height: 1.6;
            color: #555;
        }

        .prestations-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .prestations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .prestation-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .prestation-qty {
            background: rgba(255,255,255,0.3);
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .no-prestations {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 2rem;
        }

        .actions-footer {
            display: flex;
            gap: 1rem;
            justify-content: center;
            padding: 2rem 0;
        }

        .btn-action {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-edit-full {
            background: #007bff;
            color: white;
        }

        .btn-edit-full:hover {
            background: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        }

        .btn-manage-prestations {
            background: #28a745;
            color: white;
        }

        .btn-manage-prestations:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40,167,69,0.3);
        }

        .badge-yes {
            color: #28a745;
            font-weight: 600;
        }

        .badge-no {
            color: #dc3545;
            font-weight: 600;
        }
    </style>
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
