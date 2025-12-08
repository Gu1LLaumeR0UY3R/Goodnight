<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Prestations - <?php echo htmlspecialchars($bien["designation_bien"]); ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
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
