<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlobeNight - Votre plateforme de location</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="/css/favoris.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>
<body class="home-sunset">
    <div class="top-banner"></div>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>        
        <section class="liste-biens">
            <div class="search-section">
                <h2>Trouvez votre logement idéal</h2>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <button id="toggle-filters" type="button" style="width: 48px; height: 48px; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 50%; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.2s; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);" title="Afficher les filtres avancés">
                        <svg style="width: 1.3rem; height: 1.3rem;" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"></path>
                        </svg>
                    </button>
                    <form action="/home/search" method="GET" class="search-bar" style="flex: 1;">
                        <input type="text" id="commune_search" name="q" placeholder="Rechercher par région..." value="<?php echo htmlspecialchars($filters['commune'] ?? ''); ?>">
                        <button type="submit">Rechercher</button>
                    </form>
                </div>

                <!-- Filtres avancés (cachés par défaut) -->
                <div id="filters-panel" class="filters-container" style="display: none; background: rgba(255,255,255,0.95); border-radius: 12px; padding: 1.5rem; margin-top: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); animation: slideDown 0.3s ease-out;">
                    <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: #2c3e50;">Filtres avancés</h3>
                    <form action="/home/search" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                        <!-- Commune (hidden pour conserver la recherche) -->
                        <input type="hidden" name="q" value="<?php echo htmlspecialchars($filters['commune'] ?? ''); ?>">
                        
                        <!-- Type de bien -->
                        <div>
                            <label for="type_bien" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">Type de bien</label>
                            <select name="type_bien" id="type_bien" style="width: 100%; padding: 0.6rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                                <option value="">Tous les types</option>
                                <?php foreach ($typesBiens as $type): ?>
                                    <option value="<?php echo $type['id_typebien']; ?>" <?php echo (($filters['type_bien'] ?? '') == $type['id_typebien']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($type['desc_type_bien']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Superficie min -->
                        <div>
                            <label for="superficie_min" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">Superficie min (m²)</label>
                            <input type="number" name="superficie_min" id="superficie_min" min="0" placeholder="Ex: 50" value="<?php echo htmlspecialchars($filters['superficie_min'] ?? ''); ?>" style="width: 100%; padding: 0.6rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                        </div>

                        <!-- Prix min -->
                        <div>
                            <label for="prix_min" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">Superficie max (m²)</label>
                            <input type="number" name="superficie_max" id="superficie_max" min="0" placeholder="Ex: 150" value="<?php echo htmlspecialchars($filters['superficie_max'] ?? ''); ?>" style="width: 100%; padding: 0.6rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                        </div>

                        <!-- Slider Prix -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">
                                Prix (€/semaine): 
                                <span id="prix-range-label" style="color: #667eea; font-weight: 600;">
                                    <?php 
                                    $prixMin = $filters['prix_min'] ?? 0;
                                    $prixMax = $filters['prix_max'] ?? 5000;
                                    echo htmlspecialchars($prixMin) . ' € - ' . htmlspecialchars($prixMax) . ' €';
                                    ?>
                                </span>
                            </label>
                            <div id="prix-slider" style="margin: 1rem 0.5rem 0.5rem 0.5rem;"></div>
                            <input type="hidden" name="prix_min" id="prix_min" value="<?php echo htmlspecialchars($filters['prix_min'] ?? ''); ?>">
                            <input type="hidden" name="prix_max" id="prix_max" value="<?php echo htmlspecialchars($filters['prix_max'] ?? ''); ?>">
                        </div>

                        <!-- Boutons -->
                        <div style="display: flex; gap: 0.5rem;">
                            <button type="submit" style="flex: 1; padding: 0.6rem 1.2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; transition: transform 0.2s;">
                                Appliquer
                            </button>
                            <a href="/home" style="flex: 1; padding: 0.6rem 1.2rem; background: #e0e0e0; color: #555; border: none; border-radius: 6px; font-weight: 500; text-align: center; text-decoration: none; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center;">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <div style="text-align: center; margin-top: 20px;">
                    <a href="/home/map" class="btn-map" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 25px; font-weight: 500; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <svg style="width: 1.25rem; height: 1.25rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"></path>
                        </svg>
                        Voir la carte
                    </a>
                </div>
            </div>

            <h2>
                <?php 
                $hasActiveFilters = !empty(array_filter($filters ?? []));
                echo $hasActiveFilters ? 'Résultats de la recherche' : 'Tous nos biens';
                ?>
                <span style="font-size: 0.9rem; color: #667eea; font-weight: normal;">
                    (<?php echo count($biens ?? []); ?> bien<?php echo count($biens ?? []) > 1 ? 's' : ''; ?>)
                </span>
            </h2>
            <div class="biens-grid">
                <?php if (!empty($biens)): ?>
                    <?php foreach ($biens as $bien): ?>
                        <div class="bien-card" data-bien-id="<?php echo htmlspecialchars($bien["id_biens"]); ?>">
                            <!-- Bouton Favori -->
                            <button class="btn-favorite" data-bien-id="<?php echo htmlspecialchars($bien["id_biens"]); ?>" title="Ajouter aux favoris">
                                <span class="heart-icon">♡</span>
                            </button>
                            
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

    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #toggle-filters:hover {
            transform: scale(1.05);
        }

        #toggle-filters:active {
            transform: scale(0.98);
        }
    </style>

    <script>
        // Initialiser le slider de prix
        $(function() {
            var prixMin = <?php echo !empty($filters['prix_min']) ? intval($filters['prix_min']) : 0; ?>;
            var prixMax = <?php echo !empty($filters['prix_max']) ? intval($filters['prix_max']) : 5000; ?>;
            
            $("#prix-slider").slider({
                range: true,
                min: 0,
                max: 5000,
                step: 50,
                values: [prixMin, prixMax],
                slide: function(event, ui) {
                    $("#prix-range-label").text(ui.values[0] + " € - " + ui.values[1] + " €");
                    $("#prix_min").val(ui.values[0]);
                    $("#prix_max").val(ui.values[1]);
                }
            });
        });

        document.getElementById('toggle-filters').addEventListener('click', function() {
            const panel = document.getElementById('filters-panel');
            const button = this;
            
            if (panel.style.display === 'none') {
                panel.style.display = 'block';
                button.innerHTML = '<svg style="width: 1.3rem; height: 1.3rem;" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';
                button.title = 'Fermer les filtres';
            } else {
                panel.style.display = 'none';
                button.innerHTML = '<svg style="width: 1.3rem; height: 1.3rem;" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"></path></svg>';
                button.title = 'Afficher les filtres avancés';
            }
        });

        // Ouvrir automatiquement si des filtres sont actifs
        <?php if (!empty(array_filter($filters ?? []))): ?>
        document.getElementById('filters-panel').style.display = 'block';
        document.getElementById('toggle-filters').innerHTML = '<svg style="width: 1.3rem; height: 1.3rem;" fill="none" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>';
        document.getElementById('toggle-filters').title = 'Fermer les filtres';
        <?php endif; ?>
    </script>

    <script src="/js/autocomplete.js"></script>
    <script src="/js/favoris.js"></script>
</body>
</html>

