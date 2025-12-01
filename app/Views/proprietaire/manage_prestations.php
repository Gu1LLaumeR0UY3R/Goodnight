<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Prestations - <?php echo htmlspecialchars($bien["designation_bien"]); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
        .prestations-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .bien-info {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .prestation-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 1rem;
            background: white;
        }
        
        .prestation-info {
            flex: 1;
        }
        
        .prestation-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .quantity-input {
            width: 80px;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }
        
        .checkbox-prestation {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        
        .prestation-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .prestation-description {
            font-size: 0.9rem;
            color: #666;
        }
        
        .actions-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-save {
            background: #28a745;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn-save:hover {
            background: #218838;
        }
        
        .btn-cancel {
            background: #6c757d;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #c3e6cb;
        }

        .prestations-actuelles {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border: 1px solid #ddd;
        }

        .prestations-actuelles h3 {
            margin-top: 0;
            margin-bottom: 1rem;
            color: #333;
        }

        .prestations-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
        }

        .prestation-badge {
            background: #e9ecef;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.95rem;
        }

        .prestation-badge-name {
            font-weight: 500;
        }

        .prestation-badge-qty {
            background: #6c757d;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            margin-left: 0.5rem;
        }

        .empty-prestations {
            color: #6c757d;
            font-style: italic;
        }

        .btn-editer-prestations {
            background: #007bff;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            margin-top: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-editer-prestations:hover {
            background: #0056b3;
        }



        .search-container {
            margin-bottom: 2rem;
        }

        .search-box {
            position: relative;
            max-width: 500px;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: #007bff;
        }

        .autocomplete-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 8px 8px;
            max-height: 300px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .autocomplete-results.show {
            display: block;
        }

        .autocomplete-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .autocomplete-item:hover {
            background-color: #f8f9fa;
        }

        .autocomplete-item.selected {
            background-color: #e9ecef;
        }

        .autocomplete-item-name {
            font-weight: 500;
        }

        .autocomplete-item-add {
            background: #28a745;
            color: white;
            padding: 0.3rem 0.75rem;
            border-radius: 4px;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
        }

        .autocomplete-item-add:hover {
            background: #218838;
        }

        .autocomplete-item-added {
            background: #6c757d;
            color: white;
            padding: 0.3rem 0.75rem;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .no-results {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }

        .quick-add-panel {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            display: none;
        }

        .quick-add-panel.show {
            display: block;
        }

        .quick-add-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .quick-add-title {
            font-weight: 600;
            font-size: 1rem;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-selector label {
            font-weight: 500;
        }

        .quantity-selector input {
            width: 70px;
            padding: 0.4rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-align: center;
        }

        .quick-add-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-confirm-add {
            background: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-confirm-add:hover {
            background: #218838;
        }

        .btn-cancel-add {
            background: #6c757d;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-cancel-add:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
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


    </script>
</body>
</html>
