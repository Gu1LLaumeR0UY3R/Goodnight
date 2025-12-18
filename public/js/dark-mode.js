// Dark Mode Toggle System
(function() {
    'use strict';

    // Vérifier la préférence sauvegardée ou la préférence système
    function getInitialMode() {
        const savedMode = localStorage.getItem('darkMode');
        if (savedMode !== null) {
            return savedMode === 'true';
        }
        // Vérifier la préférence système
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    // Créer des étoiles filantes aléatoires
    function createShootingStar() {
        if (!document.body.classList.contains('dark-mode')) return;

        const star = document.createElement('div');
        star.className = 'shooting-star';
        star.style.left = Math.random() * window.innerWidth + 'px';
        star.style.top = Math.random() * (window.innerHeight / 2) + 'px';
        document.body.appendChild(star);

        setTimeout(() => {
            star.remove();
        }, 3000);
    }

    // Lancer des étoiles filantes périodiquement
    function startShootingStars() {
        setInterval(() => {
            if (Math.random() > 0.7) { // 30% de chance toutes les 5 secondes
                createShootingStar();
            }
        }, 5000);
    }

    // Mettre à jour le footer selon le thème
    function updateFooter(isDark) {
        const footer = document.querySelector('footer');
        if (!footer) return;
        
        // Chercher le conteneur existant
        let container = footer.querySelector('.sunset, .nightsky');
        
        if (!container) {
            // Créer le conteneur s'il n'existe pas
            container = document.createElement('div');
            footer.insertBefore(container, footer.firstChild);
        }
        
        if (isDark) {
            container.className = 'nightsky';
            container.innerHTML = `
                <div class="moon"></div>
                <div class="stars"></div>
                <div class="stars-layer-2"></div>
                <div class="horizon"></div>
            `;
        } else {
            container.className = 'sunset';
            container.innerHTML = `
                <div class="sun"></div>
                <div class="horizon"></div>
            `;
        }
    }

    // Mettre à jour la bannière supérieure
    function updateTopBanner(isDark) {
        let banner = document.querySelector('.top-banner, .top-banner-night');
        
        if (!banner) {
            // Créer la bannière si elle n'existe pas
            banner = document.createElement('div');
            document.body.insertBefore(banner, document.body.firstChild);
        }
        
        banner.className = isDark ? 'top-banner-night' : 'top-banner';
    }

    // Appliquer le mode sombre
    function applyDarkMode(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
            // Changer le thème de fond vers la nuit
            document.body.classList.remove('home-sunset');
            document.body.classList.add('home-night');
            updateToggleIcon(true);
            updateFooter(true);
            updateTopBanner(true);
        } else {
            document.body.classList.remove('dark-mode');
            // Changer le thème de fond vers le coucher de soleil
            document.body.classList.remove('home-night');
            document.body.classList.add('home-sunset');
            updateToggleIcon(false);
            updateFooter(false);
            updateTopBanner(false);
        }
        localStorage.setItem('darkMode', isDark);
    }

    // Mettre à jour l'icône du bouton
    function updateToggleIcon(isDark) {
        const toggle = document.getElementById('darkModeToggle');
        if (toggle) {
            toggle.innerHTML = isDark ? '☀️' : '🌙';
            toggle.title = isDark ? 'Mode clair' : 'Mode sombre';
        }
    }

    // Toggle dark mode
    function toggleDarkMode() {
        const isDark = !document.body.classList.contains('dark-mode');
        applyDarkMode(isDark);
    }

    // Créer le bouton toggle
    function createToggleButton() {
        const button = document.createElement('button');
        button.id = 'darkModeToggle';
        button.className = 'dark-mode-toggle';
        button.setAttribute('aria-label', 'Toggle dark mode');
        button.addEventListener('click', toggleDarkMode);
        document.body.appendChild(button);
    }

    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        // Créer le bouton
        createToggleButton();

        // Appliquer le mode initial
        const isDark = getInitialMode();
        applyDarkMode(isDark);

        // Démarrer les étoiles filantes
        startShootingStars();

        // Écouter les changements de préférence système
        if (window.matchMedia) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (localStorage.getItem('darkMode') === null) {
                    applyDarkMode(e.matches);
                }
            });
        }

        // Ajouter un raccourci clavier (Ctrl/Cmd + D)
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
                e.preventDefault();
                toggleDarkMode();
            }
        });
    });

    // Exposer la fonction globalement pour pouvoir l'utiliser depuis d'autres scripts
    window.toggleDarkMode = toggleDarkMode;
})();
