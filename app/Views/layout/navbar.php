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

