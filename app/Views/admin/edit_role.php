<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Rôle - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<body>

    <main>
        <h2>Modifier le Rôle</h2>
        <form action="/admin/editRole/<?php echo htmlspecialchars($role["id_roles"]); ?>" method="POST">
            <label for="nom_roles">Nom du rôle :</label>
            <input type="text" id="nom_roles" name="nom_roles" value="<?php echo htmlspecialchars($role["nom_roles"]); ?>" required>
            <button type="submit">Mettre à jour le rôle</button>
        </form>

        <button onclick="window.location.href='/admin/roles'">Retour</button>
        
    </main>

</body>
</html>
