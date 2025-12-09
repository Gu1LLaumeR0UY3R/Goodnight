<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Biens - Propriétaire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/tables.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <style>
        main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        /* Table moderne avec bordures */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid var(--border-color, #e0e0e0);
        }

        .dark-mode table {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        table thead {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
        }

        .dark-mode table thead {
            background: linear-gradient(135deg, var(--night-stellar), var(--night-nebula));
        }

        table th {
            padding: 1.25rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color, #e0e0e0);
            border-right: 1px solid var(--border-color, #e0e0e0);
            color: var(--text-primary);
            vertical-align: middle;
        }

        table td:last-child {
            border-right: none;
        }

        table tbody tr {
            transition: all 0.2s ease;
        }

        table tbody tr:hover {
            background: rgba(255, 90, 95, 0.05);
            transform: scale(1.005);
        }

        .dark-mode table tbody tr:hover {
            background: rgba(102, 252, 241, 0.05);
        }

        table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn, .btn-view, .btn-edit, .btn-delete, .btn-primary, .btn-secondary {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
            border: none;
        }

        .btn-primary {
            background: var(--accent-primary, #ff5a5f);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-hover, #ff7f83);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 90, 95, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn-view {
            background: #17a2b8;
            color: white;
        }

        .btn-view:hover {
            background: #138496;
            transform: translateY(-2px);
        }

        .btn-edit {
            background: #ffc107;
            color: #212529;
        }

        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        /* Badges */
        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-block;
            cursor: help;
        }

        .badge-warning {
            background: #ffa726;
            color: white;
        }

        .badge-success {
            background: #66bb6a;
            color: white;
        }

        .badge-refused {
            background: #ef5350;
            color: white;
        }

        /* Flash messages */
        .flash-message {
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            border-radius: 8px;
            font-weight: 500;
            border-left: 4px solid;
        }

        .flash-success {
            background: #d4edda;
            color: #155724;
            border-color: #28a745;
        }

        .flash-warning {
            background: #fff3cd;
            color: #856404;
            border-color: #ffc107;
        }

        .flash-error {
            background: #f8d7da;
            color: #721c24;
            border-color: #dc3545;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 2px dashed var(--border-color, #e0e0e0);
        }

        .dark-mode .empty-state {
            background: var(--bg-card);
        }

        .empty-state p {
            font-size: 1.125rem;
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }

        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            color: var(--text-secondary);
            font-size: 0.9375rem;
            border-top: 1px solid var(--border-color);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            table {
                font-size: 0.875rem;
            }
            
            table th,
            table td {
                padding: 0.875rem 0.75rem;
            }
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-header h2 {
                font-size: 1.5rem;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash-message flash-<?php echo $_SESSION['flash']['type']; ?>">
                <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        
        <div class="page-header">
            <h2>Mes Biens</h2>
            <a href="/proprietaire/addBien" class="btn btn-primary">+ Ajouter un nouveau bien</a>
        </div>
        
        <?php if (!empty($biens)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Désignation</th>
                        <th>Adresse</th>
                        <th>Superficie</th>
                        <th>Couchages</th>
                        <th>Animaux</th>
                        <th>Statut</th>
                        <th>Prestations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($biens as $bien): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bien["designation_bien"]); ?></td>
                            <td><?php echo htmlspecialchars($bien["rue_biens"] . ($bien["complement_biens"] ? ", " . $bien["complement_biens"] : "")); ?></td>
                            <td><?php echo htmlspecialchars($bien["superficie_biens"]); ?> m²</td>
                            <td><?php echo htmlspecialchars($bien["nb_couchage"]); ?></td>
                            <td><?php echo $bien["animaux_biens"] ? "Oui" : "Non"; ?></td>
                            <td>
                                <?php 
                                $statut = $bien["statut_validation"] ?? 'en_attente';
                                $badges = [
                                    'en_attente' => ['class' => 'badge-warning', 'icon' => '🟡', 'label' => 'En attente'],
                                    'valide' => ['class' => 'badge-success', 'icon' => '🟢', 'label' => 'Validé'],
                                    'refuse' => ['class' => 'badge-refused', 'icon' => '🔴', 'label' => 'Refusé']
                                ];
                                $badge = $badges[$statut];
                                ?>
                                <span class="badge <?php echo $badge['class']; ?>" 
                                      title="<?php echo $statut === 'refuse' && !empty($bien['motif_refus']) ? 'Motif: ' . htmlspecialchars($bien['motif_refus']) : ''; ?>">
                                    <?php echo $badge['icon'] . ' ' . $badge['label']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="/proprietaire/managePrestations/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn btn-secondary">Gérer</a>
                            </td>
                            <td class="actions">
                                <a href="/proprietaire/viewBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-view">Voir</a>
                                <a href="/proprietaire/editBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-edit">Modifier</a>
                                <a href="/proprietaire/deleteBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bien ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p>Vous n'avez pas encore ajouté de biens.</p>
                <a href="/proprietaire/addBien" class="btn btn-primary">Ajouter votre premier bien</a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
