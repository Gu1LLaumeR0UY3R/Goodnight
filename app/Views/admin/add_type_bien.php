<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Type de Bien - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

    <main>
        <h2>Ajouter un nouveau Type de Bien</h2>
        <form action="/admin/addTypeBien" method="POST">
            <label for="designation_bien">Désignation du type de bien :</label>
            <input type="text" id="designation_bien" name="desc_type_bien" required>
            <button type="submit">Ajouter le type de bien</button>
        </form>
    </main>

</body>
</html>
