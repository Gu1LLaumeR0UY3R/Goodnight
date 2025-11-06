<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Utilisateur - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
	        /* Correction de positionnement pour intl-tel-input */
	        .iti {
	            width: 100% !important; /* Assure que le conteneur intl-tel-input prend toute la largeur disponible */
	            display: flex !important; /* Utiliser flex pour forcer l'alignement */
	        }
	        .iti .iti__country-container {
	            flex-shrink: 0 !important; /* Empêche le sélecteur de rétrécir */
	        }
	        .iti input.iti__tel-input {
	            flex-grow: 1 !important; /* Permet au champ de saisie de prendre l'espace restant */
	            padding-right: 0 !important; /* Corrige le padding si nécessaire */
	        }
    </style>
    <link rel="stylesheet" href="/lib/intl-tel-input/intlTelInput.min.css">
</head>
<body>

    <main>
        <h2>Modifier l'utilisateur : <?php echo htmlspecialchars($user["email_locataire"]); ?></h2>
        <form action="/admin/editUser/<?php echo htmlspecialchars($user["id_locataire"]); ?>" method="POST">
            <?php if (empty($user["Siret"]) || empty($user["RaisonSociale"])): ?>
                <label for="nom_locataire">Nom :</label>
                <input type="text" id="nom_locataire" name="nom_locataire" value="<?php echo htmlspecialchars($user["nom_locataire"] ?? ''); ?>">

                <label for="prenom_locataire">Prénom :</label>
                <input type="text" id="prenom_locataire" name="prenom_locataire" value="<?php echo htmlspecialchars($user["prenom_locataire"] ?? ''); ?>">
            <?php else: ?>
                <label for="RaisonSociale">Raison Sociale :</label>
                <input type="text" id="RaisonSociale" name="RaisonSociale" value="<?php echo htmlspecialchars($user["RaisonSociale"] ?? ''); ?>">

                <label for="Siret">SIRET :</label>
                <input type="text" id="Siret" name="Siret" value="<?php echo htmlspecialchars($user["Siret"] ?? ''); ?>">
            <?php endif; ?>

            <label for="email_locataire">Email :</label>
            <input type="email" id="email_locataire" name="email_locataire" value="<?php echo htmlspecialchars($user["email_locataire"]); ?>" required>

            <label for="tel_locataire">Téléphone :</label>
            <input type="tel" id="tel_locataire" name="tel_locataire" value="<?php echo htmlspecialchars($user["tel_locataire"] ?? ''); ?>" maxlength="15">
            <input type="hidden" id="full_tel_locataire" name="full_tel_locataire">

            <label for="id_commune">Commune :</label>
            <select id="id_commune" name="id_commune">
                <option value="">Sélectionner une commune</option>
                <?php foreach ($communes as $commune): ?>
                    <option value="<?php echo htmlspecialchars($commune["id_commune"]); ?>" <?php echo ($user["id_commune"] == $commune["id_commune"]) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($commune["ville_nom"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <h3>Rôles de l'utilisateur :</h3>
            <?php foreach ($roles as $role): ?>
                <input type="checkbox" id="role_<?php echo htmlspecialchars($role["id_roles"]); ?>" name="roles[]" value="<?php echo htmlspecialchars($role["id_roles"]); ?>"
                    <?php echo in_array($role["id_roles"], $userRoleIds) ? "checked" : ""; ?>>
                <label for="role_<?php echo htmlspecialchars($role["id_roles"]); ?>"><?php echo htmlspecialchars($role["nom_roles"]); ?></label><br>
            <?php endforeach; ?>

            <button type="submit">Mettre à jour l'utilisateur</button>
        </form>

        <button onclick="window.location.href='/admin/users'">Retour</button>
        
    </main>

    <script src="/lib/intl-tel-input/intlTelInput.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation de intl-tel-input
            const input = document.querySelector("#tel_locataire");
            const fullTelInput = document.querySelector("#full_tel_locataire");
            const iti = window.intlTelInput(input, {
                initialCountry: "fr", // Pays initial par défaut
                separateDialCode: true,
                utilsScript: "" // Le script utils.js n'est pas nécessaire pour la validation de base
            });

            // Mettre à jour le champ caché avec le numéro complet
            function updateFullTel() {
                if (iti.isValidNumber()) {
                    fullTelInput.value = iti.getNumber();
                } else {
                    fullTelInput.value = ""; // Vider si invalide
                }
            }

            // Mettre à jour le champ caché au chargement avec la valeur existante
            if (input.value) {
                iti.setNumber(input.value);
                updateFullTel();
            }

            input.addEventListener("change", updateFullTel);
            input.addEventListener("keyup", updateFullTel);

            // Empêcher la saisie de caractères non numériques et mettre à jour le champ caché
            input.addEventListener("input", function() {
                // Supprimer immédiatement tout caractère non numérique
                this.value = this.value.replace(/[^0-9]/g, '');
                updateFullTel();
            });
        });
    </script>
</body>
</html>