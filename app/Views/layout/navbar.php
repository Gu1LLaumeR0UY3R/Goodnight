<?php
/**
 * Navbar partagée pour toutes les pages
 * Affiche une navbar fixe avec une partie statique et une partie dynamique
 */

// Déterminer le rôle et l'état de connexion de l'utilisateur
$isLoggedIn = isset($_SESSION['user_id']);
$userRoles = $_SESSION['user_roles'] ?? [];
$userName = $_SESSION['user_nom'] ?? '';
$userFirstName = $_SESSION['user_prenom'] ?? '';
$userEmail = $_SESSION['user_email'] ?? null;
?>

<header class="navbar-sticky">
    <div class="navbar-container">
        <!-- Section Logo/Titre -->
        <div class="navbar-brand">
            <a href="/home" class="navbar-logo">
                <h1>GlobeNight</h1>
            </a>
        </div>

        <!-- Section Navigation Principale (Statique) -->
        <nav class="navbar-main">
            <ul class="navbar-menu">
                <li><a href="/home" class="navbar-link">Accueil</a></li>
                
                <?php if ($isLoggedIn && isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <li><a href="/admin" class="navbar-link">Administration</a></li>
                <?php elseif ($isLoggedIn && in_array('Propriétaire', $userRoles)): ?>
                    <li><a href="/proprietaire" class="navbar-link">Tableau de bord</a></li>
                    <li><a href="/proprietaire/myBiens" class="navbar-link">Mes Biens</a></li>
                    <li><a href="/proprietaire/myReservations" class="navbar-link">Mes Réservations</a></li>
                <?php elseif ($isLoggedIn && in_array('Locataire', $userRoles)): ?>
                    <li><a href="/locataire" class="navbar-link">Tableau de bord</a></li>
                    <li><a href="/locataire/myReservations" class="navbar-link">Mes Réservations</a></li>

                <?php endif; ?>
            </ul>
        </nav>

        <!-- Section Authentification (Dynamique) -->
        <div class="navbar-auth">
            <?php if ($isLoggedIn): ?>
                <span class="navbar-user-info">
                    <?php 
                        $displayName = trim($userFirstName . ' ' . $userName);
                        if (empty($displayName)) {
                            $displayName = htmlspecialchars($userEmail);
                        }
                        echo htmlspecialchars($displayName);
                    ?>
                </span>
                <a href="/logout" class="navbar-btn navbar-btn-logout">Déconnexion</a>
            <?php else: ?>
                <a href="/login" class="navbar-btn navbar-btn-login">Connexion</a>
                <a href="/register" class="navbar-btn navbar-btn-register">Inscription</a>
            <?php endif; ?>
        </div>

        <!-- Bouton Menu Mobile (Hamburger) -->
        <button class="navbar-toggle" id="navbar-toggle">
            <span class="navbar-toggle-icon"></span>
            <span class="navbar-toggle-icon"></span>
            <span class="navbar-toggle-icon"></span>
        </button>
    </div>
</header>

<script>
    // Gestion du menu mobile
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('navbar-toggle');
        const navbar = document.querySelector('.navbar-sticky');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                navbar.classList.toggle('navbar-mobile-open');
            });
        }

        // Fermer le menu mobile quand on clique sur un lien
        const navLinks = document.querySelectorAll('.navbar-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navbar.classList.remove('navbar-mobile-open');
            });
        });
    });
</script>

<!-- Toaster container + flash messages (global) -->
<?php
    $flashSuccess = $_SESSION['success_message'] ?? null;
    $flashError = $_SESSION['error_message'] ?? null;
    $flashErrors = $_SESSION['errors'] ?? null;
    // clear after reading
    unset($_SESSION['success_message'], $_SESSION['error_message'], $_SESSION['errors']);
?>

<style>
    .toast-container {
        position: fixed;
        top: 1rem;
        left: 50%;
        transform: translateX(-50%);
        z-index: 999999;
        pointer-events: none;
    }
    .toast {
        min-width: 320px;
        max-width: 720px;
        margin: 0.5rem auto;
        background: #fff;
        color: #111;
        border-radius: 6px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: Arial, sans-serif;
        pointer-events: auto;
        opacity: 0;
        transform: translateY(-8px);
        transition: opacity 240ms ease, transform 240ms ease;
    }
    .toast.show { opacity: 1; transform: translateY(0); }
    .toast.success { border-left: 4px solid #28a745; }
    .toast.error { border-left: 4px solid #dc3545; }
    .toast .toast-close { margin-left: auto; background: transparent; border: none; font-size: 16px; cursor: pointer; }
</style>

<div class="toast-container" id="toast-container" aria-live="polite" aria-atomic="true"></div>

<script>
    (function(){
        const container = document.getElementById('toast-container');
        if (!container) return;

        function createToast(type, message, timeout = 4500) {
            const el = document.createElement('div');
            el.className = 'toast ' + (type === 'success' ? 'success' : 'error');
            el.innerHTML = '<div class="toast-message">' + (message || '') + '</div>' +
                '<button class="toast-close" aria-label="Fermer">✕</button>';

            const closeBtn = el.querySelector('.toast-close');
            closeBtn.addEventListener('click', () => removeToast(el));

            container.appendChild(el);
            // show
            requestAnimationFrame(() => el.classList.add('show'));

            const t = setTimeout(() => removeToast(el), timeout);
            // pause on hover
            el.addEventListener('mouseenter', () => clearTimeout(t));
            return el;
        }

        function removeToast(el) {
            if (!el) return;
            el.classList.remove('show');
            setTimeout(() => { try { el.remove(); } catch(e){} }, 260);
        }

        // read server-side flash messages injected by PHP
        const serverFlash = {
            success: <?php echo json_encode($flashSuccess, JSON_UNESCAPED_UNICODE); ?>,
            error: <?php echo json_encode($flashError, JSON_UNESCAPED_UNICODE); ?>,
            errors: <?php echo json_encode($flashErrors, JSON_UNESCAPED_UNICODE); ?>
        };

        if (serverFlash.success) {
            createToast('success', serverFlash.success);
        }
        if (serverFlash.error) {
            createToast('error', serverFlash.error);
        }
        if (Array.isArray(serverFlash.errors) && serverFlash.errors.length) {
            serverFlash.errors.forEach(msg => createToast('error', msg));
        }
    })();
</script>

