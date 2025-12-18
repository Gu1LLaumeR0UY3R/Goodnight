/**
 * Système de suppression multiple pour les pages admin
 */

(function() {
    let selectedIds = new Set();
    let entityType = '';
    let deleteEndpoint = '';

    /**
     * Initialiser le système de multi-suppression
     */
    window.initMultiDelete = function(config) {
        entityType = config.entityType || 'élément';
        deleteEndpoint = config.deleteEndpoint || '/admin/deleteMultiple';
        
        setupUI();
        attachEventListeners();
    };

    /**
     * Créer l'interface utilisateur
     */
    function setupUI() {
        // Ajouter une checkbox dans le header
        const table = document.getElementById('admintable');
        if (!table) return;

        const thead = table.querySelector('thead tr');
        const checkboxHeader = document.createElement('th');
        checkboxHeader.innerHTML = '<input type="checkbox" id="select-all" title="Tout sélectionner">';
        thead.insertBefore(checkboxHeader, thead.firstChild);

        // Ajouter des checkboxes dans chaque ligne
        const tbody = table.querySelector('tbody');
        tbody.querySelectorAll('tr').forEach(row => {
            const firstCell = row.querySelector('td');
            if (!firstCell) return;
            
            const id = firstCell.textContent.trim();
            const checkbox = document.createElement('td');
            checkbox.innerHTML = `<input type="checkbox" class="row-select" data-id="${id}">`;
            row.insertBefore(checkbox, row.firstChild);
        });

        // Ajouter le bouton de suppression multiple
        const mainElement = document.querySelector('main');
        const heading = mainElement.querySelector('h2');
        
        const deleteButton = document.createElement('button');
        deleteButton.id = 'delete-selected';
        deleteButton.className = 'btn-delete-multiple';
        deleteButton.style.display = 'none';
        deleteButton.innerHTML = '🗑️ Supprimer la sélection (<span id="selected-count">0</span>)';
        
        heading.parentNode.insertBefore(deleteButton, heading.nextSibling);
    }

    /**
     * Attacher les événements
     */
    function attachEventListeners() {
        // Sélectionner/désélectionner tout
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.row-select');
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                    if (this.checked) {
                        selectedIds.add(cb.dataset.id);
                    } else {
                        selectedIds.delete(cb.dataset.id);
                    }
                });
                updateUI();
            });
        }

        // Sélection individuelle
        document.querySelectorAll('.row-select').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedIds.add(this.dataset.id);
                } else {
                    selectedIds.delete(this.dataset.id);
                }
                updateSelectAllState();
                updateUI();
            });
        });

        // Bouton de suppression
        const deleteButton = document.getElementById('delete-selected');
        if (deleteButton) {
            deleteButton.addEventListener('click', handleMultiDelete);
        }
    }

    /**
     * Mettre à jour l'état de la checkbox "Tout sélectionner"
     */
    function updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-select');
        const checkedCount = document.querySelectorAll('.row-select:checked').length;
        
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === checkboxes.length && checkedCount > 0;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
        }
    }

    /**
     * Mettre à jour l'interface
     */
    function updateUI() {
        const deleteButton = document.getElementById('delete-selected');
        const countSpan = document.getElementById('selected-count');
        
        if (deleteButton && countSpan) {
            const count = selectedIds.size;
            countSpan.textContent = count;
            deleteButton.style.display = count > 0 ? 'inline-flex' : 'none';
        }
    }

    /**
     * Gérer la suppression multiple
     */
    function handleMultiDelete() {
        if (selectedIds.size === 0) {
            alert('Veuillez sélectionner au moins un élément à supprimer.');
            return;
        }

        const entityName = selectedIds.size > 1 ? `${entityType}s` : entityType;
        if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selectedIds.size} ${entityName} ?`)) {
            return;
        }

        // Désactiver le bouton pendant le traitement
        const deleteButton = document.getElementById('delete-selected');
        deleteButton.disabled = true;
        deleteButton.innerHTML = '⏳ Suppression en cours...';

        // Envoyer la requête
        fetch(deleteEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                ids: Array.from(selectedIds)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Créer un toast de succès
                showToast(`${data.deleted || selectedIds.size} ${entityName} supprimé(s) avec succès`, 'success');
                
                // Recharger la page après un court délai
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Erreur lors de la suppression', 'error');
                deleteButton.disabled = false;
                deleteButton.innerHTML = '🗑️ Supprimer la sélection (<span id="selected-count">' + selectedIds.size + '</span>)';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur lors de la suppression', 'error');
            deleteButton.disabled = false;
            deleteButton.innerHTML = '🗑️ Supprimer la sélection (<span id="selected-count">' + selectedIds.size + '</span>)';
        });
    }

    /**
     * Afficher un toast de notification
     */
    function showToast(message, type) {
        const container = document.getElementById('toast-container');
        if (!container) {
            console.warn('Toast container not found');
            alert(message);
            return;
        }

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.innerHTML = `
            <div class="toast-message">${message}</div>
            <button class="toast-close" aria-label="Fermer">✕</button>
        `;

        container.appendChild(toast);

        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => removeToast(toast));

        requestAnimationFrame(() => toast.classList.add('show'));

        setTimeout(() => removeToast(toast), 4500);
    }

    function removeToast(toast) {
        if (!toast) return;
        toast.classList.remove('show');
        setTimeout(() => {
            try {
                toast.remove();
            } catch (e) {}
        }, 260);
    }
})();
