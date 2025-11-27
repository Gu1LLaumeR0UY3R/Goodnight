/**
 * Gestion des modales pour l'administration
 * Permet d'afficher le contenu des iframes dans des pop-ups modales
 * 
 * Cette classe gère l'ouverture et la fermeture des modales qui affichent
 * les pages d'administration dans des iframes. Elle offre une expérience
 * utilisateur fluide avec animations et gestion des événements.
 */

class AdminModal {
    /**
     * Constructeur de la classe AdminModal
     * Initialise la propriété currentModal et lance l'initialisation des événements
     */
    constructor() {
        // Stocke la référence de la modale actuellement ouverte (null si aucune)
        this.currentModal = null;
        // Lance l'initialisation des écouteurs d'événements
        this.init();
    }

    /**
     * Initialise tous les écouteurs d'événements pour les modales
     * Cette méthode configure la délégation d'événements pour:
     * - L'ouverture des modales au clic sur les boîtes admin
     * - La fermeture via le bouton X
     * - La fermeture via clic sur l'overlay
     * - La fermeture via la touche Échap
     */
    init() {
        // Délégation d'événements pour les boîtes cliquables
        // Utilise .closest() pour gérer les clics sur les éléments enfants de .admin-box
        document.addEventListener('click', (e) => {
            // Recherche si l'élément cliqué est ou est dans une boîte admin
            const box = e.target.closest('.admin-box');
            // Si une boîte est trouvée et qu'on n'a pas cliqué sur le bouton de fermeture
            if (box && !e.target.closest('.modal-close')) {
                // Récupère l'URL de l'iframe depuis l'attribut data-iframe-url
                const iframeUrl = box.dataset.iframeUrl;
                // Récupère le titre depuis l'attribut data-title
                const title = box.dataset.title;
                // Ouvre la modale avec ces informations
                this.openModal(iframeUrl, title);
            }
        });

        // Écouteur pour le bouton de fermeture (X)
        // Utilise preventDefault et stopPropagation pour éviter les interférences
        document.addEventListener('click', (e) => {
            // Recherche si l'élément cliqué est ou est dans un bouton de fermeture
            const closeBtn = e.target.closest('.modal-close');
            if (closeBtn) {
                // Empêche le comportement par défaut du bouton
                e.preventDefault();
                // Empêche la propagation de l'événement aux parents
                e.stopPropagation();
                // Ferme la modale
                this.closeModal();
            }
        });

        // Fermer la modale en cliquant sur l'overlay (fond sombre)
        // Mais PAS en cliquant sur la fenêtre modale elle-même
        document.addEventListener('click', (e) => {
            // Vérifie si on a cliqué sur l'overlay ET pas sur la fenêtre modale
            if (e.target.classList.contains('modal-overlay') && !e.target.closest('.modal-window')) {
                this.closeModal();
            }
        });

        // Fermer la modale avec la touche Échap (Escape)
        // Améliore l'accessibilité et l'expérience utilisateur
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });
    }

    /**
     * Ouvre une modale avec une iframe
     * Crée dynamiquement tous les éléments de la modale et l'affiche avec animation
     * 
     * @param {string} iframeUrl - L'URL de la page à charger dans l'iframe
     * @param {string} title - Le titre à afficher dans l'en-tête de la modale
     */
    openModal(iframeUrl, title) {
        // Vérifier si une modale est déjà ouverte
        // Si oui, la fermer avant d'en ouvrir une nouvelle
        if (this.currentModal) {
            this.closeModal();
        }

        // Créer l'overlay (fond sombre semi-transparent derrière la modale)
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';

        // Créer la fenêtre modale principale
        const modal = document.createElement('div');
        modal.className = 'modal-window';

        // Créer l'en-tête de la modale avec le titre et le bouton de fermeture
        const header = document.createElement('div');
        header.className = 'modal-header';
        header.innerHTML = `
            <h2 class="modal-title">${title}</h2>
            <button class="modal-close" type="button" title="Fermer" aria-label="Fermer la modale">
                <span>&times;</span>
            </button>
        `;

        // Créer le corps de la modale qui contiendra l'iframe
        const body = document.createElement('div');
        body.className = 'modal-body';
        
        // Créer l'iframe avec chargement optimisé
        const iframe = document.createElement('iframe');
        iframe.src = iframeUrl; // URL de la page à afficher
        iframe.frameBorder = '0'; // Pas de bordure
        iframe.className = 'modal-iframe';
        iframe.loading = 'lazy'; // Chargement paresseux (lazy loading) pour optimiser les performances
        
        // Conteneur pour l'iframe
        const content = document.createElement('div');
        content.className = 'modal-content';
        content.appendChild(iframe);
        
        body.appendChild(content);

        // Assembler tous les éléments de la modale
        modal.appendChild(header); // Ajouter l'en-tête
        modal.appendChild(body);   // Ajouter le corps
        overlay.appendChild(modal); // Mettre la modale dans l'overlay

        // Ajouter l'overlay complet au body de la page
        document.body.appendChild(overlay);

        // Stocker la référence de la modale pour pouvoir la fermer plus tard
        this.currentModal = overlay;

        // Utiliser requestAnimationFrame pour garantir que l'élément est dans le DOM
        // avant d'ajouter la classe 'active' qui déclenche l'animation CSS
        requestAnimationFrame(() => {
            overlay.classList.add('active');
        });
    }

    /**
     * Ferme la modale actuellement ouverte
     * Joue l'animation de fermeture puis supprime les éléments du DOM
     * Utilise transitionend pour synchroniser avec l'animation CSS
     */
    closeModal() {
        // Vérifier qu'une modale est bien ouverte
        if (this.currentModal) {
            // Retirer la classe 'active' pour déclencher l'animation CSS de fermeture
            this.currentModal.classList.remove('active');
            
            // Fonction qui sera appelée à la fin de l'animation
            const handleTransitionEnd = () => {
                // Vérifier que la modale existe toujours et qu'elle a un parent dans le DOM
                if (this.currentModal && this.currentModal.parentNode) {
                    // Supprimer complètement la modale du DOM
                    this.currentModal.parentNode.removeChild(this.currentModal);
                }
                // Réinitialiser la référence
                this.currentModal = null;
                // Nettoyer l'écouteur d'événement (sécurité supplémentaire)
                this.currentModal?.removeEventListener('transitionend', handleTransitionEnd);
            };
            
            // Attendre la fin de l'animation CSS (transitionend)
            // { once: true } garantit que l'écouteur ne sera appelé qu'une seule fois
            if (this.currentModal) {
                this.currentModal.addEventListener('transitionend', handleTransitionEnd, { once: true });
                
                // Fallback (plan B) : au cas où l'événement transitionend ne se déclenche pas
                // (peut arriver dans certains navigateurs ou situations)
                // On supprime la modale après 350ms de toute façon
                setTimeout(() => {
                    if (this.currentModal && this.currentModal.parentNode) {
                        this.currentModal.parentNode.removeChild(this.currentModal);
                        this.currentModal = null;
                    }
                }, 350); // 350ms correspond à la durée de l'animation CSS
            }
        }
    }
}

// Initialisation de la classe AdminModal au chargement de la page
// Vérifie d'abord si le DOM est encore en cours de chargement
if (document.readyState === 'loading') {
    // Si le DOM est en cours de chargement, attendre l'événement DOMContentLoaded
    document.addEventListener('DOMContentLoaded', () => {
        new AdminModal();
    });
} else {
    // Si le DOM est déjà complètement chargé, initialiser immédiatement
    new AdminModal();
}

