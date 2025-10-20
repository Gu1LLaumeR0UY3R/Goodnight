<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/grille.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2>Bienvenue sur le tableau de bord administrateur</h2>
        <p>Utilisez le menu de navigation ci-dessus pour gérer les différentes entités de la plateforme.</p>
        <div class="parent">
            <div class="div1"><iframe src="/admin/typesBiens" frameborder="0"></iframe></div>
            <div class="div2"><iframe src="/admin/roles" frameborder="0"></iframe></div>
            <div class="div3"><iframe src="/admin/communes" frameborder="0"></iframe></div>
            <div class="div4"><iframe src="/admin/users" frameborder="0"></iframe></div>
            <div class="div5"><iframe src="/admin/saisons" frameborder="0"></iframe></div>
            <div class="div6"><iframe src="/admin/biens" frameborder="0"></iframe></div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
