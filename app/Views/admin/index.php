<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/admin-modal.css">
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="page-shell">
        <section class="glass-card admin-hero">
            <p class="eyebrow">Tableau de bord administrateur</p>
            <div class="admin-hero-head">
                <h1>Bienvenue</h1>
                <p>Cliquez sur une carte pour gérer les entités clés de la plateforme.</p>
            </div>
        </section>

        <section class="admin-boxes-grid">
            <!-- Gestion des Admins et Accès -->
            <div class="admin-box glass-card" data-iframe-url="/admin/admins" data-title="Gestion des Admins">
                <span class="admin-box-icon">🛡️</span>
                <div>
                    <h3 class="admin-box-title">Admins</h3>
                    <p class="admin-box-description">Gérer les comptes administrateurs</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/users" data-title="Gestion des Utilisateurs">
                <span class="admin-box-icon">👤</span>
                <div>
                    <h3 class="admin-box-title">Utilisateurs</h3>
                    <p class="admin-box-description">Administrer les comptes et accès</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/roles" data-title="Gestion des Rôles">
                <span class="admin-box-icon">👥</span>
                <div>
                    <h3 class="admin-box-title">Rôles</h3>
                    <p class="admin-box-description">Définir les rôles et permissions</p>
                </div>
            </div>

            <!-- Gestion des Biens et Réservations -->
            <div class="admin-box glass-card" data-iframe-url="/admin/biens" data-title="Gestion des Biens">
                <span class="admin-box-icon">🏢</span>
                <div>
                    <h3 class="admin-box-title">Biens</h3>
                    <p class="admin-box-description">Superviser l'ensemble du parc</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/typesBiens" data-title="Gestion des Types de Biens">
                <span class="admin-box-icon">🏠</span>
                <div>
                    <h3 class="admin-box-title">Types de Biens</h3>
                    <p class="admin-box-description">Gérer les catégories et les typologies</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/saisons" data-title="Gestion des Saisons">
                <span class="admin-box-icon">📅</span>
                <div>
                    <h3 class="admin-box-title">Saisons</h3>
                    <p class="admin-box-description">Piloter les saisons tarifaires</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/reservations" data-title="Gestion des Réservations">
                <span class="admin-box-icon">📖</span>
                <div>
                    <h3 class="admin-box-title">Réservations</h3>
                    <p class="admin-box-description">Suivre et ajuster les réservations</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/communes" data-title="Gestion des Communes">
                <span class="admin-box-icon">🗺️</span>
                <div>
                    <h3 class="admin-box-title">Communes</h3>
                    <p class="admin-box-description">Référentiel des communes</p>
                </div>
            </div>

            <!-- Validation et Modération -->
            <div class="admin-box glass-card validations-card <?php echo ($pendingValidations ?? 0) > 0 ? 'has-notification' : ''; ?>" data-iframe-url="/admin/validations" data-title="Validation des Biens">
                <?php if (($pendingValidations ?? 0) > 0): ?>
                    <div class="notification-badge validations has-items" data-tooltip="Validations à traiter">
                        <div class="notification-badge-text">
                            <span class="notification-badge-number"><?php echo $pendingValidations; ?></span>
                            <span class="notification-badge-label">À valider</span>
                        </div>
                    </div>
                <?php endif; ?>
                <span class="admin-box-icon">✅</span>
                <div>
                    <h3 class="admin-box-title">Validations</h3>
                    <p class="admin-box-description">Approuver les biens en attente</p>
                </div>
            </div>

            <div class="admin-box glass-card signalements-card <?php echo ($pendingSignalements ?? 0) > 0 ? 'has-notification' : ''; ?>" data-iframe-url="/admin/signalements" data-title="Gestion des Signalements">
                <?php if (($pendingSignalements ?? 0) > 0): ?>
                    <div class="notification-badge signalements has-items" data-tooltip="Signalements à traiter">
                        <div class="notification-badge-text">
                            <span class="notification-badge-number"><?php echo $pendingSignalements; ?></span>
                            <span class="notification-badge-label">À traiter</span>
                        </div>
                    </div>
                <?php endif; ?>
                <span class="admin-box-icon">🚩</span>
                <div>
                    <h3 class="admin-box-title">Signalements</h3>
                    <p class="admin-box-description">Traiter les signalements utilisateurs</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="/js/admin-modal.js"></script>
</body>
</html>