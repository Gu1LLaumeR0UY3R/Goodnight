<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Prestations Disponibles</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="/css/night-background.css">
    <style>
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .prestations-memo-container {
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

        /* En-tête de page */
        .page-header {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 16px rgba(255, 90, 95, 0.3);
            text-align: center;
        }

        .dark-mode .page-header {
            background: linear-gradient(135deg, var(--night-stellar), var(--night-nebula));
        }

        .page-header h2 {
            margin: 0;
            font-size: 2.25rem;
            font-weight: 700;
        }

        /* Intro */
        .page-intro {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .page-intro {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .page-intro p {
            margin: 0 0 0.75rem 0;
            color: var(--text-primary);
            line-height: 1.6;
        }

        .page-intro p:last-child {
            margin-bottom: 0;
        }

        .page-intro strong {
            color: var(--accent-primary, #ff5a5f);
        }

        /* Bouton retour */
        .btn-editer-prestations {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1.5rem;
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(255, 90, 95, 0.3);
        }

        .btn-editer-prestations:hover {
            background: linear-gradient(135deg, var(--accent-hover, #ff7f83), #ff9999);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(255, 90, 95, 0.4);
        }

        /* Compteur */
        .prestations-count {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 2px solid #2196f3;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
        }

        .prestations-count p {
            margin: 0;
            color: #0d47a1;
            font-size: 1.125rem;
        }

        .prestations-count strong {
            font-size: 1.375rem;
            font-weight: 700;
            color: #1565c0;
        }

        /* Barre de recherche */
        .search-box {
            margin-bottom: 2rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            background: white;
            color: var(--text-primary);
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .search-input {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-primary, #ff5a5f);
            box-shadow: 0 0 0 3px rgba(255, 90, 95, 0.1), 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        /* Grille de prestations */
        .prestations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.25rem;
        }

        .prestation-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .prestation-card {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .prestation-card:hover {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border-color: var(--accent-primary, #ff5a5f);
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 6px 20px rgba(255, 90, 95, 0.3);
        }

        .prestation-icon {
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .prestation-name {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-primary);
            transition: color 0.2s;
        }

        .prestation-card:hover .prestation-name {
            color: white;
        }

        /* Message aucun résultat */
        .no-results {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
            font-style: italic;
            font-size: 1.125rem;
            background: white;
            border: 2px dashed var(--border-color, #e0e0e0);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .no-results {
            background: var(--bg-card);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            color: var(--text-secondary);
            font-size: 0.9375rem;
            border-top: 1px solid var(--border-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            .page-header h2 {
                font-size: 1.75rem;
            }

            .prestations-grid {
                grid-template-columns: 1fr;
            }

            .page-intro {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body class="home-sunset">
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
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
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
