<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Bien - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/css/photo-upload.css">

</head>
<body>

    <main>
                <h2>Modifier le Bien : <?php echo htmlspecialchars($bien["designation_bien"]); ?></h2>
        <h2>Modifier le Bien</h2>
        <form action="/admin/editBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" method="POST" enctype="multipart/form-data">
            <label for="designation_bien">Nom du bien :</label>
            <input type="text" id="designation_bien" name="designation_bien" value="<?php echo htmlspecialchars($bien["designation_bien"]); ?>" required>
            <label for="rue_biens">Rue :</label>
            <input type="text" id="rue_biens" name="rue_biens" value="<?php echo htmlspecialchars($bien["rue_biens"]); ?>" required>
            <label for="complement_biens">Complément :</label>
            <input type="text" id="complement_biens" name="complement_biens" value="<?php echo htmlspecialchars($bien["complement_biens"]); ?>">
            <label for="superficie_biens">Superficie (m²) :</label>
            <input type="number" id="superficie_biens" name="superficie_biens" value="<?php echo htmlspecialchars($bien["superficie_biens"]); ?>" required>
            <label for="description_biens">Description :</label>
            <textarea id="description_biens" name="description_biens"><?php echo htmlspecialchars($bien["description_biens"]); ?></textarea>
            <label for="animaux_biens">Animaux acceptés :</label>
            <input type="checkbox" id="animaux_biens" name="animaux_biens" value="1" <?php echo $bien["animaux_biens"] ? "checked" : ""; ?>>
            <label for="nb_couchage">Nombre de couchages :</label>
            <input type="number" id="nb_couchage" name="nb_couchage" value="<?php echo htmlspecialchars($bien["nb_couchage"]); ?>" required>
            <label for="id_TypeBien">Type de bien :</label>
            <select id="id_TypeBien" name="id_TypeBien" required>
                <?php foreach ($typesBiens as $type): ?>
                    <option value="<?php echo htmlspecialchars($type["id_typebien"]); ?>" <?php echo ($bien["id_TypeBien"] == $type["id_typebien"]) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($type["desc_type_bien"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="proprietaire_search">Propriétaire :</label>
            <input type="text" id="proprietaire_search" name="proprietaire_nom" value="<?php echo htmlspecialchars($proprietaireNom); ?>" required>
            <input type="hidden" id="id_locataire" name="id_locataire" value="<?php echo htmlspecialchars($bien["id_locataire"]); ?>">
            <input type="hidden" id="initial_id_locataire" value="<?php echo htmlspecialchars($bien["id_locataire"]); ?>">

            <fieldset class="form-section">
                <legend>Tarification (Prix à la semaine)</legend>
                <div id="tarifs-container" class="tarifs-grid">
                    <?php foreach ($saisons as $saison): ?>
                        <div class="tarif-group">
                            <h4><?php echo htmlspecialchars($saison["lib_saison"]); ?></h4>
                            <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][id_saison]" value="<?php echo htmlspecialchars($saison["id_saison"]); ?>">
                            
                            <div class="form-group">
                                <label for="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>">Prix Semaine (€) :</label>
                                <?php
                                    $currentYear = date('Y');
                                    $tarifKey = $saison['id_saison'] . '_' . $currentYear;
                                    $prixSemaine = $tarifsMapped[$tarifKey] ?? '';
                                ?>
                                <input type="number" id="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][prix_semaine]" step="0.01" min="0" value="<?php echo htmlspecialchars($prixSemaine); ?>">
                                <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][annee]" value="<?php echo htmlspecialchars($currentYear); ?>">
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <h3>Photos actuelles :</h3>
            <div class="photos-grid">
                <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo-item">
                            <img src="<?php echo htmlspecialchars($photo["lien_photo"]); ?>" alt="<?php echo htmlspecialchars($photo["nom_photo"]); ?>" width="100">
                            <a href="/admin/deletePhoto/<?php echo htmlspecialchars($photo["id_photo"]); ?>" onclick="return confirm(&quot;Êtes-vous sûr de vouloir supprimer cette photo ?&quot;);">Supprimer</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune photo pour ce bien.</p>
                <?php endif; ?>
            </div>

            <h3>Ajouter de nouvelles photos :</h3>
            <div class="photo-drop-zone">
                <div class="drop-zone-text">
                    <strong>Glissez-déposez vos photos ici ou cliquez pour sélectionner des fichiers</strong><br>
                </div>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                <div class="photo-preview-container"></div>
            </div>

            <label for="id_commune">Commune :</label>
            <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>" placeholder="Commencez à taper le nom de la commune...">
            <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">
            
        <button type="submit">Mettre à jour le bien</button>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
    <script src="/js/photo-upload.js"></script>

</body>
</html>
