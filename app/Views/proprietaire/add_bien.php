<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bien - Propriétaire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/form_style.css"> <!-- Nouveau fichier CSS pour le formulaire -->
    <link rel="stylesheet" href="/css/photo-upload.css"> <!-- Styles pour le drag and drop -->
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
        <h2 class="form-title">Ajouter un nouveau Bien</h2>
        <form action="/proprietaire/addBien" method="POST" enctype="multipart/form-data" class="add-bien-form">
            
            <fieldset class="form-section">
                <legend>Informations Générales</legend>
                <div class="form-group">
                    <label for="designation_bien">Désignation du bien :</label>
                    <input type="text" id="designation_bien" name="designation_bien" required>
                </div>
                <div class="form-group">
                    <label for="id_TypeBien">Type de bien :</label>
                    <select id="id_TypeBien" name="id_TypeBien" required>
                        <?php foreach ($typesBiens as $type): ?>
                            <option value="<?php echo htmlspecialchars($type["id_typebien"]); ?>">
                                <?php echo htmlspecialchars($type["desc_type_bien"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="superficie_biens">Superficie (m²) :</label>
                    <input type="number" id="superficie_biens" name="superficie_biens" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="nb_couchage">Nombre de couchages :</label>
                    <input type="number" id="nb_couchage" name="nb_couchage" required>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="animaux_biens" name="animaux_biens" value="1">
                    <label for="animaux_biens">Animaux acceptés</label>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Localisation</legend>
                <div class="form-group">
                    <label for="rue_biens">Rue :</label>
                    <input type="text" id="rue_biens" name="rue_biens" required>
                </div>
                <div class="form-group">
                    <label for="complement_biens">Complément d'adresse :</label>
                    <input type="text" id="complement_biens" name="complement_biens">
                </div>
                <div class="form-group">
                    <label for="id_commune">Commune :</label>
                    <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>" placeholder="Commencez à taper le nom de la commune...">
                    <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Description</legend>
                <div class="form-group full-width">
                    <label for="description_biens">Description détaillée :</label>
                    <textarea id="description_biens" name="description_biens" rows="5"></textarea>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Tarification (Prix à la semaine)</legend>
                <div id="tarifs-container" class="tarifs-grid">
                    <?php foreach ($saisons as $saison): ?>
                        <div class="tarif-group">
                            <h4><?php echo htmlspecialchars($saison["lib_saison"]); ?></h4>
                            <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][id_saison]" value="<?php echo htmlspecialchars($saison["id_saison"]); ?>">
                            
                            <div class="form-group">
                                <label for="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>">Prix Semaine (€) :</label>
                                <input type="number" id="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][prix_semaine]" step="0.01" min="0">
                            </div>
                            <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][annee]" value="<?php echo date('Y'); ?>">
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Photos du Bien</legend>
                <div class="form-group full-width">
                    <div class="photo-drop-zone">
                        <div class="drop-zone-text">
                            <strong>Glissez-déposez vos photos ici</strong><br>
                            ou cliquez pour sélectionner des fichiers
                        </div>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                        <div class="photo-preview-container"></div>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="submit-button">Ajouter le bien</button>
        </form>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
    <script src="/js/photo-upload.js"></script>
</body>
</html>
