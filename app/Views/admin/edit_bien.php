<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Bien - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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
            <label for="superficie_biens">Superficie :</label>
            <input type="number" id="superficie_biens" name="superficie_biens" value="<?php echo htmlspecialchars($bien["superficie_biens"]); ?>" required>
            <label for="description_biens">Description :</label>
            <textarea id="description_biens" name="description_biens"><?php echo htmlspecialchars($bien["description_biens"]); ?></textarea>
            <label for="animaux_biens">Animaux autorisés :</label>
            <input type="checkbox" id="animaux_biens" name="animaux_biens" value="1" <?php if ($bien["animaux_biens"]) echo 'checked'; ?>>
            <label for="nb_couchage">Nombre de couchages :</label>
            <input type="number" id="nb_couchage" name="nb_couchage" value="<?php echo htmlspecialchars($bien["nb_couchage"]); ?>" required>
            <label for="id_TypeBien">Type de bien :</label>
            <select id="id_TypeBien" name="id_TypeBien" required>
                <?php foreach ($typesBiens as $typeBien): ?>
                    <option value="<?php echo htmlspecialchars($typeBien['id_typebien']); ?>" <?php if ($typeBien['id_typebien'] == $bien['id_TypeBien']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($typeBien['desc_type_bien']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="proprietaire_search">Propriétaire :</label>
            <input type="text" id="proprietaire_search" name="proprietaire_nom" value="<?php echo htmlspecialchars($proprietaireNom); ?>" required>
            <input type="h<input type="hidden" id="id_locataire" name="id_locataire" value="<?php echo htmlspecialchars($bien["id_locataire"]); ?>">            <input type="hidden" id="initial_id_locataire" value="<?php echo htmlspecialchars($bien["id_locataire"]); ?>">

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
            <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($communeNom); ?>" required>
            <input type="hidden" id="id_commune_hidden" name="id_commune" value="<?php echo htmlspecialchars($bien["id_commune"]); ?>">
            <input type="hidden" id="initial_id_commune" value="<?php echo htmlspecialchars($bien["id_commune"]); ?>">

            <button type="submit">Mettre à jour le bien</button>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
    <script src="/js/photo-upload.js"></script>
    <script>
        $(function() {
            // Autocomplétion pour les propriétaires
            $("#proprietaire_search").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/admin/api/users/search", // Assurez-vous que cette route existe et renvoie les utilisateurs
                        dataType: "json",
                        data: {
                            term: request.term,
                            role: "Proprietaire" // Filtrer par rôle si nécessaire
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                let label = '';
                                if (item.type_locataire === 'physique') {
                                    label = item.prenom_locataire + ' ' + item.nom_locataire;
                                } else if (item.type_locataire === 'morale') {
                                    label = item.RaisonSociale + ' (Siret: ' + item.Siret + ')';
                                }
                                return {
                                    label: label,
                                    value: label,
                                    id: item.id_locataire
                                };
                            }));
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#id_locataire").val(ui.item.id);
                },
                change: function(event, ui) {
                    if (!ui.item) {
                        // Si l'utilisateur efface le champ ou ne sélectionne pas dans la liste
                        // Réinitialiser l'ID à la valeur initiale ou à null
                        $("#id_locataire").val($("#initial_id_locataire").val());
                        // Optionnel: effacer le champ si la valeur n'est pas valide
                        // $(this).val("");
                    }
                }
            });

            // Autocomplétion pour les communes (pré-remplissage déjà géré par la valeur)
            $("#commune_search_register").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "/admin/api/communes/search", // Assurez-vous que cette route existe
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.nom_commune + ' (' + item.code_postal + ')',
                                    value: item.nom_commune + ' (' + item.code_postal + ')',
                                    id: item.id_commune
                                };
                            }));
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    $("#id_commune_hidden").val(ui.item.id);
                },
                change: function(event, ui) {
                    if (!ui.item) {
                        // Si l'utilisateur efface le champ ou ne sélectionne pas dans la liste
                        // Réinitialiser l'ID à la valeur initiale ou à null
                        $("#id_commune_hidden").val($("#initial_id_commune").val());
                        // Optionnel: effacer le champ si la valeur n'est pas valide
                        // $(this).val("");
                    }
                }
            });
        });
    </script>

</body>
</html>
