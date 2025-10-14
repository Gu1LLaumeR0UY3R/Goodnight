<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bien - Propriétaire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
</head>
<body>
    <header>
        <h1>Tableau de bord Propriétaire</h1>
        <nav>
            <ul>
                <li><a href="/proprietaire">Accueil Propriétaire</a></li>
                <li><a href="/proprietaire/myBiens">Mes Biens</a></li>
                <li><a href="/proprietaire/myReservations">Mes Réservations</a></li>
                <li><a href="/logout">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Ajouter un nouveau Bien</h2>
        <form action="/proprietaire/addBien" method="POST" enctype="multipart/form-data">
            <label for="designation_bien">Désignation du bien :</label>
            <input type="text" id="designation_bien" name="designation_bien" required>

            <label for="rue_biens">Rue :</label>
            <input type="text" id="rue_biens" name="rue_biens" required>

            <label for="complement_biens">Complément d'adresse :</label>
            <input type="text" id="complement_biens" name="complement_biens">

            <label for="superficie_biens">Superficie (m²) :</label>
            <input type="number" id="superficie_biens" name="superficie_biens" step="0.01" required>

            <label for="description_biens">Description :</label>
            <textarea id="description_biens" name="description_biens"></textarea>

            <label for="animaux_biens">Animaux acceptés :</label>
            <input type="checkbox" id="animaux_biens" name="animaux_biens" value="1">

            <label for="nb_couchage">Nombre de couchages :</label>
            <input type="number" id="nb_couchage" name="nb_couchage" required>

            <label for="id_TypeBien">Type de bien :</label>
            <select id="id_TypeBien" name="id_TypeBien" required>
                <?php foreach ($typesBiens as $type): ?>
                    <option value="<?php echo htmlspecialchars($type["id_typebien"]); ?>">
                        <?php echo htmlspecialchars($type["desc_type_bien"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>

             <label for="id_commune">Commune :</label>
            <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>">
            <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">
            </select>

            <label for="photos">Photos du bien :</label>
            <input type="file" id="photos" name="photos[]" multiple accept="image/*">

            <button type="submit">Ajouter le bien</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
</body>
</html>
