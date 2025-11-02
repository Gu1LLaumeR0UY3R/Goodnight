<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Réservation - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>

    <main>
        <h2>Ajouter une nouvelle Réservation</h2>
        <form action="/admin/addReservation" method="POST">
            <label for="id_biens">Bien :</label>
            <select id="id_biens" name="id_biens" required>
                <option value="">Sélectionnez un bien</option>
                <?php foreach ($biens as $bien): ?>
                    <option value="<?php echo htmlspecialchars($bien['id_biens']); ?>"><?php echo htmlspecialchars($bien['designation_bien']); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="id_locataire">Locataire :</label>
            <select id="id_locataire" name="id_locataire" required>
                <option value="">Sélectionnez un locataire</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo htmlspecialchars($user['id_locataire']); ?>">
                        <?php echo htmlspecialchars($user['prenom_locataire'] . ' ' . $user['nom_locataire'] . (!empty($user['RaisonSociale']) ? ' (' . $user['RaisonSociale'] . ')' : '')); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date_debut">Date du début :</label>
            <input type="date" id="date_debut" name="date_debut" required>

            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" required>

            <label for="id_tarif">Tarif :</label>
            <select id="id_tarif" name="id_tarif" required>
                <option value="">Sélectionnez un tarif</option>
                <?php foreach ($tarifs as $tarif): ?>
                    <option value="<?php echo htmlspecialchars($tarif['id_tarif']); ?>"><?php echo htmlspecialchars($tarif['prix_semaine'] . ' €/semaine (Saison ' . $tarif['id_saison'] . ', Année ' . $tarif['annee'] . ')'); ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Ajouter la réservation</button>
        </form>

        <button onclick="window.location.href='/admin/reservations'">Retour</button>

    </main>

</body>
</html>
