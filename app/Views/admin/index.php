<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/grille.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/admin-modal.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2>Bienvenue sur le tableau de bord administrateur</h2>
        <p>Cliquez sur une boîte pour gérer les différentes entités de la plateforme.</p>
        
        <!-- Conteneur des boîtes cliquables -->
        <div class="admin-boxes-container">
            <!-- Gestion des Types de Biens -->
            <div class="admin-box" data-iframe-url="/admin/typesBiens" data-title="Gestion des Types de Biens">
                <span class="admin-box-icon">🏠</span>
                <h3 class="admin-box-title">Types de Biens</h3>
                <p class="admin-box-description">Gérer les types de biens disponibles</p>
            </div>

            <!-- Gestion des Rôles -->
            <div class="admin-box" data-iframe-url="/admin/roles" data-title="Gestion des Rôles">
                <span class="admin-box-icon">👥</span>
                <h3 class="admin-box-title">Rôles</h3>
                <p class="admin-box-description">Gérer les rôles utilisateur</p>
            </div>

            <!-- Gestion des Communes -->
            <div class="admin-box" data-iframe-url="/admin/communes" data-title="Gestion des Communes">
                <span class="admin-box-icon">🗺️</span>
                <h3 class="admin-box-title">Communes</h3>
                <p class="admin-box-description">Gérer les communes</p>
            </div>

            <!-- Gestion des Utilisateurs -->
            <div class="admin-box" data-iframe-url="/admin/users" data-title="Gestion des Utilisateurs">
                <span class="admin-box-icon">👤</span>
                <h3 class="admin-box-title">Utilisateurs</h3>
                <p class="admin-box-description">Gérer les utilisateurs</p>
            </div>

            <!-- Gestion des Saisons -->
            <div class="admin-box" data-iframe-url="/admin/saisons" data-title="Gestion des Saisons">
                <span class="admin-box-icon">📅</span>
                <h3 class="admin-box-title">Saisons</h3>
                <p class="admin-box-description">Gérer les saisons tarifaires</p>
            </div>

            <!-- Gestion des Biens -->
            <div class="admin-box" data-iframe-url="/admin/biens" data-title="Gestion des Biens">
                <span class="admin-box-icon">🏢</span>
                <h3 class="admin-box-title">Biens</h3>
                <p class="admin-box-description">Gérer tous les biens</p>
            </div>

            <div class="admin-box" data-iframe-url="/admin/admins" data-title="Gestion des Admins">
                <span class="admin-box-icon"></span>
                <h3 class="admin-box-title">Admin</h3>
                <p class="admin-box-description">Gérer tous les admins</p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <!-- Script pour gérer les modales -->
    <script src="/js/admin-modal.js"></script>
</body>
</html>

