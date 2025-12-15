<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/favoris.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
</head>
<body class="home-sunset">
    <div class="top-banner"></div>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="favorites-page">
        <!-- HEADER -->
        <section class="favorites-header">
            <div class="header-content">
                <h1>Mes Biens Favoris ❤️</h1>
                <p class="header-subtitle">Découvrez vos biens préférés</p>
            </div>
        </section>

        <!-- CONTENU PRINCIPAL -->
        <section class="favorites-content">
            <?php if (!empty($favorites)): ?>
                <!-- Bouton Effacer tous les favoris -->
                <div class="favorites-actions">
                    <button onclick="clearAllFavorites()" class="btn-clear-all">Effacer tous les favoris</button>
                </div>

                <!-- Grille des favoris -->
                <div class="biens-grid">
                    <?php foreach ($favorites as $bien): ?>
                        <div class="bien-card" data-bien-id="<?php echo htmlspecialchars($bien["id_biens"]); ?>">
                            <!-- Bouton Favori -->
                            <button class="btn-favorite" data-bien-id="<?php echo htmlspecialchars($bien["id_biens"]); ?>" title="Retirer des favoris">
                                <span class="heart-icon">♡</span>
                            </button>
                            
                            <img src="<?php echo htmlspecialchars($bien["premiere_photo"] ?? '/images/default.jpg'); ?>" alt="Photo de <?php echo htmlspecialchars($bien["designation_bien"]); ?>">
                            <h3><?php echo htmlspecialchars($bien["designation_bien"]); ?></h3>
                            <p class="bien-type">Type: <?php echo htmlspecialchars($bien["type_bien_nom"]); ?></p>
                            <p class="bien-commune">Commune: <?php echo htmlspecialchars($bien["commune_nom"]); ?></p>
                            <p class="bien-superficie">Superficie: <?php echo htmlspecialchars($bien["superficie_biens"]); ?> m²</p>
                            <p class="bien-couchages">Couchages: <?php echo htmlspecialchars($bien["nb_couchage"]); ?></p>
                            <p class="bien-description"><?php echo htmlspecialchars(substr($bien["description_biens"], 0, 100)); ?>...</p>
                            <p class="prix">Prix jour: <?php echo htmlspecialchars(($bien["prix_semaine"] ?? null) ? number_format($bien["prix_semaine"], 2, ',', ' ') . ' €' : 'Non renseigné'); ?></p>
                            
                            <div class="bien-actions">
                                <a href="/bien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-details">Voir les détails</a>
                                <a href="/home" class="btn-similar">Similaires</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <!-- État vide -->
                <div class="empty-favorites">
                    <div class="heart-big">♡</div>
                    <h2>Aucun favori pour le moment</h2>
                    <p>Commencez par ajouter vos biens préférés !</p>
                    <a href="/home" class="btn-explore">Découvrir les biens →</a>
                </div>
            <?php endif; ?>
        </section>

        <!-- SECTION INFO -->
        <?php if (!empty($favorites)): ?>
            <section class="favorites-info">
                <h2>Conseils</h2>
                <div class="info-cards">
                    <div class="info-card">
                        <h3>💡 Conseil</h3>
                        <p>Comparez vos favoris pour trouver le bien qui vous convient le mieux.</p>
                    </div>
                    <div class="info-card">
                        <h3>📅 Réservation</h3>
                        <p>Consultez les disponibilités et réservez votre prochain séjour.</p>
                    </div>
                    <div class="info-card">
                        <h3>⭐ Avis</h3>
                        <p>Consultez les avis d'autres voyageurs pour vous aider.</p>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <footer>
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="/js/favoris.js"></script>
</body>
</html>
