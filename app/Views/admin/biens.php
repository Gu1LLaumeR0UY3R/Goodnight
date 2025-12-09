<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Biens - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/admin-content.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
</head>
<body>

    <main>
        <h2>Gestion des Biens</h2>
        <a href="/admin/addBien">Ajouter un nouveau bien</a>
        <table id="admintable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du bien</th>
                    <th>Propriétaire</th>
                    <th>Statut</th>
                    <th>Rue</th>
                    <th>Complément</th>
                    <th>Superficie</th>
                    <th>Description</th>
                    <th>Animaux autorisés</th>
                    <th>Couchage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($biens as $bien): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bien["id_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["designation_bien"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["proprietaire"]); ?></td>
                        <td>
                            <select class="statut-select" data-bien-id="<?php echo $bien['id_biens']; ?>" style="padding: 5px 10px; border-radius: 4px; border: 1px solid #ddd; cursor: pointer;">
                                <option value="en_attente" <?php echo ($bien['statut_validation'] === 'en_attente') ? 'selected' : ''; ?>>🟡 En attente</option>
                                <option value="valide" <?php echo ($bien['statut_validation'] === 'valide') ? 'selected' : ''; ?>>🟢 Validé</option>
                                <option value="refuse" <?php echo ($bien['statut_validation'] === 'refuse') ? 'selected' : ''; ?>>🔴 Refusé</option>
                            </select>
                        </td>
                        <td><?php echo htmlspecialchars($bien["rue_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["complement_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["superficie_biens"]); ?></td>
                        <td><?php echo htmlspecialchars($bien["description_biens"]); ?></td>
                        <td><?php echo $bien["animaux_biens"] ? "Oui" : "Non"; ?></td>
                        <td><?php echo htmlspecialchars($bien["nb_couchage"]); ?></td>
                        <td>
                            <a href="/admin/editBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>">Modifier</a>
                            <a href="/admin/deleteBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bien ?');">Supprimer</a>
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

            // Gestion du changement de statut
            $('.statut-select').on('change', function() {
                const bienId = $(this).data('bien-id');
                const newStatut = $(this).val();
                const selectElement = $(this);
                
                let confirmMessage = '';
                if (newStatut === 'valide') {
                    confirmMessage = 'Valider ce bien ? Il sera visible publiquement.';
                } else if (newStatut === 'refuse') {
                    confirmMessage = 'Refuser ce bien ?';
                } else if (newStatut === 'en_attente') {
                    confirmMessage = 'Remettre ce bien en attente de validation ?';
                }
                
                if (confirm(confirmMessage)) {
                    $.ajax({
                        url: '/admin/updateStatutBien/' + bienId,
                        method: 'POST',
                        data: { statut: newStatut },
                        success: function(response) {
                            const data = JSON.parse(response);
                            if (data.success) {
                                alert('Statut mis à jour avec succès');
                                location.reload();
                            } else {
                                alert('Erreur lors de la mise à jour: ' + data.message);
                                location.reload();
                            }
                        },
                        error: function() {
                            alert('Erreur de connexion');
                            location.reload();
                        }
                    });
                } else {
                    location.reload();
                }
            });
        });
        </script>
    </main>
</body>
</html>
