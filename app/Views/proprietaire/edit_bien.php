<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Bien - Propriétaire</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/photo-upload.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            min-height: calc(100vh - 80px);
        }

        main h2 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Alerte info */
        .alert {
            padding: 1.25rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .alert-info {
            background: #e3f2fd;
            border-color: #2196f3;
            color: #0d47a1;
        }

        .alert strong {
            font-weight: 700;
        }

        .alert ul {
            margin: 0.75rem 0 0 1.5rem;
        }

        .alert li {
            margin: 0.25rem 0;
        }

        /* Formulaire */
        form {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode form {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            margin-top: 1.25rem;
            font-size: 0.9375rem;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            background: white;
            color: var(--text-primary);
            transition: all 0.2s;
        }

        .dark-mode input[type="text"],
        .dark-mode input[type="number"],
        .dark-mode input[type="email"],
        .dark-mode select,
        .dark-mode textarea {
            background: var(--bg-primary);
            border-color: var(--border-color);
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent-primary, #ff5a5f);
            box-shadow: 0 0 0 3px rgba(255, 90, 95, 0.1);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: var(--accent-primary, #ff5a5f);
        }

        /* Section tarification */
        .form-section {
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
            background: rgba(0, 0, 0, 0.02);
        }

        .dark-mode .form-section {
            background: rgba(255, 255, 255, 0.02);
            border-color: var(--border-color);
        }

        .form-section legend {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            padding: 0 0.75rem;
        }

        /* Accordéon pour les tarifs */
        .accordion-container {
            margin: 1.5rem 0;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }

        .dark-mode .accordion-container {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .accordion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem;
            cursor: pointer;
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            transition: all 0.2s;
        }

        .dark-mode .accordion-header {
            background: linear-gradient(135deg, var(--night-stellar), var(--night-nebula));
        }

        .accordion-header:hover {
            background: linear-gradient(135deg, var(--accent-hover, #ff7f83), #ff9999);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 90, 95, 0.3);
        }

        .accordion-title {
            font-size: 1.125rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .accordion-icon {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .accordion-header.active .accordion-icon {
            transform: rotate(180deg);
        }

        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease;
        }

        .accordion-content.active {
            max-height: 2000px;
        }

        .tarifs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }

        .tarif-item {
            background: rgba(0, 0, 0, 0.02);
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            padding: 1.25rem;
            transition: all 0.2s;
        }

        .dark-mode .tarif-item {
            background: rgba(255, 255, 255, 0.02);
            border-color: var(--border-color);
        }

        .tarif-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--accent-primary, #ff5a5f);
        }

        .tarif-item h4 {
            margin: 0 0 1rem 0;
            color: var(--accent-primary, #ff5a5f);
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        /* Photos */
        h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid var(--accent-primary, #ff5a5f);
        }

        .photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .photo-item {
            position: relative;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            padding: 0.5rem;
            text-align: center;
            background: white;
            transition: all 0.2s;
        }

        .dark-mode .photo-item {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        .photo-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .photo-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .photo-item a {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .photo-item a:hover {
            background: #c82333;
            transform: scale(1.05);
        }

        /* Bouton submit */
        button[type="submit"] {
            width: 100%;
            padding: 1rem 2rem;
            background: var(--accent-primary, #ff5a5f);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 2rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        button[type="submit"]:hover {
            background: var(--accent-hover, #ff7f83);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 90, 95, 0.4);
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
        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            main h2 {
                font-size: 1.5rem;
            }

            form {
                padding: 1.5rem;
            }

            .photos-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }
        }
    </style>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2>Modifier le Bien : <?php echo htmlspecialchars($bien["designation_bien"]); ?></h2>
        
        <div class="alert alert-info" style="background: #e3f2fd; padding: 15px; border-left: 4px solid #2196f3; margin-bottom: 20px;">
            ℹ️ <strong>Attention :</strong> La modification des champs suivants nécessite une re-validation par un administrateur :
            <ul style="margin: 10px 0 0 20px;">
                <li>Désignation du bien</li>
                <li>Description</li>
                <li>Type de bien</li>
                <li>Photos (ajout/suppression)</li>
            </ul>
            Votre bien sera remis en attente et ne sera plus visible publiquement jusqu'à validation.
        </div>
        
        <form action="/proprietaire/editBien/<?php echo htmlspecialchars($bien["id_biens"]); ?>" method="POST" enctype="multipart/form-data" id="editBienForm" onsubmit="return confirmCriticalChanges()">
            <label for="designation_bien">Désignation du bien :</label>
            <input type="text" id="designation_bien" name="designation_bien" value="<?php echo htmlspecialchars($bien["designation_bien"]); ?>" required>

            <label for="rue_biens">Rue :</label>
            <input type="text" id="rue_biens" name="rue_biens" value="<?php echo htmlspecialchars($bien["rue_biens"]); ?>" required>

            <label for="complement_biens">Complément d'adresse :</label>
            <input type="text" id="complement_biens" name="complement_biens" value="<?php echo htmlspecialchars($bien["complement_biens"]); ?>">

            <label for="superficie_biens">Superficie (m²) :</label>
            <input type="number" id="superficie_biens" name="superficie_biens" step="0.01" value="<?php echo htmlspecialchars($bien["superficie_biens"]); ?>" required>

            <label for="description_biens">Description :</label>
            <textarea id="description_biens" name="description_biens"><?php echo htmlspecialchars($bien["description_biens"]); ?></textarea>

            <label for="animaux_biens">Animaux acceptés :</label>
            <input type="checkbox" id="animaux_biens" name="animaux_biens" value="1" <?php echo $bien["animaux_biens"] ? "checked" : ""; ?>>

            <label for="nb_couchage">Nombre de couchages :</label>
            <input type="number" id="nb_couchage" name="nb_couchage" value="<?php echo htmlspecialchars($bien["nb_couchage"]); ?>" required>

            <label for="id_TypeBien">Type de bien :</label>
            <select id="id_TypeBien" name="id_TypeBien" required>
                <?php foreach ($typesBiens as $type): ?>
                    <option value="<?php echo htmlspecialchars($type["id_typebien"]); ?>" <?php echo ($bien["id_TypeBien"] == $type["id_typebien"]) ? "selected" : ""; ?>>
                        <?php echo htmlspecialchars($type["desc_type_bien"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_commune">Commune :</label>
            <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($bien["commune_nom"] ?? ''); ?>" placeholder="Commencez à taper le nom de la commune...">
            <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($bien["id_commune"] ?? ''); ?>">

            <fieldset class="form-section">
                <legend>Tarification (Prix par jour)</legend>
                
                <div class="accordion-container" id="tarifs-accordion">
                    <div class="accordion-header" onclick="toggleAllTarifs()">
                        <div class="accordion-title">
                            <span>💰</span>
                            <span>Gérer les tarifs par saison</span>
                            <span style="font-size: 0.875rem; font-weight: 400; opacity: 0.9;">(<?php echo count($saisons); ?> saisons)</span>
                        </div>
                        <span class="accordion-icon" id="accordion-icon">▼</span>
                    </div>
                    <div class="accordion-content" id="accordion-content-tarifs">
                        <div class="tarifs-grid">
                            <?php foreach ($saisons as $saison): ?>
                                <div class="tarif-item">
                                    <h4>
                                        <span>📅</span>
                                        <span><?php echo htmlspecialchars($saison["lib_saison"]); ?></span>
                                    </h4>
                                    <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][id_saison]" value="<?php echo htmlspecialchars($saison["id_saison"]); ?>">
                                    
                                    <div class="form-group">
                                        <label for="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>">Prix par jour (€) :</label>
                                        <?php
                                            $currentYear = date('Y');
                                            $tarifKey = $saison['id_saison'] . '_' . $currentYear;
                                            $prixSemaine = $tarifsMapped[$tarifKey] ?? '';
                                        ?>
                                        <input type="number" 
                                               id="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>" 
                                               name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][prix_semaine]" 
                                               step="0.01" 
                                               min="0" 
                                               placeholder="Ex: 150.00"
                                               value="<?php echo htmlspecialchars($prixSemaine); ?>">
                                        <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][annee]" value="<?php echo htmlspecialchars($currentYear); ?>">
                                        <small style="display: block; margin-top: 0.5rem; color: var(--text-secondary); font-size: 0.875rem;">
                                            💡 Laissez vide si non applicable
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <h3>Photos actuelles :</h3>
            <div class="photos-grid">
                <?php if (!empty($photos)): ?>
                    <?php foreach ($photos as $photo): ?>
                        <div class="photo-item">
                            <img src="<?php echo htmlspecialchars($photo["lien_photo"]); ?>" alt="<?php echo htmlspecialchars($photo["nom_photo"]); ?>" width="100">
                            <a href="/proprietaire/deletePhoto/<?php echo htmlspecialchars($photo["id_photo"]); ?>" onclick="return confirm(&quot;Êtes-vous sûr de vouloir supprimer cette photo ?&quot;);">Supprimer</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune photo pour ce bien.</p>
                <?php endif; ?>
            </div>

            <h3>Ajouter de nouvelles photos :</h3>
            <div class="photo-drop-zone">
                <div class="drop-zone-text">
                    <strong>Glissez-déposez vos photos ici ou cliquez pour sélectionner des fichiers</strong><br>
                </div>
                <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                <div class="photo-preview-container"></div>
            </div>

            <button type="submit">Mettre à jour le bien</button>
        </form>
    </main>

    <footer>
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="/js/autocomplete.js"></script>
    <script src="/js/register.js"></script>
    <script src="/js/photo-upload.js"></script>
    <script>
        // Fonction pour ouvrir/fermer l'accordéon des tarifs
        function toggleAllTarifs() {
            const header = document.querySelector('.accordion-header');
            const content = document.getElementById('accordion-content-tarifs');
            const icon = document.getElementById('accordion-icon');
            
            // Toggle l'état actif
            const isActive = header.classList.contains('active');
            
            if (isActive) {
                // Fermer l'accordéon
                header.classList.remove('active');
                content.classList.remove('active');
            } else {
                // Ouvrir l'accordéon
                header.classList.add('active');
                content.classList.add('active');
            }
        }

        // Valeurs initiales des champs critiques
        const initialValues = {
            designation_bien: document.getElementById('designation_bien').value,
            description_biens: document.getElementById('description_biens').value,
            id_TypeBien: document.getElementById('id_TypeBien').value,
            photos_count: <?php echo count($photos ?? []); ?>
        };
        
        // Vérifier si des champs critiques ont été modifiés
        function confirmCriticalChanges() {
            const champsCritiquesModifies = [];
            
            // Vérifier la désignation
            if (document.getElementById('designation_bien').value !== initialValues.designation_bien) {
                champsCritiquesModifies.push('Désignation du bien');
            }
            
            // Vérifier la description
            if (document.getElementById('description_biens').value !== initialValues.description_biens) {
                champsCritiquesModifies.push('Description');
            }
            
            // Vérifier le type de bien
            if (document.getElementById('id_TypeBien').value !== initialValues.id_TypeBien) {
                champsCritiquesModifies.push('Type de bien');
            }
            
            // Vérifier les photos (si des nouvelles photos ont été ajoutées)
            const fileInput = document.getElementById('photos');
            if (fileInput && fileInput.files.length > 0) {
                champsCritiquesModifies.push('Photos');
            }
            
            // Si des champs critiques ont été modifiés, demander confirmation
            if (champsCritiquesModifies.length > 0) {
                const message = "⚠️ ATTENTION\n\n" +
                    "Vous avez modifié les champs critiques suivants :\n" +
                    "• " + champsCritiquesModifies.join("\n• ") + "\n\n" +
                    "Votre bien sera remis en attente de validation.\n" +
                    "Il ne sera plus visible publiquement jusqu'à validation par un administrateur.\n\n" +
                    "Voulez-vous continuer ?";
                    
                return confirm(message);
            }
            
            return true; // Pas de changement critique, continuer normalement
        }
    </script>
</body>
</html>
