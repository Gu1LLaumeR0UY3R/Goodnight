<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Admins - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
</head>
<div class="container mt-4">
    <h2>Ajouter un Administrateur</h2>
    <form action="/admin/addAdmin" method="POST">
        <div class="mb-3">
            <label for="nom_admin" class="form-label">Nom :</label>
            <input type="text" class="form-control" id="nom_admin" name="nom_admin" required>
        </div>
        <div class="mb-3">
            <label for="email_admin" class="form-label">Email :</label>
            <input type="email" class="form-control" id="email_admin" name="email_admin" required>
        </div>
        <div class="mb-3">
            <label for="mot_de_passe" class="form-label">Mot de passe :</label>
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin" value="1">
            <label class="form-check-label" for="is_admin">Est Administrateur</label>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="/admin/admins" class="btn btn-secondary">Annuler</a>
    </form>
</div>
