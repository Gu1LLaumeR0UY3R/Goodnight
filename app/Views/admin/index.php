<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="/css/night-background.css">
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="/css/admin-modal.css">
    <link rel="stylesheet" href="/css/admin-badges.css">
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
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Admins</h3>
                    <p class="admin-box-description">Gérer les comptes administrateurs</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/users" data-title="Gestion des Utilisateurs">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Utilisateurs</h3>
                    <p class="admin-box-description">Administrer les comptes et accès</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/roles" data-title="Gestion des Rôles">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Rôles</h3>
                    <p class="admin-box-description">Définir les rôles et permissions</p>
                </div>
            </div>

            <!-- Gestion des Biens et Réservations -->
            <div class="admin-box glass-card" data-iframe-url="/admin/biens" data-title="Gestion des Biens">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Biens</h3>
                    <p class="admin-box-description">Superviser l'ensemble du parc</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/typesBiens" data-title="Gestion des Types de Biens">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205 3 1m1.5.5-1.5-.5M6.75 7.364V3h-3v18m3-13.636 10.5-3.819"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Types de Biens</h3>
                    <p class="admin-box-description">Gérer les catégories et les typologies</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/saisons" data-title="Gestion des Saisons">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 0 1-1.161.886l-.143.048a1.107 1.107 0 0 0-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 0 1-1.652.928l-.679-.906a1.125 1.125 0 0 0-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 0 0-8.862 12.872M12.75 3.031a9 9 0 0 1 6.69 14.036m0 0-.177-.529A2.25 2.25 0 0 0 17.128 15H16.5l-.324-.324a1.453 1.453 0 0 0-2.328.377l-.036.073a1.586 1.586 0 0 1-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 0 1-5.276 3.67m0 0a9 9 0 0 1-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Saisons</h3>
                    <p class="admin-box-description">Piloter les saisons tarifaires</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/reservations" data-title="Gestion des Réservations">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Réservations</h3>
                    <p class="admin-box-description">Suivre et ajuster les réservations</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/communes" data-title="Gestion des Communes">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Communes</h3>
                    <p class="admin-box-description">Référentiel des communes</p>
                </div>
            </div>

            <!-- Validation et Modération -->
            <div class="admin-box glass-card" data-iframe-url="/admin/validations" data-title="Validation des Biens">
                <?php if (($pendingValidations ?? 0) > 0): ?>
                    <div class="admin-badge dot" title="<?php echo $pendingValidations; ?> biens à valider"></div>
                <?php endif; ?>
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Validations</h3>
                    <p class="admin-box-description">Approuver les biens en attente</p>
                </div>
            </div>

            <div class="admin-box glass-card signalements-card <?php echo ($pendingSignalements ?? 0) > 0 ? 'has-notification' : ''; ?>" data-iframe-url="/admin/signalements" data-title="Gestion des Signalements">
                <?php if (($pendingSignalements ?? 0) > 0): ?>
                    <div class="admin-badge dot" title="<?php echo $pendingSignalements; ?> signalements à traiter"></div>
                <?php endif; ?>
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Signalements</h3>
                    <p class="admin-box-description">Traiter les signalements utilisateurs</p>
                </div>
            </div>

            <div class="admin-box glass-card" data-iframe-url="/admin/commentaires-signales" data-title="Commentaires signalés">
                <span class="admin-box-icon">
                    <svg style="width: 2rem; height: 2rem;" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"></path>
                    </svg>
                </span>
                <div>
                    <h3 class="admin-box-title">Commentaires signalés</h3>
                    <p class="admin-box-description">Modérer les commentaires inappropriés</p>
                </div>
            </div>

            <!-- Gamification et Contenu Spécial -->
            <div class="admin-box glass-card" data-iframe-url="/admin/easter-eggs" data-title="Gestion des Easter Eggs">
                <span class="admin-box-icon">🥚</span>
                <div>
                    <h3 class="admin-box-title">Easter Eggs</h3>
                    <p class="admin-box-description">Gérer tous les easter eggs du système</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <style>
        .admin-box.glass-card {
            background: white !important;
        }
    </style>

    <script src="/js/admin-modal.js"></script>
</body>
</html>