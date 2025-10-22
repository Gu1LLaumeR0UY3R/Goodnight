<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Administrateur - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<div class="container mt-4">
    <h2>Gestion des Administrateurs</h2>
    <a href="/admin/addAdmin" class="btn btn-success mb-3">Ajouter un Administrateur</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Est Admin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= htmlspecialchars($admin["id_admin"]) ?></td>
                    <td><?= htmlspecialchars($admin["nom_admin"]) ?></td>
                    <td><?= htmlspecialchars($admin["email_admin"]) ?></td>
                    <td><?= $admin["is_admin"] ? "Oui" : "Non" ?></td>
                    <td>
                        <a href="/admin/editAdmin/<?= $admin["id_admin"] ?>" class="btn btn-primary btn-sm">Modifier</a>
                        <a href="/admin/deleteAdmin/<?= $admin["id_admin"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
