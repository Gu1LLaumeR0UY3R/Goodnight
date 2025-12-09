<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signalements - Admin</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/admin-content.css">
    <link rel="stylesheet" href="/app/Views/admin/DataTables/datatables.min.css">
    <style>
        .badge-motif {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        .motif-contenu_inapproprie { background: #ff5252; color: white; }
        .motif-fausses_informations { background: #ffa726; color: white; }
        .motif-photos_trompeuses { background: #42a5f5; color: white; }
        .motif-arnaque { background: #d32f2f; color: white; }
        .motif-autre { background: #9e9e9e; color: white; }

        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-traiter {
            background: #66bb6a;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-traiter:hover {
            background: #4caf50;
        }
        .btn-rejeter {
            background: #e0e0e0;
            color: #333;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-rejeter:hover {
            background: #bdbdbd;
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
            width: 600px;
            max-width: 90%;
        }
        .modal-content h3 {
            margin-top: 0;
            color: #333;
        }
        .modal-content .info-group {
            margin: 15px 0;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 6px;
        }
        .modal-content .info-group strong {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 13px;
        }
        .modal-content textarea {
            width: 100%;
            min-height: 80px;
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
        .btn-confirm-traiter {
            background: #66bb6a;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-confirm-traiter:hover {
            background: #4caf50;
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
        
        <div class="admin-header">
            <h1>Signalements</h1>
            <p>Gérez les signalements des utilisateurs</p>
        </div>

        <?php if (empty($signalements)): ?>
            <div class="alert alert-info" style="background: #e3f2fd; padding: 20px; border-radius: 8px; text-align: center;">
                ✓ Aucun signalement en attente pour le moment.
            </div>
        <?php else: ?>
            <table id="signalementsTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Bien</th>
                        <th>Propriétaire</th>
                        <th>Motif</th>
                        <th>Signaleur</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($signalements as $sig): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($sig['designation_bien']); ?></td>
                            <td><?php echo htmlspecialchars($sig['proprietaire']); ?></td>
                            <td>
                                <span class="badge-motif motif-<?php echo $sig['motif']; ?>">
                                    <?php 
                                    $motifs = [
                                        'contenu_inapproprie' => 'Contenu inapproprié',
                                        'fausses_informations' => 'Fausses informations',
                                        'photos_trompeuses' => 'Photos trompeuses',
                                        'arnaque' => 'Arnaque',
                                        'autre' => 'Autre'
                                    ];
                                    echo $motifs[$sig['motif']] ?? $sig['motif'];
                                    ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($sig['signaleur']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($sig['date_signalement'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="/bien/<?php echo $sig['id_biens']; ?>" target="_blank" class="btn-view" title="Voir le bien">👁️</a>
                                    <button onclick="showDetailsModal(<?php echo htmlspecialchars(json_encode($sig)); ?>)" class="btn-traiter" title="Voir détails">📋</button>
                                    <button onclick="traiterSignalement(<?php echo $sig['id_signalement']; ?>)" class="btn-traiter" title="Marquer comme traité">✅</button>
                                    <button onclick="rejeterSignalement(<?php echo $sig['id_signalement']; ?>)" class="btn-rejeter" title="Rejeter">❌</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <!-- Modal détails -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <h3>Détails du signalement</h3>
            <div id="modalContent"></div>
            <div class="modal-buttons">
                <button onclick="closeModal()" class="btn-cancel">Fermer</button>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/app/Views/admin/DataTables/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#signalementsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                },
                order: [[4, 'desc']] // Trier par date décroissante
            });
        });

        function showDetailsModal(signalement) {
            const motifs = {
                'contenu_inapproprie': 'Contenu inapproprié',
                'fausses_informations': 'Fausses informations',
                'photos_trompeuses': 'Photos trompeuses',
                'arnaque': 'Arnaque',
                'autre': 'Autre'
            };

            const content = `
                <div class="info-group">
                    <strong>Bien signalé:</strong>
                    ${signalement.designation_bien}
                </div>
                <div class="info-group">
                    <strong>Motif:</strong>
                    ${motifs[signalement.motif] || signalement.motif}
                </div>
                ${signalement.description ? `
                    <div class="info-group">
                        <strong>Description:</strong>
                        ${signalement.description}
                    </div>
                ` : ''}
                <div class="info-group">
                    <strong>Signalé par:</strong>
                    ${signalement.signaleur}
                </div>
                <div class="info-group">
                    <strong>Propriétaire du bien:</strong>
                    ${signalement.proprietaire}
                </div>
                <div class="info-group">
                    <strong>Date:</strong>
                    ${new Date(signalement.date_signalement).toLocaleString('fr-FR')}
                </div>
            `;

            document.getElementById('modalContent').innerHTML = content;
            document.getElementById('detailsModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }

        function traiterSignalement(id) {
            if (confirm('Marquer ce signalement comme traité ?')) {
                window.location.href = '/admin/traiterSignalement/' + id;
            }
        }

        function rejeterSignalement(id) {
            if (confirm('Rejeter ce signalement ?')) {
                window.location.href = '/admin/rejeterSignalement/' + id;
            }
        }

        // Fermer la modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
