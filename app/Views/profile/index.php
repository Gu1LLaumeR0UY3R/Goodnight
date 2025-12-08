<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/profile.css">
</head>
<body class="profile-page">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="profile-main">
        <div class="profile-container">
            <div class="profile-header">
                <h1>Mon Profil</h1>
                <p class="profile-subtitle">Gérez vos informations personnelles</p>
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

            <!-- Boutons d'action -->
            <div class="profile-actions">
                <button id="editBtn" class="btn-edit" onclick="toggleEditMode()">
                    ✏️ Modifier mes informations
                </button>
                <button id="saveBtn" class="btn-save" style="display: none;" onclick="saveProfile()">
                    💾 Enregistrer
                </button>
                <button id="cancelBtn" class="btn-cancel" style="display: none;" onclick="cancelEdit()">
                    ❌ Annuler
                </button>
            </div>

            <!-- Informations utilisateur -->
            <div class="profile-info" id="profileInfo">
                <div class="info-card">
                    <h3>Informations Personnelles</h3>
                    <?php if (!empty($user['RaisonSociale'])): ?>
                        <!-- Personne morale -->
                        <div class="info-row">
                            <span class="info-label">Raison Sociale:</span>
                            <span class="info-value" data-field="RaisonSociale"><?php echo htmlspecialchars($user['RaisonSociale']); ?></span>
                            <input type="text" class="info-input" data-field="RaisonSociale" value="<?php echo htmlspecialchars($user['RaisonSociale']); ?>" style="display: none;">
                        </div>
                        <div class="info-row">
                            <span class="info-label">SIRET:</span>
                            <span class="info-value" data-field="Siret"><?php echo htmlspecialchars($user['Siret'] ?? 'Non renseigné'); ?></span>
                            <input type="text" class="info-input" data-field="Siret" value="<?php echo htmlspecialchars($user['Siret'] ?? ''); ?>" placeholder="14 chiffres" maxlength="14" style="display: none;">
                        </div>
                    <?php else: ?>
                        <!-- Personne physique -->
                        <div class="info-row">
                            <span class="info-label">Nom:</span>
                            <span class="info-value" data-field="nom_locataire"><?php echo htmlspecialchars($user['nom_locataire'] ?? 'Non renseigné'); ?></span>
                            <input type="text" class="info-input" data-field="nom_locataire" value="<?php echo htmlspecialchars($user['nom_locataire'] ?? ''); ?>" style="display: none;">
                        </div>
                        <div class="info-row">
                            <span class="info-label">Prénom:</span>
                            <span class="info-value" data-field="prenom_locataire"><?php echo htmlspecialchars($user['prenom_locataire'] ?? 'Non renseigné'); ?></span>
                            <input type="text" class="info-input" data-field="prenom_locataire" value="<?php echo htmlspecialchars($user['prenom_locataire'] ?? ''); ?>" style="display: none;">
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date de naissance:</span>
                            <span class="info-value" data-field="dateNaissance_locataire">
                                <?php 
                                    if (!empty($user['dateNaissance_locataire'])) {
                                        echo date('d/m/Y', strtotime($user['dateNaissance_locataire']));
                                    } else {
                                        echo 'Non renseignée';
                                    }
                                ?>
                            </span>
                            <input type="date" class="info-input" data-field="dateNaissance_locataire" value="<?php echo htmlspecialchars($user['dateNaissance_locataire'] ?? ''); ?>" style="display: none;">
                        </div>
                    <?php endif; ?>
                    
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value" data-field="email_locataire"><?php echo htmlspecialchars($user['email_locataire']); ?></span>
                        <input type="email" class="info-input" data-field="email_locataire" value="<?php echo htmlspecialchars($user['email_locataire']); ?>" style="display: none;">
                    </div>
                    <div class="info-row">
                        <span class="info-label">Téléphone:</span>
                        <span class="info-value" data-field="tel_locataire"><?php echo htmlspecialchars($user['tel_locataire'] ?? 'Non renseigné'); ?></span>
                        <input type="tel" class="info-input" data-field="tel_locataire" value="<?php echo htmlspecialchars($user['tel_locataire'] ?? ''); ?>" placeholder="+33 6 12 34 56 78" style="display: none;">
                    </div>
                    <div class="info-row">
                        <span class="info-label">Rue:</span>
                        <span class="info-value" data-field="rue_locataire"><?php echo htmlspecialchars($user['rue_locataire'] ?? 'Non renseignée'); ?></span>
                        <input type="text" class="info-input" data-field="rue_locataire" value="<?php echo htmlspecialchars($user['rue_locataire'] ?? ''); ?>" style="display: none;">
                    </div>
                    <div class="info-row">
                        <span class="info-label">Complément:</span>
                        <span class="info-value" data-field="complement_locataire"><?php echo htmlspecialchars($user['complement_locataire'] ?? 'Non renseigné'); ?></span>
                        <input type="text" class="info-input" data-field="complement_locataire" value="<?php echo htmlspecialchars($user['complement_locataire'] ?? ''); ?>" placeholder="Appartement, étage..." style="display: none;">
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="profile-footer">
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

            // Faire défiler vers le haut pour voir l'alerte
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Faire disparaître l'alerte après 5 secondes
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Mode édition
        let isEditMode = false;
        let originalValues = {};

        function toggleEditMode() {
            isEditMode = true;
            
            // Sauvegarder les valeurs originales
            document.querySelectorAll('.info-input').forEach(input => {
                originalValues[input.dataset.field] = input.value;
            });

            // Masquer les valeurs en lecture seule et afficher les inputs
            document.querySelectorAll('.info-value').forEach(el => el.style.display = 'none');
            document.querySelectorAll('.info-input').forEach(el => el.style.display = 'block');

            // Basculer les boutons
            document.getElementById('editBtn').style.display = 'none';
            document.getElementById('saveBtn').style.display = 'inline-block';
            document.getElementById('cancelBtn').style.display = 'inline-block';
        }

        function cancelEdit() {
            isEditMode = false;

            // Restaurer les valeurs originales
            document.querySelectorAll('.info-input').forEach(input => {
                input.value = originalValues[input.dataset.field] || '';
            });

            // Masquer les inputs et afficher les valeurs en lecture seule
            document.querySelectorAll('.info-value').forEach(el => el.style.display = 'block');
            document.querySelectorAll('.info-input').forEach(el => el.style.display = 'none');

            // Réactiver les boutons
            document.getElementById('saveBtn').disabled = false;
            document.getElementById('cancelBtn').disabled = false;

            // Basculer les boutons
            document.getElementById('editBtn').style.display = 'inline-block';
            document.getElementById('saveBtn').style.display = 'none';
            document.getElementById('cancelBtn').style.display = 'none';

            // Effacer les messages d'erreur
            document.getElementById('alertContainer').innerHTML = '';
        }

        function saveProfile() {
            // Collecter les données du formulaire
            const formData = {};
            document.querySelectorAll('.info-input').forEach(input => {
                formData[input.dataset.field] = input.value;
            });

            // Validation côté client
            const errors = [];
            
            // Email obligatoire
            if (!formData.email_locataire || !formData.email_locataire.trim()) {
                errors.push('L\'email est obligatoire');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email_locataire)) {
                errors.push('Format d\'email invalide');
            }

            // Validation selon le type de personne
            if (formData.RaisonSociale !== undefined) {
                // Personne morale
                if (!formData.RaisonSociale || !formData.RaisonSociale.trim()) {
                    errors.push('La raison sociale est obligatoire');
                }
                if (formData.Siret && !/^\d{14}$/.test(formData.Siret.replace(/\s/g, ''))) {
                    errors.push('Le SIRET doit contenir 14 chiffres');
                }
            } else {
                // Personne physique
                if (!formData.nom_locataire || !formData.nom_locataire.trim()) {
                    errors.push('Le nom est obligatoire');
                }
                if (!formData.prenom_locataire || !formData.prenom_locataire.trim()) {
                    errors.push('Le prénom est obligatoire');
                }
            }

            // Validation du téléphone
            if (formData.tel_locataire && !/^[0-9\s\+\-\(\)]+$/.test(formData.tel_locataire)) {
                errors.push('Numéro de téléphone invalide');
            }

            // Afficher les erreurs si présentes
            if (errors.length > 0) {
                showAlert(errors.join('<br>'), 'error');
                return;
            }

            // Désactiver les boutons pendant l'envoi
            document.getElementById('saveBtn').disabled = true;
            document.getElementById('cancelBtn').disabled = true;

            // Envoyer les données au serveur
            console.log('Envoi des données:', formData);
            
            fetch('/profile/updateProfile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Erreur de parsing JSON:', e);
                        throw new Error('La réponse du serveur n\'est pas du JSON valide: ' + text);
                    }
                });
            })
            .then(data => {
                console.log('Data reçue:', data);
                
                if (data.success) {
                    showAlert(data.message, 'success');
                    
                    // Mettre à jour les valeurs affichées
                    document.querySelectorAll('.info-input').forEach(input => {
                        const field = input.dataset.field;
                        const value = input.value;
                        const displayValue = value || 'Non renseigné';
                        
                        // Mise à jour de la valeur affichée
                        const valueSpan = document.querySelector(`.info-value[data-field="${field}"]`);
                        if (valueSpan) {
                            // Formatage spécial pour la date
                            if (field === 'dateNaissance_locataire' && value) {
                                const date = new Date(value);
                                valueSpan.textContent = date.toLocaleDateString('fr-FR');
                            } else {
                                valueSpan.textContent = displayValue;
                            }
                        }
                    });

                    // Quitter le mode édition
                    setTimeout(() => {
                        cancelEdit();
                    }, 1500);
                } else {
                    // Afficher les erreurs serveur
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).join('<br>');
                        showAlert(errorMessages, 'error');
                    } else {
                        showAlert(data.error || 'Erreur lors de la mise à jour', 'error');
                    }
                    
                    // Réactiver les boutons
                    document.getElementById('saveBtn').disabled = false;
                    document.getElementById('cancelBtn').disabled = false;
                }
            })
            .catch(error => {
                console.error('Erreur complète:', error);
                showAlert('Erreur lors de la mise à jour du profil: ' + error.message, 'error');
                
                // Réactiver les boutons
                document.getElementById('saveBtn').disabled = false;
                document.getElementById('cancelBtn').disabled = false;
            });
        }
    </script>
</body>
</html>
