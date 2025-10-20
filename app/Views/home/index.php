<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlobeNight - Votre plateforme de location</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <section class="hero">
            <h2>Trouvez votre logement idéal</h2>
            <form action="/home/search" method="GET" class="search-bar">
                <input type="text" id="commune_search" name="q" placeholder="Rechercher par région..." value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>">
                <button type="submit">Rechercher</button>
            </form>
        </section>

        <section class="liste-biens">
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
                            <p class="prix">Prix semaine: <?php echo htmlspecialchars(($bien["prix_semaine"] ?? null) ? number_format($bien["prix_semaine"], 2, ',', ' ') . ' €' : 'Non renseigné'); ?></p>
                            <a href="/bien/<?php echo htmlspecialchars($bien["id_biens"]); ?>">Voir les détails</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun bien trouvé pour votre recherche.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="/js/autocomplete.js"></script>
</body>
</html>

