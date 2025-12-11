<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires signalés - Admin</title>
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
        <h2>Gestion des Commentaires signalés</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="padding: 1rem; margin-bottom: 1rem; background: #d4edda; color: #155724; border-radius: 8px;">
                ✓ <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div style="padding: 1rem; margin-bottom: 1rem; background: #f8d7da; color: #721c24; border-radius: 8px;">
                ✗ <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($commentairesSignales)): ?>
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">✨</div>
                <h3>Aucun commentaire signalé</h3>
                <p>Tous les commentaires sont en règle !</p>
            </div>
        <?php else: ?>
            <table id="admintable" class="display">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Auteur</th>
                        <th>Bien</th>
                        <th>Note</th>
                        <th>Titre</th>
                        <th>Aperçu</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commentairesSignales as $commentaire): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($commentaire['id_commentaire']); ?></td>
                            <td><?php echo htmlspecialchars($commentaire['prenom_locataire'] . ' ' . $commentaire['nom_locataire']); ?></td>
                            <td>
                                <a href="/bien/<?php echo $commentaire['id_biens']; ?>" target="_blank">
                                    <?php echo htmlspecialchars($commentaire['designation_bien']); ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($commentaire['note']): ?>
                                    <?php echo str_repeat('★', $commentaire['note']) . str_repeat('☆', 5 - $commentaire['note']); ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($commentaire['titre'] ?? '-'); ?></td>
                            <td>
                                <?php 
                                $contenu = $commentaire['contenu'];
                                $apercu = mb_strlen($contenu) > 50 ? mb_substr($contenu, 0, 50) . '...' : $contenu;
                                echo htmlspecialchars($apercu); 
                                ?>
                                <?php if (mb_strlen($contenu) > 50): ?>
                                    <a href="javascript:void(0)" onclick="voirCommentaire(<?php echo $commentaire['id_commentaire']; ?>)" style="display:block; margin-top:0.25rem; font-size:0.85rem;">Voir plus</a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($commentaire['date_creation'])); ?></td>
                            <td>
                                <span style="padding: 0.25rem 0.6rem; border-radius: 12px; font-size: 0.85rem; font-weight: 500; background: <?php echo $commentaire['statut'] === 'publie' ? '#d4edda' : ($commentaire['statut'] === 'rejete' ? '#f8d7da' : '#fff3cd'); ?>; color: <?php echo $commentaire['statut'] === 'publie' ? '#155724' : ($commentaire['statut'] === 'rejete' ? '#721c24' : '#856404'); ?>;">
                                    <?php 
                                    $statuts = ['publie' => '✓ Publié', 'en_attente' => '⏳ En attente', 'rejete' => '✗ Rejeté'];
                                    echo $statuts[$commentaire['statut']] ?? $commentaire['statut'];
                                    ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($commentaire['statut'] === 'publie'): ?>
                                    <a href="javascript:void(0)" onclick="approuverCommentaire(<?php echo $commentaire['id_commentaire']; ?>)">Approuver</a>
                                    <a href="javascript:void(0)" onclick="rejeterCommentaire(<?php echo $commentaire['id_commentaire']; ?>)">Rejeter</a>
                                <?php endif; ?>
                                <a href="javascript:void(0)" onclick="supprimerCommentaire(<?php echo $commentaire['id_commentaire']; ?>)">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Modal -->
        <div id="modal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6);">
            <div style="position: relative; margin: 5% auto; padding: 2rem; background: white; width: 80%; max-width: 600px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <span onclick="fermerModal()" style="position: absolute; top: 1rem; right: 1.5rem; font-size: 2rem; cursor: pointer; color: #999;">&times;</span>
                <h3 style="margin-bottom: 1rem;">Commentaire complet</h3>
                <div id="modalContent"></div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#admintable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                    },
                    pageLength: 25,
                    order: [[0, 'desc']]
                });
            });

            const commentaires = <?php echo json_encode($commentairesSignales ?? []); ?>;

            function approuverCommentaire(id) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/commentaire/approuver/' + id;
                document.body.appendChild(form);
                form.submit();
            }

            function rejeterCommentaire(id) {
                if (confirm('Rejeter ce commentaire ? Il ne sera plus visible publiquement.')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/admin/commentaire/rejeter/' + id;
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function supprimerCommentaire(id) {
                if (confirm('Supprimer définitivement ce commentaire ?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/commentaire/delete/' + id;
                    document.body.appendChild(form);
                    form.submit();
                }
            }

            function voirCommentaire(id) {
                const c = commentaires.find(x => x.id_commentaire == id);
                if (!c) return;
                
                let html = '';
                if (c.titre) html += `<h4>${escapeHtml(c.titre)}</h4>`;
                html += `<p style="white-space: pre-wrap; margin: 1rem 0;">${escapeHtml(c.contenu)}</p>`;
                html += `<hr style="margin: 1rem 0;"><small><strong>Auteur:</strong> ${escapeHtml(c.prenom_locataire + ' ' + c.nom_locataire)}<br><strong>Date:</strong> ${new Date(c.date_creation).toLocaleString('fr-FR')}</small>`;
                
                document.getElementById('modalContent').innerHTML = html;
                document.getElementById('modal').style.display = 'block';
            }

            function fermerModal() {
                document.getElementById('modal').style.display = 'none';
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Fermer avec Escape ou clic extérieur
            document.addEventListener('keydown', e => { if (e.key === 'Escape') fermerModal(); });
            document.getElementById('modal')?.addEventListener('click', e => { if (e.target.id === 'modal') fermerModal(); });
        </script>
    </main>
</body>
</html>
