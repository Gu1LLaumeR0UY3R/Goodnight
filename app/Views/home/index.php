<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goodnight - Votre plateforme de location</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/home.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/css/favoris.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="/vendor/jquery/jquery-3.6.0.min.js"></script>
    <script src="/vendor/jquery-ui/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="/css/theme.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>        
        <section class="liste-biens">
            <div class="search-section hero-search">
                <p class="eyebrow">Locations entre particuliers</p>
                <h1 class="hero-title">Une bonne nuit,<br><em>où que vous alliez.</em></h1>
                <p class="hero-sub">Trouvez et réservez un logement auprès de propriétaires près de chez vous.</p>
                <form action="/home/search" method="GET" class="search-bar">
                    <div>
                        <input type="text" id="commune_search" name="q" placeholder="Rechercher par région..." value="<?php echo htmlspecialchars($filters['commune'] ?? ''); ?>">
                    </div>
                    <div>
                        <button type="submit" class="btn-search">
                            Rechercher
                        </button>
                        <button id="toggle-filters" type="button" class="btn-filters" title="Afficher les filtres avancés">
                            <svg fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"></path>
                            </svg>
                            <span>Filtres</span>
                        </button>
                    </div>
                </form>

                <!-- Filtres avancés (cachés par défaut) -->
                <div id="filters-panel" class="filters-container" style="display: none;">
                    <h3>Filtres avancés</h3>
                    <form action="/home/search" method="GET">
                        <!-- Commune (hidden pour conserver la recherche) -->
                        <input type="hidden" name="q" value="<?php echo htmlspecialchars($filters['commune'] ?? ''); ?>">
                        
                        <!-- Types de bien (multi-sélection) -->
                        <div style="grid-column: span 2;">
                            <label>Types de bien</label>
                            
                            <!-- Boutons de sélection -->
                            <div>
                                <?php foreach ($typesBiens as $type): ?>
                                    <button type="button" 
                                            class="type-bien-btn" 
                                            data-type-id="<?php echo htmlspecialchars($type['id_typebien']); ?>"
                                            onclick="toggleTypeBien(<?php echo htmlspecialchars($type['id_typebien']); ?>)"
                                           >
                                        <?php echo htmlspecialchars($type['desc_type_bien']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Inputs cachés pour le formulaire -->
                            <div id="hidden-types-inputs"></div>
                        </div>

                        <!-- Prestations avec recherche -->
                        <div style="grid-column: span 2;">
                            <label>Prestations</label>
                            <div style="position: relative;">
                                <input type="text" 
                                       id="prestation-search" 
                                       placeholder="Rechercher une prestation (ex: piscine, wifi...)" 
                                       autocomplete="off"
                                      >
                                <div id="prestation-autocomplete" class="autocomplete-results" style="display: none;"></div>
                            </div>
                            
                            <!-- Badges des prestations sélectionnées -->
                            <div id="selected-prestations"></div>
                            
                            <!-- Inputs cachés pour le formulaire -->
                            <div id="hidden-prestations-inputs"></div>
                        </div>

                        <!-- Slider Couchages -->
                        <div style="grid-column: span 2;">
                            <label>
                                Couchages:
                                <span id="couchage-range-label">
                                    <?php 
                                    $couchageMin = $filters['couchage_min'] ?? 1;
                                    $couchageMax = $filters['couchage_max'] ?? 10;
                                    echo htmlspecialchars($couchageMin) . ' - ' . htmlspecialchars($couchageMax);
                                    ?>
                                </span>
                            </label>
                            <div id="couchage-slider"></div>
                            <div>
                                <div>
                                    <input type="number" name="couchage_min" id="couchage_min" min="1" max="20" step="1" placeholder="Min" value="<?php echo htmlspecialchars($filters['couchage_min'] ?? ''); ?>">
                                </div>
                                <div>
                                    <input type="number" name="couchage_max" id="couchage_max" min="1" max="20" step="1" placeholder="Max" value="<?php echo htmlspecialchars($filters['couchage_max'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Slider Superficie -->
                        <div style="grid-column: span 2;">
                            <label>
                                Superficie (m²): 
                                <span id="superficie-range-label">
                                    <?php 
                                    $superficieMin = $filters['superficie_min'] ?? 0;
                                    $superficieMax = $filters['superficie_max'] ?? 500;
                                    echo htmlspecialchars($superficieMin) . ' m² - ' . htmlspecialchars($superficieMax) . ' m²';
                                    ?>
                                </span>
                            </label>
                            <div id="superficie-slider"></div>
                            <div>
                                <div>
                                    <input type="number" name="superficie_min" id="superficie_min" min="0" max="500" step="10" placeholder="Min" value="<?php echo htmlspecialchars($filters['superficie_min'] ?? ''); ?>">
                                </div>
                                <div>
                                    <input type="number" name="superficie_max" id="superficie_max" min="0" max="500" step="10" placeholder="Max" value="<?php echo htmlspecialchars($filters['superficie_max'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Slider Prix -->
                        <div style="grid-column: span 2;">
                            <label>
                                Prix (€/semaine): 
                                <span id="prix-range-label">
                                    <?php 
                                    $prixMin = $filters['prix_min'] ?? 0;
                                    $prixMax = $filters['prix_max'] ?? 5000;
                                    echo htmlspecialchars($prixMin) . ' € - ' . htmlspecialchars($prixMax) . ' €';
                                    ?>
                                </span>
                            </label>
                            <div id="prix-slider"></div>
                            <div>
                                <div>
                                    <input type="number" name="prix_min" id="prix_min" min="0" max="10000" step="50" placeholder="Min" value="<?php echo htmlspecialchars($filters['prix_min'] ?? ''); ?>">
                                </div>
                                <div>
                                    <input type="number" name="prix_max" id="prix_max" min="0" max="10000" step="50" placeholder="Max" value="<?php echo htmlspecialchars($filters['prix_max'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Animaux acceptés + Tri -->
                        <div>
                            <label>Animaux acceptés</label>
                            <label>
                                <input type="checkbox" name="animaux" value="1" <?php echo (!empty($filters['animaux'])) ? 'checked' : ''; ?>>
                                Oui
                            </label>
                        </div>

                        <div>
                            <label for="tri">Trier par</label>
                            <select name="tri" id="tri">
                                <option value="">Par défaut</option>
                                <option value="prix_asc" <?php echo (($filters['tri'] ?? '') === 'prix_asc') ? 'selected' : ''; ?>>Prix croissant</option>
                                <option value="prix_desc" <?php echo (($filters['tri'] ?? '') === 'prix_desc') ? 'selected' : ''; ?>>Prix décroissant</option>
                                <option value="superficie_asc" <?php echo (($filters['tri'] ?? '') === 'superficie_asc') ? 'selected' : ''; ?>>Superficie croissante</option>
                                <option value="superficie_desc" <?php echo (($filters['tri'] ?? '') === 'superficie_desc') ? 'selected' : ''; ?>>Superficie décroissante</option>
                            </select>
                        </div>

                        <!-- Boutons -->
                        <div>
                            <button type="submit">
                                Appliquer
                            </button>
                            <a href="/home">
                                Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>

                <div class="map-link">
                    <a href="/home/map" class="btn-map">
                        <svg data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
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
                <span class="count">
                    (<?php echo count($biens ?? []); ?> bien<?php echo count($biens ?? []) > 1 ? 's' : ''; ?>)
                </span>
            </h2>
            <div class="biens-grid">
                <?php if (!empty($biens)): ?>
                    <?php foreach ($biens as $bien): ?>
                        <a href="/bien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="bien-card">
                            <div class="bien-image">
                                <img src="<?php echo htmlspecialchars($bien["premiere_photo"] ?? '/images/default.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($bien["designation_bien"]); ?>">
                                <span class="bien-prix-badge"><?php echo htmlspecialchars(($bien["prix_semaine"] ?? null) ? number_format($bien["prix_semaine"] / 7, 0) . '€' : 'N/C'); ?><small>/nuit</small></span>
                                <button class="btn-fav" data-bien-id="<?php echo htmlspecialchars($bien["id_biens"]); ?>" onclick="event.preventDefault();">♥</button>
                            </div>
                            <div class="bien-details">
                                <h3><?php echo htmlspecialchars($bien["designation_bien"]); ?></h3>
                                <p class="bien-lieu"><svg class="pin" viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg> <?php echo htmlspecialchars($bien["commune_nom"]); ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty">Aucun bien ne correspond à votre recherche.</p>
                <?php endif; ?>
            </div>
        </section>

    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Goodnight. Tous droits réservés.</p>
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
                btn.classList.toggle('is-selected', selectedTypesBien.has(typeId));
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

        // Ne pas ouvrir automatiquement les filtres après une recherche

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
                selectedDiv.innerHTML = '<span style="color: #6a6f62; font-size: 0.9rem;">Aucune prestation sélectionnée</span>';
                return;
            }
            
            selectedPrestations.forEach(id => {
                const prestation = prestationsData.find(p => p.id_prestation == id);
                if (!prestation) return;
                
                // Badge visuel
                const badge = document.createElement('span');
                badge.className = 'chip';
                badge.innerHTML = `${prestation.lib_prestation} <button type="button" class="chip-remove" aria-label="Retirer" onclick="removePrestationFilter(${id})">×</button>`;
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
                autocompleteDiv.innerHTML = '<div style="padding: 0.75rem; color: #6a6f62; text-align: center;">Aucune prestation trouvée</div>';
                autocompleteDiv.style.display = 'block';
                return;
            }
            
            let html = '';
            filtered.forEach(prestation => {
                html += `
                    <div class="autocomplete-item" onclick="addPrestationFilter(${prestation.id_prestation})">
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

