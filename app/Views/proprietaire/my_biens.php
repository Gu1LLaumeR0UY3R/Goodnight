<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Biens - Propriétaire</title>
    <link rel="stylesheet" href="/css/style.css">
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
        <div class="page-header">
            <h2>Mes Biens</h2>
            <a href="/proprietaire/addBien" class="btn btn-primary">+ Ajouter un nouveau bien</a>
        </div>
        
        <?php if (!empty($biens)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Adresse</th>
                        <th>Superficie</th>
                        <th>Couchages</th>
                        <th>Animaux</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($biens as $bien): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bien["designation_bien"]); ?></td>
                            <td><?php echo htmlspecialchars($bien["rue_biens"] . ($bien["complement_biens"] ? ", " . $bien["complement_biens"] : "")); ?></td>
                            <td><?php echo htmlspecialchars($bien["superficie_biens"]); ?> m²</td>
                            <td><?php echo htmlspecialchars($bien["nb_couchage"]); ?></td>
                            <td><?php echo $bien["animaux_biens"] ? "Oui" : "Non"; ?></td>
                            <td class="actions">
                                <a href="/proprietaire/editBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-edit">Modifier</a>
                                <a href="/proprietaire/deleteBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bien ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p>Vous n'avez pas encore ajouté de biens.</p>
                <a href="/proprietaire/addBien" class="btn btn-primary">Ajouter votre premier bien</a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
