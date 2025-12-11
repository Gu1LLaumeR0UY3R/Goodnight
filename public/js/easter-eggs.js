/**
 * EASTER EGGS - JavaScript pour les interactions
 * Gestion des onglets, suppression de cadres, et autres interactions
 */

// ========================================
// GESTION DES ONGLETS
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    initTabs();
    initDeleteButtons();
    initFileUpload();
});

/**
 * Initialise le système d'onglets
 */
function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    if (tabButtons.length === 0) return;
    
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            // Retirer la classe active de tous les boutons et contenus
            tabButtons.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Ajouter la classe active au bouton cliqué et au contenu correspondant
            this.classList.add('active');
            const targetTab = document.getElementById(tabId);
            if (targetTab) {
                targetTab.classList.add('active');
            }
            
            // Sauvegarder l'onglet actif dans le localStorage
            localStorage.setItem('easterEggsActiveTab', tabId);
        });
    });
    
    // Restaurer l'onglet actif depuis le localStorage
    const savedTab = localStorage.getItem('easterEggsActiveTab');
    if (savedTab) {
        const savedButton = document.querySelector(`[data-tab="${savedTab}"]`);
        if (savedButton) {
            savedButton.click();
        }
    }
}

// ========================================
// GESTION DE LA SUPPRESSION DES CADRES
// ========================================

/**
 * Initialise les boutons de suppression
 */
function initDeleteButtons() {
    const deleteButtons = document.querySelectorAll('.btn-delete-cadre');
    
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', async function() {
            const cadreId = this.dataset.cadreId;
            const cadreNom = this.dataset.cadreNom;
            
            // Confirmation
            if (!confirm(`Êtes-vous sûr de vouloir supprimer le cadre "${cadreNom}" ?\n\nCette action est irréversible.`)) {
                return;
            }
            
            // Désactiver le bouton pendant la suppression
            this.disabled = true;
            const originalHtml = this.innerHTML;
            this.innerHTML = '⏳ Suppression...';
            
            try {
                const response = await fetch('/cadre/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: cadreId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Animation de suppression
                    const card = this.closest('.card');
                    if (card) {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        
                        setTimeout(() => {
                            card.remove();
                            
                            // Vérifier s'il reste des cartes
                            const cardsGrid = document.querySelector('.cards-grid');
                            const remainingCards = cardsGrid.querySelectorAll('.card');
                            
                            if (remainingCards.length === 0) {
                                cardsGrid.innerHTML = '<div class="empty-state"><p>Aucun cadre disponible.</p></div>';
                            }
                        }, 300);
                    }
                    
                    showNotification('Cadre supprimé avec succès', 'success');
                } else {
                    throw new Error(data.error || 'Erreur inconnue');
                }
            } catch (error) {
                console.error('Erreur lors de la suppression:', error);
                showNotification('Erreur lors de la suppression : ' + error.message, 'error');
                
                // Réactiver le bouton
                this.disabled = false;
                this.innerHTML = originalHtml;
            }
        });
    });
}

// ========================================
// GESTION DE L'UPLOAD DE FICHIERS
// ========================================

/**
 * Initialise la prévisualisation de l'upload de fichiers
 */
function initFileUpload() {
    const fileInput = document.getElementById('fichier');
    if (!fileInput) return;
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('filePreview');
        const previewImage = document.getElementById('previewImage');
        const fileName = document.getElementById('fileName');
        
        if (!file) {
            if (preview) preview.classList.remove('active');
            return;
        }
        
        // Vérifier que c'est un PNG
        if (!file.type.match('image/png')) {
            showNotification('Veuillez sélectionner un fichier PNG', 'error');
            e.target.value = '';
            if (preview) preview.classList.remove('active');
            return;
        }
        
        // Vérifier la taille (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('Le fichier est trop volumineux (max 5MB)', 'error');
            e.target.value = '';
            if (preview) preview.classList.remove('active');
            return;
        }
        
        // Afficher les informations du fichier
        if (fileName) {
            const sizeKB = (file.size / 1024).toFixed(2);
            fileName.textContent = `Fichier : ${file.name} (${sizeKB} KB)`;
        }
        
        // Créer l'aperçu
        const reader = new FileReader();
        reader.onload = function(event) {
            if (previewImage) {
                previewImage.src = event.target.result;
            }
            if (preview) {
                preview.classList.add('active');
            }
        };
        reader.onerror = function() {
            showNotification('Erreur lors de la lecture du fichier', 'error');
        };
        reader.readAsDataURL(file);
    });
}

// ========================================
// SYSTÈME DE NOTIFICATIONS
// ========================================

/**
 * Affiche une notification toast
 * @param {string} message - Le message à afficher
 * @param {string} type - Le type de notification (success, error, info, warning)
 */
function showNotification(message, type = 'info') {
    // Créer le conteneur de notifications s'il n'existe pas
    let container = document.getElementById('notificationContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notificationContainer';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 400px;
        `;
        document.body.appendChild(container);
    }
    
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    // Styles selon le type
    const colors = {
        success: { bg: 'rgba(76, 175, 80, 0.95)', border: '#4caf50' },
        error: { bg: 'rgba(244, 67, 54, 0.95)', border: '#f44336' },
        warning: { bg: 'rgba(255, 152, 0, 0.95)', border: '#ff9800' },
        info: { bg: 'rgba(33, 150, 243, 0.95)', border: '#2196f3' }
    };
    
    const color = colors[type] || colors.info;
    
    notification.style.cssText = `
        background: ${color.bg};
        color: white;
        padding: 14px 18px;
        border-radius: 8px;
        border-left: 4px solid ${color.border};
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        font-size: 0.95rem;
        animation: slideIn 0.3s ease;
        cursor: pointer;
        transition: all 0.2s ease;
    `;
    
    notification.textContent = message;
    
    // Animation d'entrée
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(100px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(100px);
            }
        }
    `;
    if (!document.getElementById('notificationStyles')) {
        style.id = 'notificationStyles';
        document.head.appendChild(style);
    }
    
    // Ajouter au conteneur
    container.appendChild(notification);
    
    // Effet hover
    notification.addEventListener('mouseenter', function() {
        this.style.transform = 'translateX(-5px)';
    });
    
    notification.addEventListener('mouseleave', function() {
        this.style.transform = 'translateX(0)';
    });
    
    // Supprimer au clic
    notification.addEventListener('click', function() {
        removeNotification(this);
    });
    
    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        removeNotification(notification);
    }, 5000);
}

/**
 * Supprime une notification avec animation
 * @param {HTMLElement} notification - L'élément notification à supprimer
 */
function removeNotification(notification) {
    if (!notification || !notification.parentElement) return;
    
    notification.style.animation = 'slideOut 0.3s ease';
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 300);
}

// ========================================
// VALIDATION DU FORMULAIRE
// ========================================

/**
 * Initialise la validation du formulaire d'ajout
 */
function initFormValidation() {
    const form = document.getElementById('addFrameForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        const nom = document.getElementById('nom').value.trim();
        const fichier = document.getElementById('fichier').files[0];
        
        // Validation du nom
        if (!nom) {
            e.preventDefault();
            showNotification('Veuillez entrer un nom pour le cadre', 'error');
            document.getElementById('nom').focus();
            return false;
        }
        
        // Validation du fichier
        if (!fichier) {
            e.preventDefault();
            showNotification('Veuillez sélectionner un fichier PNG', 'error');
            document.getElementById('fichier').focus();
            return false;
        }
        
        // Afficher un indicateur de chargement
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Création en cours...';
        }
        
        return true;
    });
}

// Initialiser la validation si on est sur la page de création
if (document.getElementById('addFrameForm')) {
    initFormValidation();
}

// ========================================
// UTILITAIRES
// ========================================

/**
 * Formate une taille de fichier en octets vers une chaîne lisible
 * @param {number} bytes - La taille en octets
 * @returns {string} - La taille formatée
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + ' ' + sizes[i];
}

/**
 * Escape HTML pour éviter les XSS
 * @param {string} text - Le texte à échapper
 * @returns {string} - Le texte échappé
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

console.log('🎨 Easter Eggs JavaScript chargé avec succès');
