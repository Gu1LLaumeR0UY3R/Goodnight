<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <style>
        :root {
            --primary: #ff9800;
            --primary-dark: #fb8c00;
            --secondary: #4caf50;
            --text-dark: #2c3e50;
            --text-light: #666;
            --bg-light: #f9f9f9;
            --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
            --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
            --radius: 12px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff8f0 0%, #ffffff 100%);
            margin: 0;
            color: var(--text-dark);
            padding-top: 80px;
        }

        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-container {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 2px solid var(--primary);
        }

        .profile-header h1 {
            color: var(--primary);
            font-size: 2.2rem;
            margin: 0 0 0.5rem 0;
        }

        .profile-picture-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .pfp-container {
            position: relative;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 4px solid var(--primary);
        }

        .pfp-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pfp-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 4rem;
            font-weight: bold;
        }

        .pfp-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
        }

        .btn-upload {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-upload:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        #pfpFileInput {
            display: none;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .info-card {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        .info-card h3 {
            color: var(--primary);
            margin: 0 0 1rem 0;
            font-size: 1.3rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #ddd;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-dark);
        }

        .info-value {
            color: var(--text-light);
            text-align: right;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 1.5rem;
            }

            .profile-header h1 {
                font-size: 1.8rem;
            }

            .pfp-container {
                width: 150px;
                height: 150px;
            }

            .pfp-placeholder {
                font-size: 3rem;
            }

            .info-row {
                flex-direction: column;
                gap: 0.5rem;
            }

            .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="profile-container">
            <div class="profile-header">
                <h1>Mon Profil</h1>
                <p style="color: var(--text-light); font-size: 1.1rem;">Gérez vos informations personnelles</p>
            </div>

            <!-- Alerts -->
            <div id="alertContainer"></div>

            <!-- Photo de profil -->
            <div class="profile-picture-section">
                <div class="pfp-container">
                    <?php if (!empty($user['pfp_loca'])): ?>
                        <img id="pfpImage" src="<?php echo htmlspecialchars($user['pfp_loca']); ?>" alt="Photo de profil">
                    <?php else: ?>
                        <div class="pfp-placeholder" id="pfpPlaceholder">
                            <?php 
                                $initial = '';
                                if (!empty($user['prenom_locataire'])) {
                                    $initial = strtoupper(substr($user['prenom_locataire'], 0, 1));
                                } elseif (!empty($user['RaisonSociale'])) {
                                    $initial = strtoupper(substr($user['RaisonSociale'], 0, 1));
                                }
                                echo htmlspecialchars($initial);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="pfp-actions">
                    <label for="pfpFileInput" class="btn-upload">
                        📷 Changer la photo
                    </label>
                    <input type="file" id="pfpFileInput" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                    
                    <?php if (!empty($user['pfp_loca'])): ?>
                        <button class="btn-delete" onclick="deleteProfilePicture()">
                            🗑️ Supprimer la photo
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Informations utilisateur -->
            <div class="profile-info">
                <div class="info-card">
                    <h3>Informations Personnelles</h3>
                    <?php if (!empty($user['RaisonSociale'])): ?>
                        <!-- Personne morale -->
                        <div class="info-row">
                            <span class="info-label">Raison Sociale:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['RaisonSociale']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">SIRET:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['Siret'] ?? 'Non renseigné'); ?></span>
                        </div>
                    <?php else: ?>
                        <!-- Personne physique -->
                        <div class="info-row">
                            <span class="info-label">Nom:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['nom_locataire'] ?? 'Non renseigné'); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Prénom:</span>
                            <span class="info-value"><?php echo htmlspecialchars($user['prenom_locataire'] ?? 'Non renseigné'); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date de naissance:</span>
                            <span class="info-value">
                                <?php 
                                    if (!empty($user['dateNaissance_locataire'])) {
                                        echo date('d/m/Y', strtotime($user['dateNaissance_locataire']));
                                    } else {
                                        echo 'Non renseignée';
                                    }
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="info-card">
                    <h3>Contact</h3>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email_locataire']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Téléphone:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['tel_locataire'] ?? 'Non renseigné'); ?></span>
                    </div>
                </div>

                <?php if (!empty($user['rue_locataire'])): ?>
                <div class="info-card">
                    <h3>Adresse</h3>
                    <div class="info-row">
                        <span class="info-label">Rue:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['rue_locataire']); ?></span>
                    </div>
                    <?php if (!empty($user['complement_locataire'])): ?>
                    <div class="info-row">
                        <span class="info-label">Complément:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['complement_locataire']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>© <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script>
        // Upload de photo de profil
        document.getElementById('pfpFileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Vérification de la taille (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                showAlert('Le fichier est trop volumineux. Taille maximale: 5MB', 'error');
                return;
            }

            // Vérification du type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showAlert('Type de fichier non autorisé. Formats acceptés: JPG, PNG, GIF, WEBP', 'error');
                return;
            }

            // Upload
            const formData = new FormData();
            formData.append('profile_picture', file);

            fetch('/profile/uploadProfilePicture', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Recharger la page pour afficher la nouvelle photo
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.error || 'Erreur lors de l\'upload', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Erreur lors de l\'upload de la photo', 'error');
            });
        });

        // Suppression de photo de profil
        function deleteProfilePicture() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')) {
                return;
            }

            fetch('/profile/deleteProfilePicture', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Recharger la page pour afficher le placeholder
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.error || 'Erreur lors de la suppression', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Erreur lors de la suppression de la photo', 'error');
            });
        }

        // Affichage des alertes
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'success' ? 'alert-success' : 
                              type === 'error' ? 'alert-error' : 'alert-info';
            
            alertContainer.innerHTML = `
                <div class="alert ${alertClass}">
                    ${message}
                </div>
            `;

            // Faire disparaître l'alerte après 5 secondes
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
    </script>
</body>
</html>
