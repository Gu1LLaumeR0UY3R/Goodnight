<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bien - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/photo-upload.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

</head>
<body>

    <main>
        <h2>Ajouter un nouveau Bien</h2>
        <form action="/admin/addBien" method="POST" enctype="multipart/form-data">
            <label for="designation_bien">Nom du bien :</label>
            <input type="text" id="designation_bien" name="designation_bien" required>
            <label for="rue_biens">Rue :</label>
            <input type="text" id="rue_biens" name="rue_biens" required>
            <label for="complement_biens">Complément :</label>
            <input type="text" id="complement_biens" name="complement_biens">
            <label for="superficie_biens">Superficie :</label>
            <input type="number" id="superficie_biens" name="superficie_biens" required>
            <label for="description_biens">Description :</label>
            <textarea id="description_biens" name="description_biens"></textarea>
            <label for="animaux_biens">Animaux autorisés :</label>
            <input type="checkbox" id="animaux_biens" name="animaux_biens">
            <label for="nb_couchage">Nombre de couchages :</label>
            <input type="number" id="nb_couchage" name="nb_couchage" required>
            <label for="id_TypeBien">Type de bien :</label>
            <select id="id_TypeBien" name="id_TypeBien" required>
                <?php foreach ($typesBiens as $typeBien): ?>
                    <option value="<?php echo htmlspecialchars($typeBien['id_typebien']); ?>">
                        <?php echo htmlspecialchars($typeBien['desc_type_bien']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_commune">Commune :</label>
            <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>">
            <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">

            <label for="id_locataire">Propriétaire :</label>
            <select id="id_locataire" name="id_locataire" required>
                <option value="">Sélectionnez un propriétaire</option>

                <?php foreach ($personnesPhysiques as $pp): ?>
                    <option value="<?php echo htmlspecialchars($pp['id_locataire']); ?>">
                        <?php echo htmlspecialchars('Personne physique : ' . $pp['prenom_locataire'] . ' ' . $pp['nom_locataire']); ?>
                    </option>
                <?php endforeach; ?>

                <?php foreach ($personnesMorales as $pm): ?>
                    <option value="<?php echo htmlspecialchars($pm['id_locataire']); ?>">
                        <?php echo htmlspecialchars('Personne morale : ' . $pm['RaisonSociale'] . ' (Siret : ' . $pm['Siret'] . ')'); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <fieldset class="form-section">
                <legend>Tarification (Prix par jour)</legend>
                <div id="tarifs-container" class="tarifs-grid">
                    <?php foreach ($saisons as $saison): ?>
                        <div class="tarif-group">
                            <h4><?php echo htmlspecialchars($saison["lib_saison"]); ?></h4>
                            <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][id_saison]" value="<?php echo htmlspecialchars($saison["id_saison"]); ?>">
                            
                            <div class="form-group">
                                <label for="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>">Prix Jour (€) :</label>
                                <input type="number" id="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][prix_semaine]" step="0.01" min="0">
                            </div>
                            <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][annee]" value="<?php echo date('Y'); ?>">
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <h3>Photos du Bien</h3>
            <div class="photo-drop-zone">
                <div class="drop-zone-text">
                    <strong>Glissez-déposez vos photos ici ou cliquez pour sélectionner des fichiers</strong><br>
                </div>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                <div class="photo-preview-container"></div>
            </div>

            <button type="submit">Ajouter le bien</button>
        </form>

        <button onclick="window.location.href='/admin/biens'">Retour</button>
        
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
    <script src="/js/photo-upload.js"></script>

</body>
</html>
