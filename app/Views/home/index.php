<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GlobeNight - Votre plateforme de location</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="/css/night-background.css">
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
                <form action="/home/search" method="GET" class="search-bar" style="display: flex; gap: 0.6rem; align-items: center; flex-wrap: wrap; background: rgba(255,255,255,0.92); border: 1px solid rgba(255,140,66,0.25); border-radius: 14px; padding: 0.6rem 0.7rem; box-shadow: 0 6px 16px rgba(0,0,0,0.08);">
                    <div style="display: flex; align-items: center; gap: 0.6rem; flex: 1; min-width: 260px;">
                        <input type="text" id="commune_search" name="q" placeholder="Rechercher par région..." value="<?php echo htmlspecialchars($filters['commune'] ?? ''); ?>" style="width: 100%; height: 48px; padding: 0 14px; border: 1px solid rgba(0,0,0,0.08); border-radius: 12px; font-size: 1rem; box-shadow: inset 0 1px 2px rgba(0,0,0,0.04);">
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <button type="submit" style="height: 48px; padding: 0 18px; background: linear-gradient(135deg, #ff8c42 0%, #ff5e62 100%); color: white; border: none; border-radius: 12px; font-weight: 600; letter-spacing: 0.01em; cursor: pointer; box-shadow: 0 6px 14px rgba(255, 94, 98, 0.28); transition: transform 0.15s ease, box-shadow 0.2s ease;">
                            Rechercher
                        </button>
                        <button id="toggle-filters" type="button" style="height: 48px; padding: 0 16px; background: linear-gradient(135deg, #ff8c42 0%, #ff5e62 100%); color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.4rem; transition: transform 0.15s ease, box-shadow 0.2s ease; box-shadow: 0 6px 14px rgba(255, 94, 98, 0.28);" title="Afficher les filtres avancés">
                            <svg style="width: 1.2rem; height: 1.2rem;" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"></path>
                            </svg>
                            <span>Filtres</span>
                        </button>
                    </div>
                </form>

                <!-- Filtres avancés (cachés par défaut) -->
                <div id="filters-panel" class="filters-container" style="display: none; background: rgba(255,255,255,0.95); border-radius: 12px; padding: 1.5rem; margin-top: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); animation: slideDown 0.3s ease-out;">
                    <h3 style="margin-bottom: 1rem; font-size: 1.1rem; color: #2c3e50;">Filtres avancés</h3>
                    <form action="/home/search" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                        <!-- Commune (hidden pour conserver la recherche) -->
                        <input type="hidden" name="q" value="<?php echo htmlspecialchars($filters['commune'] ?? ''); ?>">
                        
                        <!-- Types de bien (multi-sélection) -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">Types de bien</label>
                            
                            <!-- Boutons de sélection -->
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 0.75rem;">
                                <?php foreach ($typesBiens as $type): ?>
                                    <button type="button" 
                                            class="type-bien-btn" 
                                            data-type-id="<?php echo htmlspecialchars($type['id_typebien']); ?>"
                                            onclick="toggleTypeBien(<?php echo htmlspecialchars($type['id_typebien']); ?>)"
                                            style="padding: 0.5rem 1rem; background: white; border: 2px solid #ddd; border-radius: 20px; font-size: 0.9rem; cursor: pointer; transition: all 0.2s; font-weight: 500;">
                                        <?php echo htmlspecialchars($type['desc_type_bien']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Inputs cachés pour le formulaire -->
                            <div id="hidden-types-inputs"></div>
                        </div>

                        <!-- Prestations avec recherche -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">Prestations</label>
                            <div style="position: relative;">
                                <input type="text" 
                                       id="prestation-search" 
                                       placeholder="Rechercher une prestation (ex: piscine, wifi...)" 
                                       autocomplete="off"
                                       style="width: 100%; padding: 0.6rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                                <div id="prestation-autocomplete" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; border-radius: 0 0 6px 6px; max-height: 200px; overflow-y: auto; display: none; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                            </div>
                            
                            <!-- Badges des prestations sélectionnées -->
                            <div id="selected-prestations" style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.75rem; min-height: 28px;"></div>
                            
                            <!-- Inputs cachés pour le formulaire -->
                            <div id="hidden-prestations-inputs"></div>
                        </div>

                        <!-- Slider Couchages -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">
                                Couchages:
                                <span id="couchage-range-label" style="color: #667eea; font-weight: 600;">
                                    <?php 
                                    $couchageMin = $filters['couchage_min'] ?? 1;
                                    $couchageMax = $filters['couchage_max'] ?? 10;
                                    echo htmlspecialchars($couchageMin) . ' - ' . htmlspecialchars($couchageMax);
                                    ?>
                                </span>
                            </label>
                            <div id="couchage-slider" style="margin: 1rem 0.5rem 0.5rem 0.5rem;"></div>
                            <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                                <div style="flex: 1;">
                                    <input type="number" name="couchage_min" id="couchage_min" min="1" max="20" step="1" placeholder="Min" value="<?php echo htmlspecialchars($filters['couchage_min'] ?? ''); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                                </div>
                                <div style="flex: 1;">
                                    <input type="number" name="couchage_max" id="couchage_max" min="1" max="20" step="1" placeholder="Max" value="<?php echo htmlspecialchars($filters['couchage_max'] ?? ''); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                                </div>
                            </div>
                        </div>

                        <!-- Slider Superficie -->
                        <div style="grid-column: span 2;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">
                                Superficie (m²): 
                                <span id="superficie-range-label" style="color: #667eea; font-weight: 600;">
                                    <?php 
                                    $superficieMin = $filters['superficie_min'] ?? 0;
                                    $superficieMax = $filters['superficie_max'] ?? 500;
                                    echo htmlspecialchars($superficieMin) . ' m² - ' . htmlspecialchars($superficieMax) . ' m²';
                                    ?>
                                </span>
                            </label>
                            <div id="superficie-slider" style="margin: 1rem 0.5rem 0.5rem 0.5rem;"></div>
                            <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                                <div style="flex: 1;">
                                    <input type="number" name="superficie_min" id="superficie_min" min="0" max="500" step="10" placeholder="Min" value="<?php echo htmlspecialchars($filters['superficie_min'] ?? ''); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                                </div>
                                <div style="flex: 1;">
                                    <input type="number" name="superficie_max" id="superficie_max" min="0" max="500" step="10" placeholder="Max" value="<?php echo htmlspecialchars($filters['superficie_max'] ?? ''); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                                </div>
                            </div>
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
                            <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                                <div style="flex: 1;">
                                    <input type="number" name="prix_min" id="prix_min" min="0" max="10000" step="50" placeholder="Min" value="<?php echo htmlspecialchars($filters['prix_min'] ?? ''); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                                </div>
                                <div style="flex: 1;">
                                    <input type="number" name="prix_max" id="prix_max" min="0" max="10000" step="50" placeholder="Max" value="<?php echo htmlspecialchars($filters['prix_max'] ?? ''); ?>" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; font-size: 0.9rem;">
                                </div>
                            </div>
                        </div>

                        <!-- Animaux acceptés + Tri -->
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">Animaux acceptés</label>
                            <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: 500; color: #555;">
                                <input type="checkbox" name="animaux" value="1" <?php echo (!empty($filters['animaux'])) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                                Oui
                            </label>
                        </div>

                        <div>
                            <label for="tri" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #555;">Trier par</label>
                            <select name="tri" id="tri" style="width: 100%; padding: 0.6rem; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95rem;">
                                <option value="">Par défaut</option>
                                <option value="prix_asc" <?php echo (($filters['tri'] ?? '') === 'prix_asc') ? 'selected' : ''; ?>>Prix croissant</option>
                                <option value="prix_desc" <?php echo (($filters['tri'] ?? '') === 'prix_desc') ? 'selected' : ''; ?>>Prix décroissant</option>
                                <option value="superficie_asc" <?php echo (($filters['tri'] ?? '') === 'superficie_asc') ? 'selected' : ''; ?>>Superficie croissante</option>
                                <option value="superficie_desc" <?php echo (($filters['tri'] ?? '') === 'superficie_desc') ? 'selected' : ''; ?>>Superficie décroissante</option>
                            </select>
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
        // Initialiser les sliders
        $(function() {
            // Slider de prix
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
            
            // Slider de superficie
            var superficieMin = <?php echo !empty($filters['superficie_min']) ? intval($filters['superficie_min']) : 0; ?>;
            var superficieMax = <?php echo !empty($filters['superficie_max']) ? intval($filters['superficie_max']) : 500; ?>;
            
            $("#superficie-slider").slider({
                range: true,
                min: 0,
                max: 500,
                step: 10,
                values: [superficieMin, superficieMax],
                slide: function(event, ui) {
                    $("#superficie-range-label").text(ui.values[0] + " m² - " + ui.values[1] + " m²");
                    $("#superficie_min").val(ui.values[0]);
                    $("#superficie_max").val(ui.values[1]);
                }
            });

            // Slider de couchages
            var couchageMin = <?php echo !empty($filters['couchage_min']) ? intval($filters['couchage_min']) : 1; ?>;
            var couchageMax = <?php echo !empty($filters['couchage_max']) ? intval($filters['couchage_max']) : 10; ?>;

            $("#couchage-slider").slider({
                range: true,
                min: 1,
                max: 20,
                step: 1,
                values: [couchageMin, couchageMax],
                slide: function(event, ui) {
                    $("#couchage-range-label").text(ui.values[0] + " - " + ui.values[1]);
                    $("#couchage_min").val(ui.values[0]);
                    $("#couchage_max").val(ui.values[1]);
                }
            });
            
            // Synchroniser les inputs manuels avec les sliders
            $("#prix_min, #prix_max").on('change', function() {
                var min = parseInt($("#prix_min").val()) || 0;
                var max = parseInt($("#prix_max").val()) || 5000;
                if (min > max) {
                    var temp = min;
                    min = max;
                    max = temp;
                    $("#prix_min").val(min);
                    $("#prix_max").val(max);
                }
                $("#prix-slider").slider('values', [min, max]);
                $("#prix-range-label").text(min + " € - " + max + " €");
            });
            
            $("#superficie_min, #superficie_max").on('change', function() {
                var min = parseInt($("#superficie_min").val()) || 0;
                var max = parseInt($("#superficie_max").val()) || 500;
                if (min > max) {
                    var temp = min;
                    min = max;
                    max = temp;
                    $("#superficie_min").val(min);
                    $("#superficie_max").val(max);
                }
                $("#superficie-slider").slider('values', [min, max]);
                $("#superficie-range-label").text(min + " m² - " + max + " m²");
            });

            $("#couchage_min, #couchage_max").on('change', function() {
                var min = parseInt($("#couchage_min").val()) || 1;
                var max = parseInt($("#couchage_max").val()) || 10;
                if (min < 1) min = 1;
                if (max < 1) max = 1;
                if (min > max) {
                    var temp = min;
                    min = max;
                    max = temp;
                    $("#couchage_min").val(min);
                    $("#couchage_max").val(max);
                }
                $("#couchage-slider").slider('values', [min, max]);
                $("#couchage-range-label").text(min + " - " + max);
            });
        });

        // ========== GESTION DES TYPES DE BIEN (MULTI-SÉLECTION) ==========
        const selectedTypesBien = new Set(<?php echo json_encode($filters['types_bien'] ?? []); ?>);
        const hiddenTypesInputsDiv = document.getElementById('hidden-types-inputs');
        
        // Mettre à jour l'affichage des types sélectionnés
        function renderSelectedTypes() {
            hiddenTypesInputsDiv.innerHTML = '';
            
            // Mettre à jour l'apparence des boutons
            document.querySelectorAll('.type-bien-btn').forEach(btn => {
                const typeId = btn.getAttribute('data-type-id');
                if (selectedTypesBien.has(typeId)) {
                    btn.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                    btn.style.color = 'white';
                    btn.style.borderColor = '#667eea';
                } else {
                    btn.style.background = 'white';
                    btn.style.color = '#555';
                    btn.style.borderColor = '#ddd';
                }
            });
            
            // Créer les inputs cachés pour le formulaire
            selectedTypesBien.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'types_bien[]';
                input.value = id;
                hiddenTypesInputsDiv.appendChild(input);
            });
        }
        
        // Toggle un type de bien
        function toggleTypeBien(id) {
            const idStr = id.toString();
            if (selectedTypesBien.has(idStr)) {
                selectedTypesBien.delete(idStr);
            } else {
                selectedTypesBien.add(idStr);
            }
            renderSelectedTypes();
        }
        
        // Initialiser l'affichage
        renderSelectedTypes();

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

        // ========== GESTION DES PRESTATIONS (AUTOCOMPLÉTION) ==========
        const prestationsData = <?php echo json_encode($prestations ?? []); ?>;
        const selectedPrestations = new Set(<?php echo json_encode($filters['prestations'] ?? []); ?>);
        
        const searchInput = document.getElementById('prestation-search');
        const autocompleteDiv = document.getElementById('prestation-autocomplete');
        const selectedDiv = document.getElementById('selected-prestations');
        const hiddenInputsDiv = document.getElementById('hidden-prestations-inputs');
        
        // Afficher les prestations déjà sélectionnées
        function renderSelectedPrestations() {
            selectedDiv.innerHTML = '';
            hiddenInputsDiv.innerHTML = '';
            
            if (selectedPrestations.size === 0) {
                selectedDiv.innerHTML = '<span style="color: #999; font-size: 0.9rem;">Aucune prestation sélectionnée</span>';
                return;
            }
            
            selectedPrestations.forEach(id => {
                const prestation = prestationsData.find(p => p.id_prestation == id);
                if (!prestation) return;
                
                // Badge visuel
                const badge = document.createElement('span');
                badge.style.cssText = 'display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.7rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 16px; font-size: 0.85rem; font-weight: 500;';
                badge.innerHTML = `${prestation.lib_prestation} <button type="button" onclick="removePrestationFilter(${id})" style="background: none; border: none; color: white; cursor: pointer; font-size: 1rem; line-height: 1; padding: 0; margin: 0;">×</button>`;
                selectedDiv.appendChild(badge);
                
                // Input caché pour le formulaire
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'prestations[]';
                input.value = id;
                hiddenInputsDiv.appendChild(input);
            });
        }
        
        // Recherche et autocomplétion
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 2) {
                autocompleteDiv.style.display = 'none';
                return;
            }
            
            const filtered = prestationsData.filter(p => 
                p.lib_prestation.toLowerCase().includes(query) && !selectedPrestations.has(p.id_prestation)
            );
            
            if (filtered.length === 0) {
                autocompleteDiv.innerHTML = '<div style="padding: 0.75rem; color: #999; text-align: center;">Aucune prestation trouvée</div>';
                autocompleteDiv.style.display = 'block';
                return;
            }
            
            let html = '';
            filtered.forEach(prestation => {
                html += `
                    <div onclick="addPrestationFilter(${prestation.id_prestation})" 
                         style="padding: 0.75rem; cursor: pointer; border-bottom: 1px solid #f0f0f0; transition: background 0.2s;"
                         onmouseover="this.style.background='#f8f9ff'"
                         onmouseout="this.style.background='white'">
                        ${prestation.lib_prestation}
                    </div>
                `;
            });
            
            autocompleteDiv.innerHTML = html;
            autocompleteDiv.style.display = 'block';
        });
        
        // Fermer l'autocomplétion en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#prestation-search') && !e.target.closest('#prestation-autocomplete')) {
                autocompleteDiv.style.display = 'none';
            }
        });
        
        // Ajouter une prestation
        function addPrestationFilter(id) {
            selectedPrestations.add(id.toString());
            renderSelectedPrestations();
            searchInput.value = '';
            autocompleteDiv.style.display = 'none';
        }
        
        // Retirer une prestation
        function removePrestationFilter(id) {
            selectedPrestations.delete(id.toString());
            renderSelectedPrestations();
        }
        
        // Initialiser l'affichage
        renderSelectedPrestations();
    </script>

    <script src="/js/autocomplete.js"></script>
    <script src="/js/favoris.js"></script>
</body>
</html>

