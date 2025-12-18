<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Prestations - <?php echo htmlspecialchars($bien["designation_bien"]); ?></title>
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

        .prestations-container {
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

        /* Info du bien */
        .bien-info {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .bien-info {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .bien-info h3 {
            margin: 0 0 0.75rem 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-primary, #ff5a5f);
        }

        .bien-info p {
            margin: 0;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Message de succès */
        .success-message {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border: 2px solid #4caf50;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 2rem;
            color: #2e7d32;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
        }

        /* Prestations actuelles */
        .prestations-actuelles {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .prestations-actuelles {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .prestations-actuelles h3 {
            margin: 0 0 1.5rem 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--accent-primary, #ff5a5f);
        }

        .prestations-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .prestation-badge {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f5f5f5, #eeeeee);
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            padding: 1rem 1.25rem;
            transition: all 0.2s;
        }

        .dark-mode .prestation-badge {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
            border-color: var(--border-color);
        }

        .prestation-badge:hover {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border-color: var(--accent-primary, #ff5a5f);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 4px 12px rgba(255, 90, 95, 0.3);
        }

        .prestation-badge-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .prestation-badge-qty {
            background: white;
            color: var(--accent-primary, #ff5a5f);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .prestation-badge:hover .prestation-badge-qty {
            background: rgba(255, 255, 255, 0.95);
        }

        .empty-prestations {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
            font-style: italic;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 8px;
            border: 2px dashed var(--border-color, #e0e0e0);
            margin-bottom: 1.5rem;
        }

        .dark-mode .empty-prestations {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Boutons */
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

        /* Recherche */
        .search-container {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.75rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .search-container {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .search-container h3 {
            margin: 0 0 1.5rem 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            padding-bottom: 0.75rem;
            border-bottom: 3px solid var(--accent-primary, #ff5a5f);
        }

        .search-box {
            position: relative;
            margin-bottom: 1rem;
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
        }

        .dark-mode .search-input {
            background: var(--bg-primary);
            border-color: var(--border-color);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent-primary, #ff5a5f);
            box-shadow: 0 0 0 3px rgba(255, 90, 95, 0.1);
        }

        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            margin-top: 0.5rem;
            max-height: 400px;
            overflow-y: auto;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            z-index: 100;
            display: none;
        }

        .dark-mode .autocomplete-results {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .autocomplete-results.show {
            display: block;
        }

        .autocomplete-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color, #e0e0e0);
            transition: background 0.2s;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover {
            background: rgba(255, 90, 95, 0.05);
        }

        .autocomplete-item-name {
            font-weight: 600;
            color: var(--text-primary);
            flex: 1;
        }

        .autocomplete-item-added {
            color: #4caf50;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .autocomplete-item-add {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .autocomplete-item-add:hover {
            background: linear-gradient(135deg, var(--accent-hover, #ff7f83), #ff9999);
            transform: scale(1.05);
        }

        .no-results {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-secondary);
            font-style: italic;
        }

        /* Panel d'ajout rapide */
        .quick-add-panel {
            display: none;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 2px solid #2196f3;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);
        }

        .quick-add-panel.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .quick-add-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .quick-add-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0d47a1;
        }

        .quantity-selector {
            margin-bottom: 1.25rem;
        }

        .quantity-selector label {
            display: block;
            font-weight: 600;
            color: #0d47a1;
            margin-bottom: 0.5rem;
        }

        .quantity-selector input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #2196f3;
            border-radius: 8px;
            font-size: 1rem;
            background: white;
        }

        .quick-add-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-confirm-add {
            flex: 1;
            padding: 0.875rem;
            background: linear-gradient(135deg, #4caf50, #66bb6a);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-confirm-add:hover {
            background: linear-gradient(135deg, #66bb6a, #81c784);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        .btn-cancel-add {
            padding: 0.875rem 1.5rem;
            background: white;
            color: var(--text-primary);
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel-add:hover {
            background: #f5f5f5;
            border-color: var(--accent-primary, #ff5a5f);
            color: var(--accent-primary, #ff5a5f);
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

        /* Formulaire d'édition des prestations */
        .edit-prestations-container {
            display: none;
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            border: 2px solid #ff9800;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            box-shadow: 0 4px 12px rgba(255, 152, 0, 0.2);
        }

        .edit-prestations-container.show {
            display: block;
            animation: slideIn 0.3s ease;
        }

        .edit-prestations-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .edit-prestations-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #e65100;
        }

        .edit-prestations-list {
            display: grid;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .edit-prestation-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: white;
            border: 2px solid #ffb74d;
            border-radius: 10px;
            padding: 1rem;
            transition: all 0.2s;
        }

        .edit-prestation-item:hover {
            border-color: #ff9800;
            box-shadow: 0 2px 8px rgba(255, 152, 0, 0.2);
        }

        .edit-prestation-name {
            flex: 1;
            font-weight: 600;
            color: var(--text-primary);
        }

        .edit-prestation-controls {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f5f5f5;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 8px;
            padding: 0.25rem;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid var(--border-color, #e0e0e0);
            border-radius: 6px;
            font-weight: 700;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--text-primary);
        }

        .quantity-btn:hover {
            background: var(--accent-primary, #ff5a5f);
            color: white;
            border-color: var(--accent-primary, #ff5a5f);
        }

        .quantity-value {
            min-width: 40px;
            text-align: center;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text-primary);
        }

        .btn-delete-prestation {
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #f44336, #e57373);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-delete-prestation:hover {
            background: linear-gradient(135deg, #e53935, #ef5350);
            transform: scale(1.05);
        }

        .edit-prestations-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-save-prestations {
            flex: 1;
            padding: 0.875rem;
            background: linear-gradient(135deg, #4caf50, #66bb6a);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-save-prestations:hover {
            background: linear-gradient(135deg, #66bb6a, #81c784);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            .page-header h2 {
                font-size: 1.75rem;
            }

            .prestations-list {
                grid-template-columns: 1fr;
            }

            .quick-add-actions {
                flex-direction: column;
            }

            .bien-info {
                padding: 1.25rem;
            }

            .edit-prestation-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .edit-prestation-controls {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="prestations-container">
            <div class="page-header">
                <h2>Gérer les Prestations</h2>
            </div>

            <div class="bien-info">
                <h3><?php echo htmlspecialchars($bien["designation_bien"]); ?></h3>
                <p><?php echo htmlspecialchars($bien["description_biens"]); ?></p>
            </div>

            <?php if (isset($success)): ?>
                <div class="success-message">
                    Les prestations ont été mises à jour avec succès !
                </div>
            <?php endif; ?>

            <div class="prestations-actuelles">
                <h3>Prestations actuelles du bien</h3>
                <?php 
                // Debug
                // echo "<pre>DEBUG: "; print_r($prestations); echo "</pre>";
                $prestationsActives = array_filter($prestations, function($p) {
                    return isset($p["selected"]) && $p["selected"];
                });
                ?>
                <?php if (!empty($prestationsActives)): ?>
                    <div class="prestations-list">
                        <?php foreach ($prestationsActives as $prestation): ?>
                            <div class="prestation-badge">
                                <span class="prestation-badge-name"><?php echo htmlspecialchars($prestation["lib_prestation"]); ?></span>
                                <span class="prestation-badge-qty">x<?php echo htmlspecialchars($prestation["quantite_prestation"] ?? 1); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="btn-editer-prestations" onclick="toggleFormPrestations()">
                        ✏️ Éditer les prestations
                    </button>

                    <!-- Formulaire d'édition -->
                    <div class="edit-prestations-container" id="editPrestationsContainer">
                        <div class="edit-prestations-header">
                            <span class="edit-prestations-title">🔧 Modifier les prestations</span>
                            <button type="button" class="btn-cancel-add" onclick="toggleFormPrestations()">✕</button>
                        </div>
                        <div class="edit-prestations-list" id="editPrestationsList">
                            <?php foreach ($prestationsActives as $prestation): ?>
                                <div class="edit-prestation-item" data-id="<?php echo htmlspecialchars($prestation['id_prestation']); ?>">
                                    <span class="edit-prestation-name"><?php echo htmlspecialchars($prestation['lib_prestation']); ?></span>
                                    <div class="edit-prestation-controls">
                                        <div class="quantity-control">
                                            <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo htmlspecialchars($prestation['id_prestation']); ?>, -1)">−</button>
                                            <span class="quantity-value" id="qty-<?php echo htmlspecialchars($prestation['id_prestation']); ?>"><?php echo htmlspecialchars($prestation['quantite_prestation'] ?? 1); ?></span>
                                            <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo htmlspecialchars($prestation['id_prestation']); ?>, 1)">+</button>
                                        </div>
                                        <button type="button" class="btn-delete-prestation" onclick="deletePrestation(<?php echo htmlspecialchars($prestation['id_prestation']); ?>)">🗑️ Supprimer</button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="edit-prestations-actions">
                            <button type="button" class="btn-save-prestations" onclick="saveChanges()">✔️ Enregistrer les modifications</button>
                            <button type="button" class="btn-cancel-add" onclick="toggleFormPrestations()">Annuler</button>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="empty-prestations">Aucune prestation n'est actuellement associée à ce bien.</p>
                <?php endif; ?>
            </div>

            <div class="search-container">
                <h3>Rechercher et ajouter une prestation</h3>
                <div class="search-box">
                    <input type="text" 
                           class="search-input" 
                           id="searchPrestation" 
                           placeholder="Rechercher une prestation (ex: piscine, wifi, parking...)"
                           autocomplete="off">
                    <div class="autocomplete-results" id="autocompleteResults"></div>
                </div>
                <a href="/proprietaire/prestationsList" class="btn-editer-prestations" style="margin-top: 0.5rem; display: inline-flex; text-decoration: none;">
                    📋 Voir la liste complète
                </a>
                <div class="quick-add-panel" id="quickAddPanel">
                    <div class="quick-add-header">
                        <span class="quick-add-title" id="quickAddTitle"></span>
                        <button type="button" class="btn-cancel-add" onclick="cancelQuickAdd()">✕</button>
                    </div>
                    <div class="quantity-selector">
                        <label for="quickAddQuantity">Quantité :</label>
                        <input type="number" id="quickAddQuantity" value="1" min="1">
                    </div>
                    <div class="quick-add-actions">
                        <button type="button" class="btn-confirm-add" onclick="confirmQuickAdd()">Ajouter</button>
                        <button type="button" class="btn-cancel-add" onclick="cancelQuickAdd()">Annuler</button>
                    </div>
                </div>
            </div>

            <form action="/proprietaire/managePrestations/<?php echo htmlspecialchars($bien["id_biens"]); ?>" method="POST" id="formPrestations" style="display: none;">
                <?php if (!empty($prestations)): ?>
                    <?php foreach ($prestations as $prestation): ?>
                        <input type="checkbox" 
                               class="checkbox-prestation" 
                               name="prestations[<?php echo htmlspecialchars($prestation["id_prestation"]); ?>][active]" 
                               value="1"
                               id="prestation_<?php echo htmlspecialchars($prestation["id_prestation"]); ?>"
                               <?php echo isset($prestation["selected"]) && $prestation["selected"] ? "checked" : ""; ?>>
                        <input type="number" 
                               class="quantity-input" 
                               name="prestations[<?php echo htmlspecialchars($prestation["id_prestation"]); ?>][quantite]" 
                               id="quantity_<?php echo htmlspecialchars($prestation["id_prestation"]); ?>"
                               value="<?php echo htmlspecialchars($prestation["quantite_prestation"] ?? 1); ?>"
                               min="1">
                    <?php endforeach; ?>
                <?php endif; ?>
            </form>
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
        // Données des prestations depuis PHP
        const prestations = <?php echo json_encode($prestations); ?>;
        let selectedPrestationId = null;



        // Recherche et autocomplétion
        const searchInput = document.getElementById('searchPrestation');
        const autocompleteResults = document.getElementById('autocompleteResults');

        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 2) {
                autocompleteResults.classList.remove('show');
                return;
            }

            const filtered = prestations.filter(p => 
                p.lib_prestation.toLowerCase().includes(query)
            );

            if (filtered.length === 0) {
                autocompleteResults.innerHTML = '<div class="no-results">Aucune prestation trouvée</div>';
                autocompleteResults.classList.add('show');
                return;
            }

            let html = '';
            filtered.forEach(prestation => {
                const isSelected = prestation.selected == 1;
                html += `
                    <div class="autocomplete-item" data-id="${prestation.id_prestation}">
                        <span class="autocomplete-item-name">${prestation.lib_prestation}</span>
                        ${isSelected 
                            ? `<span class="autocomplete-item-added">✓ Déjà ajoutée (x${prestation.quantite_prestation || 1})</span>`
                            : `<button type="button" class="autocomplete-item-add" onclick="quickAddPrestation(${prestation.id_prestation})">Ajouter</button>`
                        }
                    </div>
                `;
            });

            autocompleteResults.innerHTML = html;
            autocompleteResults.classList.add('show');
        });

        // Fermer l'autocomplétion en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-box')) {
                autocompleteResults.classList.remove('show');
            }
        });

        function quickAddPrestation(prestationId) {
            selectedPrestationId = prestationId;
            const prestation = prestations.find(p => p.id_prestation == prestationId);
            
            document.getElementById('quickAddTitle').textContent = `Ajouter : ${prestation.lib_prestation}`;
            document.getElementById('quickAddQuantity').value = 1;
            document.getElementById('quickAddPanel').classList.add('show');
            autocompleteResults.classList.remove('show');
            searchInput.value = '';
        }

        function cancelQuickAdd() {
            document.getElementById('quickAddPanel').classList.remove('show');
            selectedPrestationId = null;
        }

        function confirmQuickAdd() {
            if (!selectedPrestationId) return;
            
            const quantity = parseInt(document.getElementById('quickAddQuantity').value);
            if (quantity < 1) {
                alert('La quantité doit être au moins 1');
                return;
            }

            // Cocher la checkbox correspondante dans le formulaire complet
            const checkbox = document.getElementById('prestation_' + selectedPrestationId);
            const quantityInput = document.getElementById('quantity_' + selectedPrestationId);
            
            if (checkbox && quantityInput) {
                checkbox.checked = true;
                quantityInput.value = quantity;
                quantityInput.disabled = false;
            }

            // Soumettre le formulaire
            document.getElementById('formPrestations').submit();
        }

        function toggleFormPrestations() {
            const container = document.getElementById('editPrestationsContainer');
            container.classList.toggle('show');
        }

        // Mettre à jour la quantité
        function updateQuantity(prestationId, delta) {
            const qtyElement = document.getElementById('qty-' + prestationId);
            let currentQty = parseInt(qtyElement.textContent);
            let newQty = currentQty + delta;
            
            // La quantité minimum est 1
            if (newQty < 1) {
                newQty = 1;
            }
            
            qtyElement.textContent = newQty;
            
            // Mettre à jour le champ caché du formulaire
            const quantityInput = document.getElementById('quantity_' + prestationId);
            if (quantityInput) {
                quantityInput.value = newQty;
            }
        }

        // Supprimer une prestation
        function deletePrestation(prestationId) {
            if (!confirm('Voulez-vous vraiment supprimer cette prestation ?')) {
                return;
            }
            
            // Décocher la checkbox correspondante
            const checkbox = document.getElementById('prestation_' + prestationId);
            if (checkbox) {
                checkbox.checked = false;
            }
            
            // Masquer visuellement l'élément
            const item = document.querySelector(`.edit-prestation-item[data-id="${prestationId}"]`);
            if (item) {
                item.style.opacity = '0';
                item.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    item.remove();
                    
                    // Vérifier s'il reste des prestations
                    const remainingItems = document.querySelectorAll('.edit-prestation-item');
                    if (remainingItems.length === 0) {
                        document.getElementById('editPrestationsList').innerHTML = '<p style="text-align: center; color: var(--text-secondary); padding: 1rem;">Toutes les prestations ont été supprimées</p>';
                    }
                }, 200);
            }
        }

        // Sauvegarder les modifications
        function saveChanges() {
            // Soumettre le formulaire
            document.getElementById('formPrestations').submit();
        }

    </script>
</body>
</html>
