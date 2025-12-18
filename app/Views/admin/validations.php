<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation des Biens - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/admin-content.css">
    <link rel="stylesheet" href="/app/Views/admin/DataTables/datatables.min.css">
    <style>
        .badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        .badge-new {
            background: #42a5f5;
            color: white;
        }
        .badge-modified {
            background: #ffa726;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-validate {
            background: #66bb6a;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-validate:hover {
            background: #4caf50;
        }
        .btn-refuse {
            background: #ef5350;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-refuse:hover {
            background: #e53935;
        }
        .btn-view {
            background: #42a5f5;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-view:hover {
            background: #1e88e5;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: 8px;
            width: 500px;
            max-width: 90%;
        }
        .modal-content h3 {
            margin-top: 0;
            color: #333;
        }
        .modal-content textarea {
            width: 100%;
            min-height: 100px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            resize: vertical;
        }
        .modal-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: flex-end;
        }
        .btn-cancel {
            background: #9e9e9e;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-cancel:hover {
            background: #757575;
        }
        .btn-confirm-refuse {
            background: #ef5350;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-confirm-refuse:hover {
            background: #e53935;
        }
    </style>
</head>
<body>

    <main class="admin-container">
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash-message flash-<?php echo $_SESSION['flash']['type']; ?>" style="padding: 15px 20px; margin: 20px 0; border-radius: 8px; font-weight: 500;">
                <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        
        <div class="admin-page-header">
            <div class="aph-main">
                <p class="aph-eyebrow">Administration</p>
                <div class="aph-title-row">
                    <h1>Validation des biens</h1>
                    <span class="aph-chip">En attente : <?php echo count($biensEnAttente ?? []); ?></span>
                </div>
                <p class="aph-sub">Gérez les biens en attente de validation.</p>
            </div>
        </div>

        <?php if (empty($biensEnAttente)): ?>
            <div class="alert alert-info" style="background: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;">
                ✓ Aucun bien en attente de validation pour le moment.
            </div>
        <?php else: ?>
            <table id="validationsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Type</th>
                        <th>Propriétaire</th>
                        <th>Commune</th>
                        <th>Date soumission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($biensEnAttente as $bien): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bien['designation_bien']); ?></td>
                            <td><?php echo htmlspecialchars($bien['type_bien_nom']); ?></td>
                            <td><?php echo htmlspecialchars($bien['proprietaire']); ?></td>
                            <td><?php echo htmlspecialchars($bien['commune_nom'] ?? 'Non renseignée'); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($bien['date_soumission'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/bien/<?php echo $bien['id_biens']; ?>" target="_blank" class="btn-view" title="Voir le bien">👁️</a>
                                    <button onclick="validerBien(<?php echo $bien['id_biens']; ?>)" class="btn-validate" title="Valider">✅</button>
                                    <button onclick="showRefuseModal(<?php echo $bien['id_biens']; ?>, '<?php echo htmlspecialchars($bien['designation_bien']); ?>')" class="btn-refuse" title="Refuser">❌</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <!-- Modal de refus -->
    <div id="refuseModal" class="modal">
        <div class="modal-content">
            <h3>Refuser le bien "<span id="refuseBienName"></span>"</h3>
            <label for="motif_refus">Motif du refus (optionnel) :</label>
            <textarea id="motif_refus" placeholder="Ex: Photos de mauvaise qualité, description incomplète, informations incorrectes..."></textarea>
            <div class="modal-buttons">
                <button onclick="closeRefuseModal()" class="btn-cancel">Annuler</button>
                <button onclick="confirmRefus()" class="btn-confirm-refuse">Confirmer le refus</button>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/app/Views/admin/DataTables/datatables.min.js"></script>
    <script>
        let currentBienId = null;

        $(document).ready(function() {
            $('#validationsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                },
                order: [[4, 'desc']] // Trier par date de soumission décroissante
            });
        });

        function validerBien(id) {
            if (confirm('Êtes-vous sûr de vouloir valider ce bien ?')) {
                window.location.href = '/admin/validerBien/' + id;
            }
        }

        function showRefuseModal(id, nom) {
            currentBienId = id;
            document.getElementById('refuseBienName').textContent = nom;
            document.getElementById('motif_refus').value = '';
            document.getElementById('refuseModal').style.display = 'block';
        }

        function closeRefuseModal() {
            document.getElementById('refuseModal').style.display = 'none';
            currentBienId = null;
        }

        function confirmRefus() {
            const motif = document.getElementById('motif_refus').value;
            const url = '/admin/refuserBien/' + currentBienId + (motif ? '?motif=' + encodeURIComponent(motif) : '');
            window.location.href = url;
        }

        // Fermer la modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('refuseModal');
            if (event.target === modal) {
                closeRefuseModal();
            }
        }
    </script>
</body>
</html>
