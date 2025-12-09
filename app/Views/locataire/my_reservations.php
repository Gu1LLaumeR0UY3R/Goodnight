<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - Locataire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <style>
        main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        h2 {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 2rem;
            text-align: center;
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            padding: 2rem;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(255, 90, 95, 0.3);
        }

        .dark-mode h2 {
            background: linear-gradient(135deg, var(--night-stellar), var(--night-nebula));
        }

        /* Messages d'alerte */
        .alert {
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            font-weight: 600;
            text-align: center;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border: 2px solid #4caf50;
            color: #2e7d32;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
        }

        .alert-error {
            background: linear-gradient(135deg, #ffebee, #ffcdd2);
            border: 2px solid #f44336;
            color: #c62828;
            box-shadow: 0 2px 8px rgba(244, 67, 54, 0.2);
        }

        /* Table */
        table {
            width: 100%;
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode table {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        thead {
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
        }

        .dark-mode thead {
            background: linear-gradient(135deg, var(--night-stellar), var(--night-nebula));
        }

        th {
            padding: 1.25rem 1rem;
            text-align: left;
            font-weight: 700;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        tbody tr {
            border-bottom: 1px solid var(--border-color, #e0e0e0);
            transition: all 0.2s;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: rgba(255, 90, 95, 0.05);
            transform: scale(1.002);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        td {
            padding: 1.25rem 1rem;
            color: var(--text-primary);
            font-size: 0.9375rem;
            vertical-align: middle;
        }

        /* Photo miniature */
        .photo-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--border-color, #e0e0e0);
            margin-right: 0.75rem;
            vertical-align: middle;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Bouton annuler */
        .btn-cancel {
            padding: 0.625rem 1.25rem;
            background: linear-gradient(135deg, #f44336, #e57373);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
        }

        .btn-cancel:hover {
            background: linear-gradient(135deg, #e53935, #ef5350);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
        }

        /* Message vide */
        main > p {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
            font-size: 1.125rem;
            font-style: italic;
            background: white;
            border: 2px dashed var(--border-color, #e0e0e0);
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode main > p {
            background: var(--bg-card);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
            color: var(--text-secondary);
            font-size: 0.9375rem;
            border-top: 1px solid var(--border-color);
        }

        footer p {
            padding: 0;
            background: none;
            border: none;
            box-shadow: none;
            font-style: normal;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            h2 {
                font-size: 1.75rem;
                padding: 1.5rem;
            }

            /* Mode carte pour mobile */
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                margin-bottom: 1.5rem;
                border: 2px solid var(--border-color, #e0e0e0);
                border-radius: 12px;
                background: white;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            }

            .dark-mode tr {
                background: var(--bg-card);
            }

            tr:hover {
                transform: none;
            }

            td {
                border: none;
                position: relative;
                padding-left: 45%;
                text-align: right;
            }

            td:before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: 40%;
                padding-right: 1rem;
                white-space: nowrap;
                font-weight: 700;
                text-align: left;
                color: var(--accent-primary, #ff5a5f);
            }

            .photo-thumb {
                float: right;
            }

            .btn-cancel {
                width: 100%;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2>📅 Mes Réservations</h2>

        <!-- Messages -->
        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (!empty($reservations)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Bien</th>
                        <th>Propriétaire</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Commune</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td data-label="Bien">
                                <?php if (!empty($reservation['premiere_photo'])): ?>
                                    <img src="<?= htmlspecialchars($reservation['premiere_photo']) ?>" 
                                         alt="Photo" class="photo-thumb">
                                <?php endif; ?>
                                <?= htmlspecialchars($reservation["designation_bien"]) ?>
                            </td>
                            <td data-label="Propriétaire">
                                <?= htmlspecialchars($reservation["nom_proprietaire"] . " " . $reservation["prenom_proprietaire"]) ?>
                            </td>
                            <td data-label="Date début"><?= htmlspecialchars($reservation["date_debut"]) ?></td>
                            <td data-label="Date fin"><?= htmlspecialchars($reservation["date_fin"]) ?></td>
                            <td data-label="Commune"><?= htmlspecialchars($reservation["commune_nom"]) ?></td>
                            <td data-label="Action">
                                <form action="/reservation/cancel/<?= $reservation['id_reservation'] ?>" 
                                      method="POST" 
                                      style="display:inline;"
                                      onsubmit="return confirm('Annuler cette réservation ?');">
                                    <button type="submit" class="btn-cancel">❌ Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Vous n'avez aucune réservation en cours.</p>
        <?php endif; ?>
    </main>

    <footer>
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; <?= date("Y") ?> GlobeNight. Tous droits réservés.</p>
    </footer>
</body>
</html>