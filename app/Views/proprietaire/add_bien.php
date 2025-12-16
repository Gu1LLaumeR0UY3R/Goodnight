<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bien - Propriétaire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/photo-upload.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Formulaire */
        .add-bien-form {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .add-bien-form {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        /* Fieldsets */
        .form-section {
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            background: rgba(0, 0, 0, 0.02);
        }

        .dark-mode .form-section {
            background: rgba(255, 255, 255, 0.02);
            border-color: var(--border-color);
        }

        .form-section legend {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            padding: 0 0.75rem;
        }

        /* Form groups */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* Labels et inputs */
        label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.9375rem;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: white;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .dark-mode input[type="text"],
        .dark-mode input[type="number"],
        .dark-mode input[type="email"],
        .dark-mode select,
        .dark-mode textarea {
            background: var(--bg-primary);
            border-color: var(--border-color);
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent-primary, #ff5a5f);
            box-shadow: 0 0 0 3px rgba(255, 90, 95, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--accent-primary, #ff5a5f);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .checkbox-group input {
            width: auto;
        }

        .checkbox-group label {
            margin: 0;
        }

        /* Accordéon pour les tarifs */
        .accordion-container {
            margin: 1.5rem 0;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .dark-mode .accordion-container {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .accordion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            transition: all 0.2s;
        }

        .dark-mode .accordion-header {
            background: linear-gradient(135deg, var(--night-stellar), var(--night-nebula));
        }

        .accordion-header:hover {
            background: linear-gradient(135deg, var(--accent-hover, #ff7f83), #ff9999);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 90, 95, 0.3);
        }

        .accordion-title {
            font-size: 1.125rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .accordion-icon {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .accordion-header.active .accordion-icon {
            transform: rotate(180deg);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }

        .accordion-content.active {
            max-height: 2000px;
        }

        .tarifs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .tarif-group {
            background: rgba(0, 0, 0, 0.02);
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            padding: 1.25rem;
            transition: all 0.2s;
        }

        .dark-mode .tarif-group {
            background: rgba(255, 255, 255, 0.02);
            border-color: var(--border-color);
        }

        .tarif-group:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--accent-primary, #ff5a5f);
        }

        .tarif-group h4 {
            margin: 0 0 1rem 0;
            color: var(--accent-primary, #ff5a5f);
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tarif-group .form-group {
            margin-bottom: 0;
        }

        /* Zone de photos avec drag and drop */
        .photo-drop-zone {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border: 3px dashed #4caf50;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .photo-drop-zone:hover {
            background: linear-gradient(135deg, #c8e6c9, #a5d6a7);
            border-color: #43a047;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
        }

        .photo-drop-zone.dragover {
            background: linear-gradient(135deg, #a5d6a7, #81c784);
            border-color: #2e7d32;
            border-width: 4px;
        }

        .drop-zone-text {
            color: #2e7d32;
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }

        .drop-zone-text strong {
            display: block;
            font-size: 1.375rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .photo-drop-zone input[type="file"] {
            display: none;
        }

        .photo-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .photo-preview-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid var(--border-color, #e0e0e0);
            background: var(--bg-secondary, #fff);
        }

        .photo-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: rgba(239, 68, 68, 0.95);
            color: #fff;
            font-size: 20px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: transform 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
            z-index: 2;
        }

        .photo-remove-btn:hover {
            background: #DC2626;
            transform: scale(1.06);
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        }

        .photo-file-name {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            padding: 6px 10px;
            font-size: 12px;
            background: linear-gradient(to top, rgba(0,0,0,0.55), rgba(0,0,0,0));
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Bouton de soumission */
        .submit-button {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 2rem;
        }

        .submit-button:hover {
            background: linear-gradient(135deg, var(--accent-hover, #ff7f83), #ff9999);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 90, 95, 0.4);
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

            .form-title {
                font-size: 1.5rem;
            }

            .add-bien-form {
                padding: 1.5rem;
            }

            .tarifs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2 class="form-title">Ajouter un nouveau Bien</h2>
        <form action="/proprietaire/addBien" method="POST" enctype="multipart/form-data" class="add-bien-form">
            
            <fieldset class="form-section">
                <legend>Informations Générales</legend>
                <div class="form-group">
                    <label for="designation_bien">Désignation du bien :</label>
                    <input type="text" id="designation_bien" name="designation_bien" required>
                </div>
                <div class="form-group">
                    <label for="id_TypeBien">Type de bien :</label>
                    <select id="id_TypeBien" name="id_TypeBien" required>
                        <?php foreach ($typesBiens as $type): ?>
                            <option value="<?php echo htmlspecialchars($type["id_typebien"]); ?>">
                                <?php echo htmlspecialchars($type["desc_type_bien"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="superficie_biens">Superficie (m²) :</label>
                    <input type="number" id="superficie_biens" name="superficie_biens" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="nb_couchage">Nombre de couchages :</label>
                    <input type="number" id="nb_couchage" name="nb_couchage" required>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="animaux_biens" name="animaux_biens" value="1">
                    <label for="animaux_biens">Animaux acceptés</label>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Localisation</legend>
                <div class="form-group">
                    <label for="rue_biens">Rue :</label>
                    <input type="text" id="rue_biens" name="rue_biens" required>
                </div>
                <div class="form-group">
                    <label for="complement_biens">Complément d'adresse :</label>
                    <input type="text" id="complement_biens" name="complement_biens">
                </div>
                <div class="form-group">
                    <label for="id_commune">Commune :</label>
                    <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>" placeholder="Commencez à taper le nom de la commune...">
                    <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Description</legend>
                <div class="form-group full-width">
                    <label for="description_biens">Description détaillée :</label>
                    <textarea id="description_biens" name="description_biens" rows="5"></textarea>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Tarification (Prix par jour)</legend>
                <div class="accordion-container">
                    <div class="accordion-header" onclick="toggleTarifs()">
                        <div class="accordion-title">
                            💰 Gérer les tarifs par saison (<?php echo count($saisons); ?> saisons)
                        </div>
                        <span class="accordion-icon" id="accordion-icon">▼</span>
                    </div>
                    <div class="accordion-content" id="accordion-content-tarifs">
                        <div id="tarifs-container" class="tarifs-grid">
                            <?php foreach ($saisons as $saison): ?>
                                <div class="tarif-group">
                                    <h4>📅 <?php echo htmlspecialchars($saison["lib_saison"]); ?></h4>
                                    <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][id_saison]" value="<?php echo htmlspecialchars($saison["id_saison"]); ?>">
                                    
                                    <div class="form-group">
                                        <label for="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>">Prix par semaine (€) :</label>
                                        <input type="number" id="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][prix_semaine]" step="0.01" min="0" placeholder="Ex: 500.00">
                                    </div>
                                    <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][annee]" value="<?php echo date('Y'); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Prestations et Équipements</legend>
                <div class="accordion-container">
                    <div class="accordion-header" onclick="togglePrestations()">
                        <div class="accordion-title">
                            ✨ Gérer les prestations et équipements (<?php echo count($prestations); ?> disponibles)
                        </div>
                        <span class="accordion-icon" id="accordion-icon-prestations">▼</span>
                    </div>
                    <div class="accordion-content" id="accordion-content-prestations">
                        <!-- Barre de recherche avec autocomplétion -->
                        <div style="margin-bottom: 1.5rem; position: relative;">
                            <label for="prestation-search" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Ajouter une prestation :</label>
                            <div style="position: relative;">
                                <input type="text" 
                                       id="prestation-search" 
                                       placeholder="Tapez le nom d'une prestation (ex: piscine, wifi...)" 
                                       autocomplete="off"
                                       style="width: 100%; padding: 0.875rem; border: 2px solid var(--border-color, #e0e0e0); border-radius: 8px; font-size: 1rem; font-family: inherit; background: white; color: var(--text-primary); transition: all 0.2s;">
                                <div id="prestation-autocomplete" 
                                     style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 2px solid var(--border-color, #e0e0e0); border-top: none; border-radius: 0 0 8px 8px; max-height: 250px; overflow-y: auto; display: none; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-top: -2px;">
                                </div>
                            </div>
                        </div>

                        <!-- Prestations sélectionnées -->
                        <div id="prestations-selected" style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-top: 1.5rem; min-height: 50px; align-content: flex-start;">
                            <!-- Les prestations sélectionnées s'ajouteront dynamiquement ici -->
                        </div>

                        <!-- Input hidden pour stocker les données finales -->
                        <input type="hidden" id="prestations-data" name="prestations-data" value="{}">
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Photos du Bien</legend>
                <div class="form-group full-width">
                    <div class="photo-drop-zone">
                        <div class="drop-zone-text">
                            <strong>Glissez-déposez vos photos ici</strong><br>
                            ou cliquez pour sélectionner des fichiers
                        </div>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                        <div class="photo-preview-container"></div>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="submit-button">Ajouter le bien</button>
        </form>
    </main>

    <footer>
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
    <script src="/js/photo-upload.js"></script>
    <script>
        // Fonction pour ouvrir/fermer l'accordéon des tarifs
        function toggleTarifs() {
            const header = document.querySelector('.accordion-header');
            const content = document.getElementById('accordion-content-tarifs');
            const icon = document.getElementById('accordion-icon');
            
            header.classList.toggle('active');
            content.classList.toggle('active');
        }

        // Fonction pour ouvrir/fermer l'accordéon des prestations
        function togglePrestations() {
            const header = document.querySelectorAll('.accordion-header')[1]; // Deuxième accordéon
            const content = document.getElementById('accordion-content-prestations');
            const icon = document.getElementById('accordion-icon-prestations');
            
            header.classList.toggle('active');
            content.classList.toggle('active');
        }

        // ========== GESTION DES PRESTATIONS (AUTOCOMPLÉTION) ==========
        const prestationsData = <?php echo json_encode($prestations ?? []); ?>;
        const selectedPrestations = new Map(); // Map pour stocker {id -> {nom, quantite}}
        
        const searchInput = document.getElementById('prestation-search');
        const autocompleteDiv = document.getElementById('prestation-autocomplete');
        const selectedDiv = document.getElementById('prestations-selected');
        const hiddenInput = document.getElementById('prestations-data');
        
        // Écouter les changements dans le champ de recherche
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 1) {
                autocompleteDiv.style.display = 'none';
                return;
            }
            
            // Filtrer les prestations qui ne sont pas déjà sélectionnées
            const filtered = prestationsData.filter(p => 
                p.lib_prestation.toLowerCase().includes(query) && !selectedPrestations.has(p.id_prestation.toString())
            );
            
            if (filtered.length === 0) {
                autocompleteDiv.innerHTML = '<div style="padding: 0.875rem; color: #999; text-align: center;">Aucune prestation trouvée</div>';
                autocompleteDiv.style.display = 'block';
                return;
            }
            
            let html = '';
            filtered.forEach(prestation => {
                html += `
                    <div onclick="addPrestationToForm(${prestation.id_prestation}, '${prestation.lib_prestation.replace(/'/g, "\\'")}', event)" 
                         style="padding: 0.875rem; cursor: pointer; border-bottom: 1px solid #f0f0f0; transition: background 0.2s; font-size: 0.95rem;"
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
        
        // Ajouter une prestation au formulaire
        function addPrestationToForm(id, nom, event) {
            event.preventDefault();
            const idStr = id.toString();
            
            // Vérifier si la prestation n'est pas déjà sélectionnée
            if (!selectedPrestations.has(idStr)) {
                selectedPrestations.set(idStr, { nom: nom, quantite: 1 });
                renderSelectedPrestations();
                searchInput.value = '';
                autocompleteDiv.style.display = 'none';
            }
        }
        
        // Augmenter la quantité
        function increasePrestationQty(id) {
            const idStr = id.toString();
            if (selectedPrestations.has(idStr)) {
                const prestation = selectedPrestations.get(idStr);
                prestation.quantite += 1;
                renderSelectedPrestations();
            }
        }
        
        // Diminuer la quantité
        function decreasePrestationQty(id) {
            const idStr = id.toString();
            if (selectedPrestations.has(idStr)) {
                const prestation = selectedPrestations.get(idStr);
                if (prestation.quantite > 1) {
                    prestation.quantite -= 1;
                    renderSelectedPrestations();
                }
            }
        }
        
        // Supprimer une prestation
        function removePrestationFromForm(id) {
            const idStr = id.toString();
            selectedPrestations.delete(idStr);
            renderSelectedPrestations();
        }
        
        // Afficher les prestations sélectionnées
        function renderSelectedPrestations() {
            selectedDiv.innerHTML = '';
            
            if (selectedPrestations.size === 0) {
                selectedDiv.innerHTML = '<p style="color: #999; font-size: 0.9rem;">Aucune prestation sélectionnée</p>';
            } else {
                selectedPrestations.forEach((prestation, id) => {
                    const badge = document.createElement('div');
                    badge.style.cssText = `
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 0.75rem 1rem;
                        border-radius: 20px;
                        font-weight: 500;
                        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
                    `;
                    
                    badge.innerHTML = `
                        <span>${prestation.nom}</span>
                        <div style="display: flex; align-items: center; gap: 0.5rem; background: rgba(255, 255, 255, 0.2); padding: 0.25rem 0.5rem; border-radius: 12px;">
                            <button type="button" onclick="decreasePrestationQty(${id})" style="background: rgba(255, 255, 255, 0.3); border: none; color: white; width: 24px; height: 24px; border-radius: 50%; cursor: pointer; font-weight: bold; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255, 255, 255, 0.5)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.3)'">−</button>
                            <span style="min-width: 20px; text-align: center; font-weight: bold;">${prestation.quantite}</span>
                            <button type="button" onclick="increasePrestationQty(${id})" style="background: rgba(255, 255, 255, 0.3); border: none; color: white; width: 24px; height: 24px; border-radius: 50%; cursor: pointer; font-weight: bold; display: flex; align-items: center; justify-content: center; transition: background 0.2s;" onmouseover="this.style.background='rgba(255, 255, 255, 0.5)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.3)'">+</button>
                        </div>
                        <button type="button" onclick="removePrestationFromForm(${id})" style="background: rgba(255, 255, 255, 0.2); border: none; color: white; width: 24px; height: 24px; border-radius: 50%; cursor: pointer; font-weight: bold; display: flex; align-items: center; justify-content: center; transition: background 0.2s; margin-left: 0.5rem;" onmouseover="this.style.background='rgba(255, 68, 68, 0.8)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.2)'">×</button>
                    `;
                    
                    selectedDiv.appendChild(badge);
                });
            }
            
            // Mettre à jour le champ hidden avec les données
            updateHiddenPrestationsData();
        }
        
        // Mettre à jour le champ hidden avec les données sérialisées
        function updateHiddenPrestationsData() {
            const data = {};
            selectedPrestations.forEach((prestation, id) => {
                data[id] = prestation.quantite;
            });
            hiddenInput.value = JSON.stringify(data);
        }
        
        // Initialiser l'affichage
        renderSelectedPrestations();
    </script>
</body>
</html>
