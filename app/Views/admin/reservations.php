<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>

    <main>
        <h2>Gestion des Réservations</h2>
        <a href="/admin/addReservation">Ajouter une nouvelle réservation</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Locataire</th>
                    <th>Bien</th>
                    <th>Tarif</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation["id_reservation"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["date_debut"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["date_fin"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["id_locataire"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["id_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($reservation["id_tarif"]); ?></td>
                        <td>
                            <a href="/admin/editReservation/<?php echo htmlspecialchars($reservation["id_reservation"]); ?>">Modifier</a>
                            <a href="/admin/deleteReservation/<?php echo htmlspecialchars($reservation["id_reservation"]); ?>" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette réservation ?\');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
