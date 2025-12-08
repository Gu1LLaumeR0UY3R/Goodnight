<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Biens - Propriétaire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
        .badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
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
        .flash-message {
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 8px;
            font-weight: 500;
        }
        .flash-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .flash-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .flash-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
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
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>
