<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bien - Propriétaire</title>
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

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Formulaire */
        .add-bien-form {
            background: white;
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .dark-mode .add-bien-form {
            background: var(--bg-card);
            border-color: var(--border-color);
        }

        /* Fieldsets */
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

        /* Form groups */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        /* Labels et inputs */
        label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
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

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .checkbox-group input {
            width: auto;
        }

        .checkbox-group label {
            margin: 0;
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

        .tarif-group {
            background: rgba(0, 0, 0, 0.02);
            border: 2px solid var(--border-color, #e0e0e0);
            border-radius: 10px;
            padding: 1.25rem;
            transition: all 0.2s;
        }

        .dark-mode .tarif-group {
            background: rgba(255, 255, 255, 0.02);
            border-color: var(--border-color);
        }

        .tarif-group:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: var(--accent-primary, #ff5a5f);
        }

        .tarif-group h4 {
            margin: 0 0 1rem 0;
            color: var(--accent-primary, #ff5a5f);
            font-size: 1.125rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tarif-group .form-group {
            margin-bottom: 0;
        }

        /* Zone de photos avec drag and drop */
        .photo-drop-zone {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border: 3px dashed #4caf50;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
        }

        .photo-drop-zone:hover {
            background: linear-gradient(135deg, #c8e6c9, #a5d6a7);
            border-color: #43a047;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.3);
        }

        .photo-drop-zone.dragover {
            background: linear-gradient(135deg, #a5d6a7, #81c784);
            border-color: #2e7d32;
            border-width: 4px;
        }

        .drop-zone-text {
            color: #2e7d32;
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }

        .drop-zone-text strong {
            display: block;
            font-size: 1.375rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .photo-drop-zone input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .photo-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* Bouton de soumission */
        .submit-button {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--accent-primary, #ff5a5f), var(--accent-hover, #ff7f83));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.125rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 2rem;
        }

        .submit-button:hover {
            background: linear-gradient(135deg, var(--accent-hover, #ff7f83), #ff9999);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 90, 95, 0.4);
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

        /* Responsive */
        @media (max-width: 768px) {
            main {
                padding: 1rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .add-bien-form {
                padding: 1.5rem;
            }

            .tarifs-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <h2 class="form-title">Ajouter un nouveau Bien</h2>
        <form action="/proprietaire/addBien" method="POST" enctype="multipart/form-data" class="add-bien-form">
            
            <fieldset class="form-section">
                <legend>Informations Générales</legend>
                <div class="form-group">
                    <label for="designation_bien">Désignation du bien :</label>
                    <input type="text" id="designation_bien" name="designation_bien" required>
                </div>
                <div class="form-group">
                    <label for="id_TypeBien">Type de bien :</label>
                    <select id="id_TypeBien" name="id_TypeBien" required>
                        <?php foreach ($typesBiens as $type): ?>
                            <option value="<?php echo htmlspecialchars($type["id_typebien"]); ?>">
                                <?php echo htmlspecialchars($type["desc_type_bien"]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="superficie_biens">Superficie (m²) :</label>
                    <input type="number" id="superficie_biens" name="superficie_biens" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="nb_couchage">Nombre de couchages :</label>
                    <input type="number" id="nb_couchage" name="nb_couchage" required>
                </div>
                <div class="form-group checkbox-group">
                    <input type="checkbox" id="animaux_biens" name="animaux_biens" value="1">
                    <label for="animaux_biens">Animaux acceptés</label>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Localisation</legend>
                <div class="form-group">
                    <label for="rue_biens">Rue :</label>
                    <input type="text" id="rue_biens" name="rue_biens" required>
                </div>
                <div class="form-group">
                    <label for="complement_biens">Complément d'adresse :</label>
                    <input type="text" id="complement_biens" name="complement_biens">
                </div>
                <div class="form-group">
                    <label for="id_commune">Commune :</label>
                    <input type="text" id="commune_search_register" name="commune_nom" value="<?php echo htmlspecialchars($old_data['commune_nom'] ?? ''); ?>" placeholder="Commencez à taper le nom de la commune...">
                    <input type="hidden" id="id_commune" name="id_commune" value="<?php echo htmlspecialchars($old_data['id_commune'] ?? ''); ?>">
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Description</legend>
                <div class="form-group full-width">
                    <label for="description_biens">Description détaillée :</label>
                    <textarea id="description_biens" name="description_biens" rows="5"></textarea>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Tarification (Prix par jour)</legend>
                <div class="accordion-container">
                    <div class="accordion-header" onclick="toggleTarifs()">
                        <div class="accordion-title">
                            💰 Gérer les tarifs par saison (<?php echo count($saisons); ?> saisons)
                        </div>
                        <span class="accordion-icon" id="accordion-icon">▼</span>
                    </div>
                    <div class="accordion-content" id="accordion-content-tarifs">
                        <div id="tarifs-container" class="tarifs-grid">
                            <?php foreach ($saisons as $saison): ?>
                                <div class="tarif-group">
                                    <h4>📅 <?php echo htmlspecialchars($saison["lib_saison"]); ?></h4>
                                    <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][id_saison]" value="<?php echo htmlspecialchars($saison["id_saison"]); ?>">
                                    
                                    <div class="form-group">
                                        <label for="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>">Prix par semaine (€) :</label>
                                        <input type="number" id="prix_semaine_<?php echo htmlspecialchars($saison["id_saison"]); ?>" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][prix_semaine]" step="0.01" min="0" placeholder="Ex: 500.00">
                                    </div>
                                    <input type="hidden" name="tarifs[<?php echo htmlspecialchars($saison["id_saison"]); ?>][annee]" value="<?php echo date('Y'); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="form-section">
                <legend>Photos du Bien</legend>
                <div class="form-group full-width">
                    <div class="photo-drop-zone">
                        <div class="drop-zone-text">
                            <strong>Glissez-déposez vos photos ici</strong><br>
                            ou cliquez pour sélectionner des fichiers
                        </div>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*">
                        <div class="photo-preview-container"></div>
                    </div>
                </div>
            </fieldset>

            <button type="submit" class="submit-button">Ajouter le bien</button>
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
        function toggleTarifs() {
            const header = document.querySelector('.accordion-header');
            const content = document.getElementById('accordion-content-tarifs');
            const icon = document.getElementById('accordion-icon');
            
            header.classList.toggle('active');
            content.classList.toggle('active');
        }
    </script>
</body>
</html>
