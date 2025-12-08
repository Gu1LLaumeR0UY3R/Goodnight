<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Prestations Disponibles</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="prestations-memo-container">
            <div class="page-header">
                <h2>📋 Liste des Prestations Disponibles</h2>
            </div>

            <div class="page-intro">
                <p><strong>Cette page vous présente toutes les prestations que vous pouvez ajouter à vos biens.</strong></p>
                <p>Utilisez la barre de recherche pour trouver rapidement une prestation spécifique.</p>
            </div>

            <?php if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'managePrestations') !== false): ?>
                <a href="<?php echo htmlspecialchars($_SERVER['HTTP_REFERER']); ?>" class="btn-editer-prestations" style="margin-bottom: 1.5rem; display: inline-flex; text-decoration: none;">
                    ← Retour à la gestion des prestations
                </a>
            <?php endif; ?>

            <div class="prestations-count">
                <p>Total : <strong><?php echo count($prestations); ?></strong> prestations disponibles</p>
            </div>

            <div class="search-box">
                <input type="text" 
                       class="search-input" 
                       id="searchPrestations" 
                       placeholder="🔍 Rechercher une prestation...">
            </div>

            <div id="prestationsContainer">
                <?php if (!empty($prestations)): ?>
                    <div class="prestations-grid">
                        <?php foreach ($prestations as $prestation): ?>
                            <div class="prestation-card" data-name="<?php echo htmlspecialchars(strtolower($prestation['lib_prestation'])); ?>">
                                <span class="prestation-icon">🏠</span>
                                <span class="prestation-name"><?php echo htmlspecialchars($prestation['lib_prestation']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        Aucune prestation disponible pour le moment.
                    </div>
                <?php endif; ?>
            </div>

            <div id="noResults" class="no-results" style="display: none;">
                Aucune prestation ne correspond à votre recherche.
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script>
        const searchInput = document.getElementById('searchPrestations');
        const prestationsCards = document.querySelectorAll('.prestation-card');
        const noResults = document.getElementById('noResults');
        const prestationsContainer = document.getElementById('prestationsContainer');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            prestationsCards.forEach(card => {
                const name = card.getAttribute('data-name');
                if (name.includes(query)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                prestationsContainer.style.display = 'none';
                noResults.style.display = 'block';
            } else {
                prestationsContainer.style.display = 'block';
                noResults.style.display = 'none';
            }
        });
    </script>
</body>
</html>
