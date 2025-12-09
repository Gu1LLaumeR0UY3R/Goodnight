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
    const favoriteButtons = document.querySelectorAll('.btn-favorite');

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
                const button = document.querySelector(`.btn-favorite[data-bien-id="${bienId}"]`);
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

