<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Types de Biens - Admin</title>
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
        <h2>Gestion des Types de Biens</h2>
        <a href="/admin/addTypeBien">Ajouter un nouveau type de bien</a>
        <table id="admintable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Désignation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($typesBiens as $typeBien): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($typeBien["id_typebien"]); ?></td>
                        <td><?php echo htmlspecialchars($typeBien["desc_type_bien"]); ?></td>
                        <td>
                            <a href="/admin/editTypeBien/<?php echo htmlspecialchars($typeBien["id_typebien"]); ?>">Modifier</a>
                            <a href="/admin/deleteTypeBien/<?php echo htmlspecialchars($typeBien["id_typebien"]); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de bien ?');">Supprimer</a>
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
