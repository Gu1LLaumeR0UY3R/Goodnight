<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Prestations Disponibles</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
        .prestations-memo-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .page-intro {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .page-intro p {
            margin: 0.5rem 0;
            color: #666;
        }

        .search-box {
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            max-width: 500px;
            padding: 0.75rem 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .search-input:focus {
            outline: none;
            border-color: #007bff;
        }

        .prestations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }

        .prestation-card {
            background: white;
            padding: 1rem 1.25rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .prestation-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .btn-editer-prestations {
            background: #007bff;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-editer-prestations:hover {
            background: #0056b3;
        }

        .prestation-icon {
            font-size: 1.5rem;
            opacity: 0.7;
        }

        .prestation-name {
            font-weight: 500;
            font-size: 1rem;
        }

        .prestations-count {
            background: #e9ecef;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .prestations-count strong {
            color: #007bff;
            font-size: 1.2rem;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
            font-style: italic;
        }

        .category-section {
            margin-bottom: 2.5rem;
        }

        .category-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
        }
    </style>
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
