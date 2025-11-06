
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">

</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="bien-details">
            <div class="bien-header">
                <h1><?php echo htmlspecialchars($bien["designation_bien"]); ?></h1>

            </div>

            <div class="bien-photos">
                <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $photo): ?>
                        <img src="<?php echo htmlspecialchars($photo["lien_photo"]); ?>" alt="<?php echo htmlspecialchars($photo["nom_photo"]); ?>">
                    <?php endforeach; ?>
                <?php else: ?>
                    <img src="/images/default.jpg" alt="Aucune photo disponible">
                <?php endif; ?>
            </div>

            <div class="bien-info">
                <div class="info-block">
                    <h3>Informations Générales</h3>
                    <p><strong>Type :</strong> <?php echo htmlspecialchars($bien["type_bien_nom"]); ?></p>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($bien["rue_biens"]); ?>, <?php echo htmlspecialchars($bien["complement_biens"]); ?></p>
                    <p><strong>Commune :</strong> <?php echo htmlspecialchars($bien["commune_nom"]); ?></p>
                    <p><strong>Superficie :</strong> <?php echo htmlspecialchars($bien["superficie_biens"]); ?> m²</p>
                    <p><strong>Nombre de couchages :</strong> <?php echo htmlspecialchars($bien["nb_couchage"]); ?></p>
                    <p><strong>Animaux acceptés :</strong> <?php echo $bien["animaux_biens"] ? 'Oui' : 'Non'; ?></p>
                    <p><strong>Prix semaine actuel :</strong> <?php echo htmlspecialchars(($bien["prix_semaine"] ?? null) ? number_format($bien["prix_semaine"], 2, ',', ' ') . ' €' : 'Non renseigné'); ?></p>
                </div>
                <div class="info-block">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($bien["description_biens"])); ?></p>
                </div>

                <?php 
                // Affichage du formulaire de réservation
                if (isset($_SESSION['user_id']) && !isset($_SESSION['is_admin']) && (in_array('Locataire', $_SESSION['user_roles'] ?? []) || in_array('Proprietaire', $_SESSION['user_roles'] ?? []))):
                ?>
                    <div class="info-block">
                        <h3>Réserver ce bien</h3>

                        <?php 
                        // Récupération des erreurs et des données de formulaire
                        $errors = $_SESSION['errors'] ?? [];
                        $old_input = $_SESSION['old_input'] ?? [];
                        unset($_SESSION['errors'], $_SESSION['old_input']);

                        if (!empty($errors)): 
                        ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="/reservation/store" method="POST" class="form-reservation">
                            <input type="hidden" name="id_biens" value="<?php echo htmlspecialchars($bien['id_biens']); ?>">
                            
                            <div class="form-group">
                                <label for="date_debut">Date de début :</label>
                                <input type="date" id="date_debut" name="date_debut" required 
                                    value="<?php echo htmlspecialchars($old_input['date_debut'] ?? date('Y-m-d')); ?>"
                                    min="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="date_fin">Date de fin :</label>
                                <input type="date" id="date_fin" name="date_fin" required 
                                    value="<?php echo htmlspecialchars($old_input['date_fin'] ?? date('Y-m-d', strtotime('+7 days'))); ?>"
                                    min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            </div>

                            <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                        </form>
                    </div>
                <?php elseif (!isset($_SESSION['user_id']) || isset($_SESSION['is_admin']) || (!in_array('Locataire', $_SESSION['user_roles'] ?? []) && !in_array('Proprietaire', $_SESSION['user_roles'] ?? []))): ?>
                    <div class="info-block">
                        <h3>Réserver ce bien</h3>
                        <p>Veuillez vous <a href="/login">connecter</a> pour effectuer une réservation.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
