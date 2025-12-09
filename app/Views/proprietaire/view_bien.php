<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - Détails</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/bien-details.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <style>
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .bien-details-container {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Bouton retour */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 8px;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s;
            margin-bottom: 1.5rem;
        }

        .dark-mode .back-button {
            background: var(--bg-card);
        }

        .back-button:hover {
            background: var(--accent-primary, #ff5a5f);
            color: white;
            border-color: var(--accent-primary, #ff5a5f);
            transform: translateX(-4px);
        }

        /* En-tête du bien */
        .bien-header {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(255, 90, 95, 0.3);
        }

        .dark-mode .bien-header {
            background: linear-gradient(135deg, var(--night-stellar), var(--night-nebula));
        }

        .bien-title {
            font-size: 2.25rem;
            font-weight: 700;
            margin: 0 0 0.75rem 0;
            color: white;
        }

        .bien-address {
            font-size: 1.125rem;
            margin: 0;
            opacity: 0.95;
            font-weight: 500;
        }

        /* Grille de détails */
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Cartes */
        .details-card,
        .description-card,
        .prestations-card {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.75rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }

        .dark-mode .details-card,
        .dark-mode .description-card,
        .dark-mode .prestations-card {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .details-card:hover,
        .description-card:hover,
        .prestations-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 1.5rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--accent-primary, #ff5a5f);
        }

        /* Lignes de détails */
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color, #e0e0e0);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.9375rem;
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 1rem;
            text-align: right;
        }

        .badge-yes {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-weight: 700;
        }

        .badge-no {
            background: #ffebee;
            color: #c62828;
            padding: 0.375rem 0.875rem;
            border-radius: 20px;
            font-weight: 700;
        }

        .dark-mode .badge-yes {
            background: rgba(46, 125, 50, 0.2);
            color: #66bb6a;
        }

        .dark-mode .badge-no {
            background: rgba(198, 40, 40, 0.2);
            color: #ef5350;
        }

        /* Description */
        .description-card {
            margin-bottom: 1.5rem;
        }

        .description-text {
            font-size: 1rem;
            line-height: 1.7;
            color: var(--text-primary);
            margin: 0;
        }

        /* Prestations */
        .prestations-card {
            margin-bottom: 1.5rem;
        }

        .prestations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
        }

        .prestation-badge {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f5f5f5, #eeeeee);
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            padding: 0.875rem 1rem;
            font-weight: 500;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .dark-mode .prestation-badge {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
            border-color: var(--border-color);
        }

        .prestation-badge:hover {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border-color: var(--accent-primary, #ff5a5f);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 12px rgba(255, 90, 95, 0.3);
        }

        .prestation-qty {
            background: white;
            color: var(--accent-primary, #ff5a5f);
            padding: 0.25rem 0.625rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .prestation-badge:hover .prestation-qty {
            background: rgba(255, 255, 255, 0.95);
        }

        .no-prestations {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-style: italic;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            border: 2px dashed var(--border-color, #e0e0e0);
        }

        .dark-mode .no-prestations {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Accordéon des tarifs */
        .tarifs-card {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.2s;
        }

        .dark-mode .tarifs-card {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .tarifs-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .accordion-container {
            margin-top: 1rem;
        }

        .accordion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 1.0625rem;
            color: var(--text-primary);
        }

        .dark-mode .accordion-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.04));
            border-color: var(--border-color);
        }

        .accordion-header:hover {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border-color: var(--accent-primary, #ff5a5f);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 90, 95, 0.3);
        }

        .accordion-icon {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        .accordion-header.active .accordion-icon {
            transform: rotate(180deg);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease;
            margin-top: 0;
        }

        .accordion-content.active {
            max-height: 2000px;
            margin-top: 1rem;
        }

        .tarifs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
        }

        .tarif-item {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.2s;
        }

        .dark-mode .tarif-item {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .tarif-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            border-color: var(--accent-primary, #ff5a5f);
        }

        .tarif-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color, #e0e0e0);
        }

        .tarif-saison {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--accent-primary, #ff5a5f);
            flex: 1;
        }

        .tarif-annee {
            background: var(--accent-primary, #ff5a5f);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .tarif-prix {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
            text-align: center;
        }

        .tarif-prix-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            text-align: center;
            margin-top: 0.25rem;
        }

        .no-tarifs {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-style: italic;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            border: 2px dashed var(--border-color, #e0e0e0);
        }

        .dark-mode .no-tarifs {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Actions footer */
        .actions-footer {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .btn-action {
            flex: 1;
            min-width: 200px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            text-align: center;
            transition: all 0.2s;
            border: 2px solid;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-edit-full {
            background: var(--accent-primary, #ff5a5f);
            color: white;
            border-color: var(--accent-primary, #ff5a5f);
        }

        .btn-edit-full:hover {
            background: var(--accent-hover, #ff7f83);
            border-color: var(--accent-hover, #ff7f83);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 90, 95, 0.4);
        }

        .btn-manage-prestations {
            background: white;
            color: var(--accent-primary, #ff5a5f);
            border-color: var(--accent-primary, #ff5a5f);
        }

        .dark-mode .btn-manage-prestations {
            background: var(--bg-card);
        }

        .btn-manage-prestations:hover {
            background: var(--accent-primary, #ff5a5f);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 90, 95, 0.4);
        }

        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            color: var(--text-secondary);
            font-size: 0.9375rem;
            border-top: 1px solid var(--border-color);
        }

        /* Galerie de photos */
        .photos-gallery {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .photos-gallery {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .gallery-item {
            position: relative;
            aspect-ratio: 4/3;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid var(--border-color, #e0e0e0);
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-color: var(--accent-primary, #ff5a5f);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
            background: #f0f0f0;
            min-height: 200px;
        }

        .dark-mode .gallery-item img {
            background: #2a2a2a;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-overlay span {
            color: white;
            font-size: 1.25rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Responsive */
        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            .bien-title {
                font-size: 1.75rem;
            }

            .bien-header {
                padding: 1.5rem;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .prestations-grid {
                grid-template-columns: 1fr;
            }

            .actions-footer {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                min-width: auto;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .detail-value {
                text-align: left;
            }
        }
    </style>
</head>
<body class="home-sunset">
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

            <?php
            // Récupérer les photos du bien
            require_once __DIR__ . '/../../Models/PhotoModel.php';
            $photoModel = new PhotoModel();
            $photos = $photoModel->getPhotosByBien($bien["id_biens"]);
            ?>

            <?php if (!empty($photos)): ?>
                <div class="photos-gallery">
                    <h2 class="card-title">📷 Galerie Photos (<?php echo count($photos); ?>)</h2>
                    <div class="gallery-grid">
                        <?php foreach ($photos as $index => $photo): ?>
                            <?php 
                            // Construire le chemin de l'image
                            $imagePath = $photo['lien_photo'];
                            // Si le chemin ne commence pas par /, on l'ajoute
                            if ($imagePath && $imagePath[0] !== '/') {
                                $imagePath = '/' . $imagePath;
                            }
                            ?>
                            <div class="gallery-item" data-index="<?php echo $index; ?>">
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                     alt="<?php echo htmlspecialchars($photo['lib_photo'] ?? 'Photo ' . ($index + 1)); ?>"
                                     data-full="<?php echo htmlspecialchars($imagePath); ?>"
                                     onerror="this.src='/img/default.jpg'; this.onerror=null;">
                                <div class="gallery-overlay">
                                    <span>🔍 Voir</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="photos-gallery">
                    <h2 class="card-title">📷 Galerie Photos</h2>
                    <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">Aucune photo n'a été ajoutée pour ce bien.</p>
                </div>
            <?php endif; ?>

            <div class="details-grid">
                <div class="details-card">
                    <h2 class="card-title">🏠 Informations Générales</h2>
                    <div class="detail-row">
                        <span class="detail-label">Type de bien</span>
                        <span class="detail-value"><?php echo htmlspecialchars($bien["type_bien_nom"] ?? 'Non spécifié'); ?></span>
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

            <!-- Section Tarifs avec accordéon -->
            <div class="tarifs-card">
                <h2 class="card-title">💰 Tarifs par saison</h2>
                <?php if (!empty($tarifs)): ?>
                    <div class="accordion-container">
                        <div class="accordion-header" onclick="toggleTarifs()">
                            <span>💳 Voir tous les tarifs (<?php echo count($tarifs); ?> tarif(s))</span>
                            <span class="accordion-icon" id="tarifs-accordion-icon">▼</span>
                        </div>
                        <div class="accordion-content" id="tarifs-accordion-content">
                            <div class="tarifs-grid">
                                <?php foreach ($tarifs as $tarif): ?>
                                    <div class="tarif-item">
                                        <div class="tarif-header">
                                            <div class="tarif-saison"><?php echo htmlspecialchars($tarif["lib_saison"]); ?></div>
                                            <div class="tarif-annee"><?php echo htmlspecialchars($tarif["annee"]); ?></div>
                                        </div>
                                        <div class="tarif-prix"><?php echo number_format($tarif["prix_semaine"], 2, ',', ' '); ?> €</div>
                                        <div class="tarif-prix-label">par jour</div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-tarifs">
                        Aucun tarif n'a été défini pour ce bien.
                    </div>
                <?php endif; ?>
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

    <!-- Lightbox pour les photos -->
    <div id="lightbox" class="lightbox">
        <div class="lightbox-backdrop"></div>
        <div class="lightbox-container">
            <button class="lightbox-close" aria-label="Fermer">&times;</button>
            <button class="lightbox-prev" aria-label="Image précédente">‹</button>
            <button class="lightbox-next" aria-label="Image suivante">›</button>
            <div class="lightbox-content">
                <img src="" alt="" id="lightboxImage">
                <div class="lightbox-caption">
                    <span id="lightboxCounter"></span>
                    <span id="lightboxTitle"></span>
                </div>
            </div>
            <div class="lightbox-thumbnails" id="lightboxThumbnails"></div>
        </div>
    </div>

    <footer>
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script>
        // Fonction pour l'accordéon des tarifs
        function toggleTarifs() {
            const header = document.querySelector('.accordion-header');
            const content = document.getElementById('tarifs-accordion-content');
            const icon = document.getElementById('tarifs-accordion-icon');
            
            header.classList.toggle('active');
            content.classList.toggle('active');
        }

        // Lightbox JavaScript
        (function(){
            const photos = <?php echo json_encode($photos ?? []); ?>;
            if (photos.length === 0) return;

            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            const lightboxCounter = document.getElementById('lightboxCounter');
            const lightboxTitle = document.getElementById('lightboxTitle');
            const lightboxThumbnails = document.getElementById('lightboxThumbnails');
            const lightboxClose = document.querySelector('.lightbox-close');
            const lightboxPrev = document.querySelector('.lightbox-prev');
            const lightboxNext = document.querySelector('.lightbox-next');
            const lightboxBackdrop = document.querySelector('.lightbox-backdrop');
            
            let lightboxIndex = 0;

            // Créer les thumbnails
            photos.forEach((photo, index) => {
                const thumb = document.createElement('img');
                let photoPath = photo.lien_photo;
                if (photoPath && photoPath[0] !== '/') {
                    photoPath = '/' + photoPath;
                }
                thumb.src = photoPath;
                thumb.alt = 'Photo ' + (index + 1);
                thumb.className = 'lightbox-thumb';
                thumb.dataset.index = index;
                thumb.addEventListener('click', () => openLightbox(index));
                lightboxThumbnails.appendChild(thumb);
            });

            // Ouvrir lightbox au clic sur image de la galerie
            document.querySelectorAll('.gallery-item').forEach((item, index) => {
                item.addEventListener('click', () => openLightbox(index));
            });

            function openLightbox(index) {
                lightboxIndex = index;
                updateLightbox();
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            }

            function updateLightbox() {
                lightboxIndex = (lightboxIndex + photos.length) % photos.length;
                const photo = photos[lightboxIndex];
                
                let photoPath = photo.lien_photo;
                if (photoPath && photoPath[0] !== '/') {
                    photoPath = '/' + photoPath;
                }
                
                lightboxImage.src = photoPath;
                lightboxImage.alt = 'Photo ' + (lightboxIndex + 1);
                lightboxCounter.textContent = `${lightboxIndex + 1} / ${photos.length}`;
                lightboxTitle.textContent = photo.lib_photo || 'Photo ' + (lightboxIndex + 1);

                // Mettre à jour les thumbnails actifs
                document.querySelectorAll('.lightbox-thumb').forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === lightboxIndex);
                });
            }

            function prevLightbox() {
                lightboxIndex--;
                updateLightbox();
            }

            function nextLightbox() {
                lightboxIndex++;
                updateLightbox();
            }

            // Event listeners
            lightboxClose?.addEventListener('click', closeLightbox);
            lightboxBackdrop?.addEventListener('click', closeLightbox);
            lightboxPrev?.addEventListener('click', prevLightbox);
            lightboxNext?.addEventListener('click', nextLightbox);

            // Navigation clavier
            document.addEventListener('keydown', e => {
                if (!lightbox.classList.contains('active')) return;
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowLeft') prevLightbox();
                if (e.key === 'ArrowRight') nextLightbox();
            });
        })();
    </script>
</body>
</html>
