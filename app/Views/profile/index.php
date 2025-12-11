<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/profile.css">
    <link rel="stylesheet" href="/cadre/frames.css">
    
    <!-- 🔓 Easter Egg débloqué ! Vous avez trouvé le secret ! 
         Visitez cette page pour débloquer les cadres de profils spéciaux :
         /profile/cadre
         🎨 Profitez de cette découverte exclusive ! -->
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

            <!-- Photo de profil avec cadre -->
            <div class="profile-picture-section">
                <div class="pfp-container" id="pfpContainerWithFrame">
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
                    
                    <!-- Afficher le sélecteur de cadres si débloqué -->
                    <?php if (!empty($user['frames_unlocked']) && $user['frames_unlocked']): ?>
                        <div style="margin-top: 1.5rem; padding: 1.5rem; background: #f5f5f5; border-radius: 8px;">
                            <h3 style="margin-top: 0;">🎨 Votre Cadre de Profil</h3>
                            <p style="font-size: 0.9rem; color: #666; margin-bottom: 1rem;">Sélectionnez un cadre spécial pour votre photo :</p>
                            
                            <div id="framesSelector" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                                <!-- Les cadres seront générés en JS -->
                            </div>
                            
                            <!-- Infos du cadre sélectionné -->
                            <div id="frameInfo" style="background: white; padding: 1rem; border-radius: 6px; margin-bottom: 1rem; border-left: 4px solid #667eea;">
                                <h4 style="margin: 0 0 0.5rem 0; color: #333;">Sélectionnez un cadre</h4>
                                <p style="margin: 0; color: #666; font-size: 0.9rem;">Choisissez un cadre pour découvrir sa description</p>
                            </div>
                            
                            <button onclick="applyFrame()" class="btn-save" style="padding: 0.7rem 1.5rem; font-size: 0.95rem;">
                                ✓ Appliquer le cadre
                            </button>
                        </div>
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

        // ========== EASTER EGG - CADRES DE PROFIL ==========
        
        // Données des cadres disponibles avec chemins PNG
        const framesData = [
            { id: 'default', name: 'Par défaut', description: 'Pas de cadre, affichage normal', path: null },
            { id: 'gold', name: 'Or Préstigieux', description: 'Un élégant cadre doré avec effet de prestige', path: '/cadre/images/gold.png' },
            { id: 'silver', name: 'Argent Raffiné', description: 'Un cadre argenté à l\'éclat subtil', path: '/cadre/images/silver.png' },
            { id: 'bronze', name: 'Bronze Antique', description: 'Un cadre de bronze avec une teinte antique', path: '/cadre/images/bronze.png' },
            { id: 'rainbow', name: 'Arc-en-ciel', description: 'Un cadre aux couleurs arc-en-ciel vibrant', path: '/cadre/images/rainbow.png' },
            { id: 'glacier', name: 'Glacier Bleu', description: 'Un cadre glacé aux tons bleus froids', path: '/cadre/images/glacier.png' },
            { id: 'pink', name: 'Rose Flamant', description: 'Un cadre rose doux et élégant', path: '/cadre/images/pink.png' },
            { id: 'emerald', name: 'Émeraude', description: 'Un cadre vert émeraude profond', path: '/cadre/images/emerald.png' },
            { id: 'mystique', name: 'Violet Mystique', description: 'Un cadre violet mystérieux et enchanteur', path: '/cadre/images/mystique.png' }
        ];

        let selectedFramePath = '<?php echo htmlspecialchars($user['cadre_profil'] ?? ''); ?>' || null;
        
        // Déterminer l'ID de cadre à partir du chemin stocké en base de données
        let selectedFrameId = 'default';
        if (selectedFramePath) {
            const frame = framesData.find(f => f.path === selectedFramePath);
            if (frame) {
                selectedFrameId = frame.id;
            }
        }


        // Rendre les cadres disponibles
        function renderFrameSelector() {
            const container = document.getElementById('framesSelector');
            if (!container) return;

            framesData.forEach(frame => {
                const frameBtn = document.createElement('button');
                frameBtn.className = 'frame-btn';
                frameBtn.id = 'frame-' + frame.id;
                frameBtn.type = 'button';
                
                // Afficher l'image ou un placeholder
                if (frame.path) {
                    frameBtn.innerHTML = `<img src="${frame.path}" alt="${frame.name}">`;
                } else {
                    frameBtn.innerHTML = `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 2rem;">⭐</div>`;
                }
                
                frameBtn.style.cssText = `
                    width: 100px;
                    height: 100px;
                    padding: 0;
                    border: 3px solid #ddd;
                    background: white;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    overflow: hidden;
                `;

                if (frame.id === selectedFrameId) {
                    frameBtn.style.borderColor = '#667eea';
                    frameBtn.style.boxShadow = '0 0 0 2px white, 0 0 0 4px #667eea';
                }

                frameBtn.addEventListener('click', () => selectFrame(frame.id, frameBtn));
                container.appendChild(frameBtn);
            });
        }

        // Sélectionner un cadre
        function selectFrame(frameId, element) {
            selectedFrameId = frameId;
            
            // Mettre à jour le style de sélection
            document.querySelectorAll('.frame-btn').forEach(btn => {
                btn.style.borderColor = '#ddd';
                btn.style.boxShadow = 'none';
            });
            
            element.style.borderColor = '#667eea';
            element.style.boxShadow = '0 0 0 2px white, 0 0 0 4px #667eea';
            
            // Afficher les infos du cadre
            const frame = framesData.find(f => f.id === frameId);
            if (frame) {
                const infoDiv = document.getElementById('frameInfo');
                if (infoDiv) {
                    infoDiv.innerHTML = `
                        <h4>${frame.name}</h4>
                        <p>${frame.description}</p>
                    `;
                }
            }
            
            // Appliquer l'aperçu du cadre en temps réel
            applyFrameStyle(frameId);
        }

        // Appliquer le cadre visuellement (PNG overlay)
        function applyFrameStyle(frameId) {
            const container = document.getElementById('pfpContainerWithFrame');
            if (!container) return;

            // Trouver le frame dans framesData
            const frame = framesData.find(f => f.id === frameId);
            if (!frame) return;

            // Supprimer les anciens overlays PNG
            let existingOverlay = container.querySelector('.frame-overlay');
            if (existingOverlay) {
                existingOverlay.remove();
            }

            if (frameId === 'default' || !frame.path) {
                // Pas d'overlay pour le cadre par défaut
                container.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.1)';
            } else {
                // Créer et appliquer l'overlay PNG
                const overlay = document.createElement('img');
                overlay.src = frame.path;
                overlay.alt = frame.name;
                overlay.className = 'frame-overlay';
                overlay.style.position = 'absolute';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.pointerEvents = 'none';
                overlay.style.zIndex = '10';
                overlay.style.borderRadius = '12px';
                
                // Charger l'image avec gestion d'erreur
                overlay.onerror = function() {
                    console.warn('Cadre image non trouvée:', frame.path);
                };
                
                container.appendChild(overlay);
                container.style.position = 'relative';
                container.style.boxShadow = '0 0 15px rgba(0, 0, 0, 0.2)';
            }
        }

        // Appliquer le cadre (envoi au serveur)
        function applyFrame() {
            if (!selectedFrameId) {
                showAlert('Veuillez sélectionner un cadre !', 'error');
                return;
            }

            // Récupérer le chemin du cadre
            const frame = framesData.find(f => f.id === selectedFrameId);
            const framePath = frame.path; // null pour 'default', chemin pour les autres

            fetch('/profile/updateFrame', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cadre_profil: framePath
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(`✓ Cadre "${framesData.find(f => f.id === selectedFrameId).name}" appliqué !`, 'success');
                } else {
                    showAlert(data.error || 'Erreur lors de l\'application du cadre', 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('Erreur lors de l\'application du cadre', 'error');
            });
        }

        // Appliquer le cadre au chargement
        document.addEventListener('DOMContentLoaded', () => {
            renderFrameSelector();
            applyFrameStyle(selectedFrameId);
        });
    </script>
</body>
</html>
