/**
 * Gestion du système de favoris avec synchronisation serveur
 */

document.addEventListener('DOMContentLoaded', function() {
    // Charger les favoris depuis le serveur
    loadFavoritesFromServer();

    // Attacher les événements aux boutons favoris
    attachFavoriteListeners();
});

/**
 * Attacher les événements aux boutons favoris
 */
function attachFavoriteListeners() {
    const favoriteButtons = document.querySelectorAll('.btn-fav');

    favoriteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const bienId = this.dataset.bienId;
            const isActive = this.classList.contains('active');

            if (isActive) {
                removeFavorite(bienId, this);
            } else {
                addFavorite(bienId, this);
            }
        });
    });
}

/**
 * Ajouter un bien aux favoris
 */
function addFavorite(bienId, buttonElement) {
    // Marquer le bouton comme actif immédiatement pour feedback utilisateur
    buttonElement.classList.add('active');
    showNotification('Ajouté aux favoris ♡', 'success');

    // Sauvegarder sur le serveur
    saveToServer('add', bienId, (success) => {
        if (!success) {
            // Annuler si erreur serveur
            buttonElement.classList.remove('active');
            showNotification('Erreur lors de l\'ajout', 'error');
        }
    });
}

/**
 * Supprimer un bien des favoris
 */
function removeFavorite(bienId, buttonElement) {
    // Marquer le bouton comme inactif immédiatement
    buttonElement.classList.remove('active');
    showNotification('Retiré des favoris', 'remove');

    // Supprimer du serveur
    saveToServer('remove', bienId, (success) => {
        if (!success) {
            // Annuler si erreur serveur
            buttonElement.classList.add('active');
            showNotification('Erreur lors du retrait', 'error');
        }
    });
}

/**
 * Charger les favoris depuis le serveur et les mettre en évidence
 */
function loadFavoritesFromServer() {
    fetch('/api/get-user-favorites', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && Array.isArray(data.favoriteIds)) {
            data.favoriteIds.forEach(bienId => {
                const button = document.querySelector(`.btn-fav[data-bien-id="${bienId}"]`);
                if (button) {
                    button.classList.add('active');
                }
            });
        }
    })
    .catch(error => {
        console.error('Erreur lors du chargement des favoris:', error);
    });
}

/**
 * Sauvegarder un favori sur le serveur
 */
function saveToServer(action, bienId, callback) {
    fetch('/api/favoris', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            bien_id: bienId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (callback) callback(true);
        } else {
            if (callback) callback(false);
        }
    })
    .catch(error => {
        console.error('Erreur lors de la sauvegarde:', error);
        if (callback) callback(false);
    });
}

/**
 * Afficher une notification toast
 */
function showNotification(message, type) {
    // Supprimer les notifications existantes
    const existingToast = document.querySelector('.favorite-toast');
    if (existingToast) {
        existingToast.remove();
    }

    // Créer la notification
    const toast = document.createElement('div');
    toast.className = `favorite-toast ${type}`;
    toast.textContent = message;

    document.body.appendChild(toast);

    // Supprimer après 2 secondes
    setTimeout(() => {
        toast.style.animation = 'slideUp 0.3s ease reverse';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 2000);
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function isUserLoggedIn() {
    return true; // Assumé vrai puisque le contrôleur vérifie le rôle
}

/**
 * Effacer tous les favoris
 */
function clearAllFavorites() {
    if (!confirm('Êtes-vous sûr de vouloir supprimer tous vos favoris ?')) {
        return;
    }

    const favoriteButtons = document.querySelectorAll('.btn-fav.active');
    
    if (favoriteButtons.length === 0) {
        showNotification('Aucun favori à supprimer', 'error');
        return;
    }

    // Supprimer tous les favoris un par un
    let removedCount = 0;
    favoriteButtons.forEach(button => {
        const bienId = button.dataset.bienId;
        
        // Retirer la classe active immédiatement
        button.classList.remove('active');
        
        // Sauvegarder sur le serveur
        saveToServer('remove', bienId, (success) => {
            if (success) {
                removedCount++;
                
                // Supprimer la carte du DOM si on est sur la page favoris
                const card = button.closest('.bien-card');
                if (card) {
                    card.style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => {
                        card.remove();
                        
                        // Vérifier s'il reste des favoris
                        const remainingCards = document.querySelectorAll('.bien-card');
                        if (remainingCards.length === 0) {
                            // Recharger la page pour afficher l'état vide
                            window.location.reload();
                        }
                    }, 300);
                }
            }
        });
    });

    showNotification(`${favoriteButtons.length} favori(s) supprimé(s)`, 'success');
}

// Animation de disparition
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }
        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }
`;
document.head.appendChild(style);

