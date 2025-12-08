<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlobeNight - Votre plateforme de location</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>        
        <section class="liste-biens">
            <div class="search-section">
                <h2>Trouvez votre logement idéal</h2>
                <form action="/home/search" method="GET" class="search-bar">
                    <input type="text" id="commune_search" name="q" placeholder="Rechercher par région..." value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>">
                    <button type="submit">Rechercher</button>
                </form>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="/home/map" class="btn-map" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 25px; font-weight: 500; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        📍 Voir la carte
                    </a>
                </div>
            </div>

            <h2>Tous nos biens</h2>
            <div class="biens-grid">
                <?php if (!empty($biens)): ?>
                    <?php foreach ($biens as $bien): ?>
                        <div class="bien-card">
                            <img src="<?php echo htmlspecialchars($bien["premiere_photo"] ?? '/images/default.jpg'); ?>" alt="Photo de <?php echo htmlspecialchars($bien["designation_bien"]); ?>">
                            <h3><?php echo htmlspecialchars($bien["designation_bien"]); ?></h3>
                            <p>Type: <?php echo htmlspecialchars($bien["type_bien_nom"]); ?></p>
                            <p>Commune: <?php echo htmlspecialchars($bien["commune_nom"]); ?></p>
                            <p>Superficie: <?php echo htmlspecialchars($bien["superficie_biens"]); ?> m²</p>
                            <p>Couchages: <?php echo htmlspecialchars($bien["nb_couchage"]); ?></p>
                            <p><?php echo htmlspecialchars(substr($bien["description_biens"], 0, 100)); ?>...</p>
                            <p class="prix">Prix jour: <?php echo htmlspecialchars(($bien["prix_semaine"] ?? null) ? number_format($bien["prix_semaine"], 2, ',', ' ') . ' €' : 'Non renseigné'); ?></p>
                            <a href="/bien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-reserver">Voir les détails</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun bien trouvé pour votre recherche.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <footer>
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="/js/autocomplete.js"></script>
</body>
</html>

