/**
 * Gestion des modales pour l'administration
 * Permet d'afficher le contenu des iframes dans des pop-ups modales
 */

class AdminModal {
    constructor() {
        this.currentModal = null;
        this.init();
    }

    /**
     * Initialiser les événements
     */
    init() {
        // Délégation d'événements pour les boîtes cliquables
        document.addEventListener('click', (e) => {
            const box = e.target.closest('.admin-box');
            if (box && !e.target.closest('.modal-close')) {
                const iframeUrl = box.dataset.iframeUrl;
                const title = box.dataset.title;
                this.openModal(iframeUrl, title);
            }
        });

        // Fermer la modale en cliquant sur le bouton de fermeture
        document.addEventListener('click', (e) => {
            const closeBtn = e.target.closest('.modal-close');
            if (closeBtn) {
                e.preventDefault();
                e.stopPropagation();
                this.closeModal();
            }
        });

        // Fermer la modale en cliquant sur l'overlay (mais pas sur la modale elle-même)
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay') && !e.target.closest('.modal-window')) {
                this.closeModal();
            }
        });

        // Fermer la modale avec la touche Échap
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }

    /**
     * Ouvrir une modale
     * @param {string} iframeUrl - L'URL à charger dans l'iframe
     * @param {string} title - Le titre de la modale
     */
    openModal(iframeUrl, title) {
        // Vérifier si une modale est déjà ouverte
        if (this.currentModal) {
            this.closeModal();
        }

        // Créer l'overlay
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';

        // Créer la fenêtre modale
        const modal = document.createElement('div');
        modal.className = 'modal-window';

        // Créer l'en-tête
        const header = document.createElement('div');
        header.className = 'modal-header';
        header.innerHTML = `
            <h2 class="modal-title">${title}</h2>
            <button class="modal-close" type="button" title="Fermer" aria-label="Fermer la modale">
                <span>&times;</span>
            </button>
        `;

        // Créer le corps avec l'iframe
        const body = document.createElement('div');
        body.className = 'modal-body';
        
        // Créer l'iframe avec chargement optimisé
        const iframe = document.createElement('iframe');
        iframe.src = iframeUrl;
        iframe.frameBorder = '0';
        iframe.className = 'modal-iframe';
        iframe.loading = 'lazy'; // Chargement paresseux pour optimiser
        
        const content = document.createElement('div');
        content.className = 'modal-content';
        content.appendChild(iframe);
        
        body.appendChild(content);

        // Assembler la modale
        modal.appendChild(header);
        modal.appendChild(body);
        overlay.appendChild(modal);

        // Ajouter au DOM
        document.body.appendChild(overlay);

        // Stocker la référence
        this.currentModal = overlay;

        // Ajouter la classe active immédiatement pour l'affichage
        requestAnimationFrame(() => {
            overlay.classList.add('active');
        });
    }

    /**
     * Fermer la modale
     */
    closeModal() {
        if (this.currentModal) {
            this.currentModal.classList.remove('active');
            
            // Attendre la fin de l'animation avant de supprimer du DOM
            const handleTransitionEnd = () => {
                if (this.currentModal && this.currentModal.parentNode) {
                    this.currentModal.parentNode.removeChild(this.currentModal);
                }
                this.currentModal = null;
                this.currentModal?.removeEventListener('transitionend', handleTransitionEnd);
            };
            
            // Utiliser transitionend pour une meilleure synchronisation
            if (this.currentModal) {
                this.currentModal.addEventListener('transitionend', handleTransitionEnd, { once: true });
                
                // Fallback au cas où transitionend ne se déclenche pas
                setTimeout(() => {
                    if (this.currentModal && this.currentModal.parentNode) {
                        this.currentModal.parentNode.removeChild(this.currentModal);
                        this.currentModal = null;
                    }
                }, 350);
            }
        }
    }
}

// Initialiser quand le DOM est chargé
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new AdminModal();
    });
} else {
    // Le DOM est déjà chargé
    new AdminModal();
}

