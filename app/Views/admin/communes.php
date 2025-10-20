<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Communes - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>

    <main>
        <h2>Gestion des Communes</h2>
        <p>La liste des communes est généralement gérée via une base de données externe et n'est pas modifiable directement via cette interface.</p>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la commune</th>
                    <th>Code Postal</th>
                    <th>Département</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($communes as $commune): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($commune["id_commune"]); ?></td>
                        <td><?php echo htmlspecialchars($commune["ville_nom"]); ?></td>
                        <td><?php echo htmlspecialchars($commune["ville_code_postal"]); ?></td>
                        <td><?php echo htmlspecialchars($commune["ville_departement"]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
