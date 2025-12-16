/**
 * GlobeNight - Gestion des Prestations
 * Système d'autocomplétion avec interface améliorée
 */

(function() {
    'use strict';

    // Vérifier que le DOM est prêt
    function initPrestations() {
        const searchInput = document.getElementById('prestation-search');
        const autocompleteDiv = document.getElementById('prestation-autocomplete');
        const selectedDiv = document.getElementById('prestations-selected');
        const hiddenInput = document.getElementById('prestations-data');

        if (!searchInput || !selectedDiv) return;

        const prestationsData = window.prestationsData || [];
        const selectedPrestations = new Map();

        // Écouter les changements dans le champ de recherche
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();

            if (query.length < 1) {
                autocompleteDiv.style.display = 'none';
                return;
            }

            const filtered = prestationsData.filter(p =>
                p.lib_prestation.toLowerCase().includes(query) && !selectedPrestations.has(p.id_prestation.toString())
            );

            if (filtered.length === 0) {
                autocompleteDiv.innerHTML = '<div style="padding: 1rem; color: var(--text-muted); text-align: center; font-size: 0.95rem;">Aucune prestation trouvée</div>';
                autocompleteDiv.style.display = 'block';
                return;
            }

            let html = '';
            filtered.forEach(prestation => {
                html += `
                    <div onclick="window.addPrestationToForm(${prestation.id_prestation}, '${prestation.lib_prestation.replace(/'/g, "\\'")}', event)"
                         style="padding: 1rem; cursor: pointer; border-bottom: 1px solid var(--border-color); transition: background 0.2s; font-size: 0.95rem; display: flex; align-items: center; gap: 0.75rem;"
                         onmouseover="this.style.background='rgba(255, 90, 95, 0.08)'"
                         onmouseout="this.style.background='transparent'">
                        <span style="font-size: 1.3rem;">✨</span>
                        <span style="flex: 1;">${prestation.lib_prestation}</span>
                        <span style="color: var(--text-muted); font-size: 0.85rem;">+</span>
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
        window.addPrestationToForm = function(id, nom, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const idStr = id.toString();

            if (!selectedPrestations.has(idStr)) {
                selectedPrestations.set(idStr, { nom: nom, quantite: 1 });
                renderSelectedPrestations();
                searchInput.value = '';
                autocompleteDiv.style.display = 'none';
            }
        };

        // Augmenter la quantité
        window.increasePrestationQty = function(id) {
            const idStr = id.toString();
            if (selectedPrestations.has(idStr)) {
                const prestation = selectedPrestations.get(idStr);
                prestation.quantite += 1;
                renderSelectedPrestations();
            }
        };

        // Diminuer la quantité
        window.decreasePrestationQty = function(id) {
            const idStr = id.toString();
            if (selectedPrestations.has(idStr)) {
                const prestation = selectedPrestations.get(idStr);
                if (prestation.quantite > 1) {
                    prestation.quantite -= 1;
                    renderSelectedPrestations();
                }
            }
        };

        // Supprimer une prestation
        window.removePrestationFromForm = function(id) {
            const idStr = id.toString();
            selectedPrestations.delete(idStr);
            renderSelectedPrestations();
        };

        // Afficher les prestations sélectionnées
        function renderSelectedPrestations() {
            selectedDiv.innerHTML = '';

            if (selectedPrestations.size === 0) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'prestation-empty';
                emptyDiv.innerHTML = `
                    <p class="prestation-empty-title">✨ Aucune prestation sélectionnée encore</p>
                    <p class="prestation-empty-subtitle">👆 Commencez par ajouter une prestation ci-dessus</p>
                `;
                selectedDiv.appendChild(emptyDiv);
            } else {
                selectedPrestations.forEach((prestation, id) => {
                    const card = document.createElement('div');
                    card.className = 'prestation-card';

                    const bgPattern = document.createElement('div');
                    bgPattern.style.cssText = `
                        position: absolute;
                        top: -50%;
                        right: -50%;
                        width: 200%;
                        height: 200%;
                        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
                        background-size: 20px 20px;
                        pointer-events: none;
                    `;
                    card.appendChild(bgPattern);

                    const content = document.createElement('div');
                    content.className = 'prestation-content';

                    // Titre
                    const title = document.createElement('div');
                    title.className = 'prestation-title';
                    title.innerHTML = `
                        <span class="prestation-icon">🎁</span>
                        <span>${prestation.nom}</span>
                    `;
                    content.appendChild(title);

                    // Contrôles
                    const controls = document.createElement('div');
                    controls.className = 'prestation-controls';

                    const label = document.createElement('span');
                    label.className = 'prestation-label';
                    label.textContent = 'Quantité :';
                    controls.appendChild(label);

                    const decreaseBtn = document.createElement('button');
                    decreaseBtn.type = 'button';
                    decreaseBtn.className = 'prestation-btn';
                    decreaseBtn.textContent = '−';
                    decreaseBtn.onclick = () => window.decreasePrestationQty(id);
                    controls.appendChild(decreaseBtn);

                    const qtyDisplay = document.createElement('div');
                    qtyDisplay.className = 'prestation-qty';
                    qtyDisplay.textContent = prestation.quantite;
                    controls.appendChild(qtyDisplay);

                    const increaseBtn = document.createElement('button');
                    increaseBtn.type = 'button';
                    increaseBtn.className = 'prestation-btn';
                    increaseBtn.textContent = '+';
                    increaseBtn.onclick = () => window.increasePrestationQty(id);
                    controls.appendChild(increaseBtn);

                    content.appendChild(controls);
                    card.appendChild(content);

                    // Bouton supprimer
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'prestation-remove';
                    removeBtn.textContent = '×';
                    removeBtn.onclick = () => window.removePrestationFromForm(id);
                    card.appendChild(removeBtn);

                    selectedDiv.appendChild(card);
                });
            }

            updateHiddenPrestationsData();
        }

        function updateHiddenPrestationsData() {
            const data = {};
            selectedPrestations.forEach((prestation, id) => {
                data[id] = prestation.quantite;
            });
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(data);
            }
        }

        // Charger les prestations existantes du bien (si en édition)
        const bienPrestations = window.bienPrestations || [];
        if (Array.isArray(bienPrestations) && bienPrestations.length > 0) {
            bienPrestations.forEach(bp => {
                selectedPrestations.set(bp.id_prestation.toString(), {
                    nom: bp.lib_prestation,
                    quantite: parseInt(bp.quantite_prestation) || 1
                });
            });
        }

        renderSelectedPrestations();
    }

    // Initialiser au chargement du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPrestations);
    } else {
        initPrestations();
    }
})();
