<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Locataire - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/locataire-dashboard.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="locataire-dashboard">
        <!-- HEADER -->
        <section class="dashboard-header">
            <div class="header-content">
                <h1>Bienvenue, <?php echo htmlspecialchars(ucfirst($_SESSION["user_prenom"] ?? $_SESSION["user_email"])); ?> 👋</h1>
                <p class="header-subtitle">Gérez vos réservations et découvrez vos biens favoris</p>
            </div>
        </section>

        <!-- CARTES STATISTIQUES -->
        <section class="stats-grid">
            <?php
            // Compter les réservations
            $activeCount = count(array_filter($reservations ?? [], fn($r) => strtotime($r['date_depart']) > time() && strtotime($r['date_arrivee']) <= time()));
            $upcomingCount = count(array_filter($reservations ?? [], fn($r) => strtotime($r['date_arrivee']) > time()));
            $pastCount = count(array_filter($reservations ?? [], fn($r) => strtotime($r['date_depart']) < time()));
            
            // Calculer les nuits du mois actuel
            $currentMonth = date('m');
            $currentYear = date('Y');
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
            $nightsReserved = 0;
            
            foreach ($reservations ?? [] as $reservation) {
                $arrivalDate = new DateTime($reservation['date_arrivee']);
                $departDate = new DateTime($reservation['date_depart']);
                
                // Vérifier si la réservation chevauche le mois actuel
                if ($arrivalDate->format('m') == $currentMonth && $arrivalDate->format('Y') == $currentYear) {
                    $nights = $departDate->diff($arrivalDate)->days;
                    $nightsReserved += $nights;
                }
            }
            
            $fillPercentage = min(($nightsReserved / $daysInMonth) * 100, 100);
            ?>

            <!-- Carte Nuits Réservées (Wheel) -->
            <div class="stat-card wheel-card">
                <div class="wheel-container">
                    <svg class="wheel" viewBox="0 0 100 100">
                        <!-- Cercle de fond -->
                        <circle cx="50" cy="50" r="45" class="wheel-background"></circle>
                        <!-- Cercle de remplissage (progress) -->
                        <circle 
                            cx="50" 
                            cy="50" 
                            r="45" 
                            class="wheel-fill"
                            style="stroke-dasharray: <?php echo ($fillPercentage / 100) * 2 * 3.14159 * 45; ?> <?php echo 2 * 3.14159 * 45; ?>"
                        ></circle>
                    </svg>
                    <div class="wheel-center">
                        <span class="wheel-emoji">🛏️</span>
                    </div>
                </div>
                <div class="wheel-info">
                    <h3>Nuits Ce Mois</h3>
                    <p class="wheel-value"><?php echo $nightsReserved; ?>/<?php echo $daysInMonth; ?> nuits</p>
                    <p class="wheel-percentage"><?php echo round($fillPercentage, 0); ?>%</p>
                </div>
            </div>

            <!-- Carte Réservations Actives -->
            <div class="stat-card active">
                <div class="stat-icon">🏠</div>
                <div class="stat-content">
                    <h3>Réservations Actives</h3>
                    <p class="stat-value"><?php echo $activeCount; ?></p>
                    <p class="stat-label">En cours</p>
                </div>
                <a href="/locataire/myReservations" class="stat-link">Voir →</a>
            </div>

            <!-- Carte Réservations à Venir -->
            <div class="stat-card upcoming">
                <div class="stat-icon">📅</div>
                <div class="stat-content">
                    <h3>À Venir</h3>
                    <p class="stat-value"><?php echo $upcomingCount; ?></p>
                    <p class="stat-label">Prochaines réservations</p>
                </div>
                <a href="/locataire/myReservations" class="stat-link">Voir →</a>
            </div>

            <!-- Carte Réservations Passées -->
            <div class="stat-card past">
                <div class="stat-icon">✅</div>
                <div class="stat-content">
                    <h3>Complétées</h3>
                    <p class="stat-value"><?php echo $pastCount; ?></p>
                    <p class="stat-label">Historique</p>
                </div>
                <a href="/locataire/myReservations" class="stat-link">Voir →</a>
            </div>

            <!-- Carte Favoris -->
            <div class="stat-card favorites">
                <div class="stat-icon">❤️</div>
                <div class="stat-content">
                    <h3>Favoris</h3>
                    <p class="stat-value">-</p>
                    <p class="stat-label">Biens sauvegardés</p>
                </div>
                <a href="/home" class="stat-link">Explorer →</a>
            </div>
        </section>

        <!-- ACTIONS RAPIDES -->
        <section class="quick-actions">
            <h2>Actions Rapides</h2>
            <div class="actions-grid">
                <a href="/locataire/myReservations" class="action-btn">
                    <span class="action-icon">📋</span>
                    <span class="action-label">Mes Réservations</span>
                </a>
                <a href="/home" class="action-btn">
                    <span class="action-icon">🔍</span>
                    <span class="action-label">Chercher un Bien</span>
                </a>
                <a href="/profile" class="action-btn">
                    <span class="action-icon">👤</span>
                    <span class="action-label">Mon Profil</span>
                </a>
                <a href="/home/map" class="action-btn">
                    <span class="action-icon">🗺️</span>
                    <span class="action-label">Voir la Carte</span>
                </a>
            </div>
        </section>

        <!-- PROCHAINES RÉSERVATIONS (APERÇU) -->
        <section class="upcoming-preview">
            <h2>Vos Prochaines Réservations</h2>
            <?php
            $upcomingReservations = array_filter($reservations ?? [], fn($r) => strtotime($r['date_arrivee']) > time());
            usort($upcomingReservations, fn($a, $b) => strtotime($a['date_arrivee']) <=> strtotime($b['date_arrivee']));
            $upcomingReservations = array_slice($upcomingReservations, 0, 3);

            if (!empty($upcomingReservations)):
            ?>
                <div class="reservations-list">
                    <?php foreach ($upcomingReservations as $reservation): ?>
                        <div class="reservation-item">
                            <div class="reservation-date">
                                <span class="date-label">Arrivée</span>
                                <span class="date-value"><?php echo date('d/m/Y', strtotime($reservation['date_arrivee'])); ?></span>
                            </div>
                            <div class="reservation-info">
                                <h4><?php echo htmlspecialchars($reservation['designation_bien'] ?? 'Bien'); ?></h4>
                                <p class="commune"><?php echo htmlspecialchars($reservation['commune_nom'] ?? 'Lieu'); ?></p>
                            </div>
                            <div class="reservation-status">
                                <span class="status-badge <?php echo strtolower($reservation['statut_reservation'] ?? 'pending'); ?>">
                                    <?php echo ucfirst($reservation['statut_reservation'] ?? 'En attente'); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <p>Aucune réservation à venir</p>
                    <a href="/home" class="btn-cta">Rechercher un bien →</a>
                </div>
            <?php endif; ?>
        </section>

        <!-- INFOS UTILES -->
        <section class="helpful-info">
            <h2>À Savoir</h2>
            <div class="info-grid">
                <div class="info-card">
                    <h3>📞 Besoin d'aide ?</h3>
                    <p>Consultez notre centre d'aide ou contactez le support.</p>
                    <a href="#">En savoir plus</a>
                </div>
                <div class="info-card">
                    <h3>❓ Questions Fréquentes</h3>
                    <p>Trouvez les réponses aux questions courantes.</p>
                    <a href="#">Accéder à la FAQ</a>
                </div>
                <div class="info-card">
                    <h3>✨ Guide du Locataire</h3>
                    <p>Conseils pour profiter au maximum de votre séjour.</p>
                    <a href="#">Lire le guide</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
