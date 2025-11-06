<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Administrateur - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <style>
    #draggable { width: 150px; height: 150px; padding: 0.5em; }
    </style>
</head>
<body>

    <main>
        <h2>Gestion des Administrateurs</h2>
        <a href="/admin/addAdmin">Ajouter un Administrateur</a>

        <table id="admintable" class="display">
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
                            <a href="/admin/editAdmin/<?= $admin["id_admin"] ?>">Modifier</a>
                            <a href="/admin/deleteAdmin/<?= $admin["id_admin"] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet administrateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
    </table>
    <script>
        $(document).ready(function() {
          $('#admintable').DataTable({
            language: {
            "sProcessing": "Traitement en cours...",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sZeroRecords": "Aucun élément à afficher",
            "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré de _MAX_ éléments au total)",
            "sInfoPostFix": "",
            "sSearch": "Rechercher :",
            "sUrl": "",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sLoadingRecords": "Chargement en cours...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            }
            }
        });
    });
    </script>
    </main>
</body>
</html>
