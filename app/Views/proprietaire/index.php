<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Propriétaire - Goodnight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <style>
        :root {
            --primary: #FE9D15;
            --bg: #F7F7F7;
            --text: #2c3e50;
        }
        
        body {
            background: var(--bg);
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .dashboard {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }
        
        h1 {
            color: var(--text);
            margin-bottom: 30px;
        }

        /* Roue 3D */
        .wheel-container {
            position: relative;
            width: 900px;
            height: 520px;
            margin: 50px auto 60px;
            perspective: 1600px;
        }

        .wheel {
            position: absolute;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.9s cubic-bezier(0.3,0.8,0.3,1);
        }

        .wheel-card {
            position: absolute;
            width: 300px;
            height: 420px;
            left: 50%;
            top: 50%;
            margin-left: -150px;
            margin-top: -210px;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            transition: all 0.7s ease;
            opacity: 0.4;
            cursor: pointer;
        }

        .wheel-card.active {
            opacity: 1;
            transform: translateZ(80px) scale(1.25);
            border: 6px solid var(--primary);
            box-shadow: 0 40px 90px rgba(254,157,21,0.6);
            z-index: 100;
        }

        .wheel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .wheel-info {
            padding: 25px;
            text-align: center;
        }

        .wheel-info h3 {
            margin: 0 0 12px;
            font-size: 21px;
            color: var(--text);
            font-weight: 700;
        }

        .nav-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: white;
            border: none;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            font-size: 42px;
            cursor: pointer;
            z-index: 200;
            box-shadow: 0 10px 30px rgba(0,0,0,0.35);
            transition: all 0.3s;
        }

        .nav-arrow:hover {
            background: #e67e22;
            transform: translateY(-50%) scale(1.15);
        }

        .nav-arrow.left {
            left: -40px;
        }

        .nav-arrow.right {
            right: -40px;
        }

        /* Contenu principal */
        .content-area {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 40px auto;
            max-width: 1000px;
        }

        .bien-details h2 {
            color: var(--primary);
            margin-top: 0;
        }

        .bien-details p {
            color: var(--text);
            font-size: 16px;
            line-height: 1.6;
        }

        /* Formulaire blocage */
        .blocage-section {
            margin-top: 40px;
            padding-top: 40px;
            border-top: 2px solid #e0e0e0;
        }

        .blocage-section h3 {
            color: var(--text);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: border 0.3s;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* Alertes */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            display: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .blocages-list {
            margin-top: 30px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .blocage-item {
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid var(--primary);
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .blocage-dates {
            font-weight: 600;
            color: var(--primary);
        }

        .blocage-motif {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #666;
            margin-top: 60px;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: #fefefe;
            margin: 50px auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 15px;
        }

        .modal-header h2 {
            margin: 0;
            color: var(--primary);
            font-size: 24px;
        }

        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            transition: color 0.3s;
        }

        .close-btn:hover {
            color: #000;
        }

        .modal-body {
            line-height: 1.8;
        }

        .fiche-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .fiche-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .fiche-label {
            font-weight: 700;
            color: var(--primary);
            font-size: 13px;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .fiche-value {
            color: var(--text);
            font-size: 15px;
        }

        .fiche-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .modal-footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-close-modal {
            background: #95a5a6;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-close-modal:hover {
            background: #7f8c8d;
        }
        /* Filter + cards layout */
        .filter-cards-area {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-top: 30px;
        }
        .filter-panel {
            width: 260px;
            background: #fff;
            padding: 16px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            height: auto;
            max-height: 520px;
            overflow: auto;
        }
        .filter-panel h3 { margin: 0 0 10px 0; color: var(--primary); }
        .filter-item { display:flex; align-items:center; gap:10px; padding:8px 6px; cursor:pointer; }
        .filter-item input { width:18px; height:18px; }
        .cards-main { flex:1; }
        .cards-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            padding: 12px;
            background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(250,250,250,0.95));
            border-radius: 12px;
            min-height: 320px;
            position: relative;
            overflow: hidden;
            perspective: 1200px; /* allow 3D transforms for wheel effect */
        }
        .card {
            width: 260px;
            height: 320px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.12);
            transform: rotateX(8deg) rotateY(-6deg) translateZ(0);
            transition: transform 600ms cubic-bezier(.2,.9,.2,1), filter 400ms ease, opacity 400ms ease;
            filter: blur(4px) grayscale(30%) brightness(0.92);
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            backface-visibility: hidden;
        }
        .card.selected {
            transform: translateY(-12px) scale(1.06) translateZ(80px);
            z-index: 60;
            filter: none;
            box-shadow: 0 40px 90px rgba(0,0,0,0.18);
        }
        .card.dimmed {
            opacity: 0.5;
            filter: blur(6px) grayscale(60%) brightness(0.78);
            transform: rotateX(10deg) rotateY(-8deg) scale(0.96) translateZ(-40px);
            z-index: 5;
        }
        .card img { width:100%; height:160px; object-fit:cover; display:block; }
        .card-body { padding:12px; flex:1; display:flex; flex-direction:column; }
        .card-title { font-weight:700; color:var(--primary); margin-bottom:6px; }
        .card-desc { color:var(--text); font-size:14px; flex:1; }
        .card-price { margin-top:10px; font-weight:800; color:#2c3e50; }

        /* Selected carousel container when many selected */
        .selected-carousel {
            display:flex; gap:14px; align-items:center; overflow:hidden; width:100%; padding:8px 4px;
            scroll-behavior: smooth;
        }

        @media (max-width: 900px) {
            .filter-cards-area { flex-direction: column; }
            .filter-panel { width: 100%; order: 2; }
            .cards-main { order: 1; }
            .card { width: calc(50% - 18px); }
        }
        /* --- Visual wheel stage (large orange area with particles) --- */
        .wheel-stage {
            margin: 30px auto 12px;
            border-radius: 16px;
            overflow: hidden;
            background: linear-gradient(180deg,#ff9d37 0%, #ff7f15 100%);
            position: relative;
            max-width: 1200px;
            height: 420px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .particles-layer { position:absolute; inset:0; pointer-events:none; overflow:hidden; }
        .particle { position:absolute; width:6px; height:6px; background:rgba(255,255,255,0.85); border-radius:50%; opacity:0.9; filter:blur(1px); }

        .wheel-3d {
            width: 900px; height: 360px; position:relative; transform-style:preserve-3d;
            transition: transform 1s ease; will-change: transform;
            animation: wheelSpin 30s linear infinite;
        }
        @keyframes wheelSpin { from { transform: rotateY(0deg); } to { transform: rotateY(360deg); } }

        .wheel-slot {
            position:absolute; width:220px; height:300px; left:50%; top:50%; transform-style:preserve-3d; margin-left:-110px; margin-top:-150px;
            border-radius:14px; overflow:hidden; background:#fff; box-shadow:0 20px 50px rgba(0,0,0,0.18);
            display:flex; flex-direction:column; cursor:pointer; transition: transform 800ms cubic-bezier(.2,.9,.2,1), opacity 400ms ease;
        }
        .wheel-slot img{ width:100%; height:140px; object-fit:cover; display:block; }
        .wheel-slot .slot-body { padding:10px; color:#333; font-weight:600; font-size:14px; }

        /* Selected cards container (below the wheel) */
        .selected-area {
            margin-top: 18px; width:100%; max-width:1200px; margin-left:auto; margin-right:auto;
            background: transparent; border-radius:12px; padding:10px; box-sizing:border-box;
        }
        /* Calendar sizing helpers */
        #calendar {
            width: 100%;
            min-height: 450px; /* ensure visible area */
        }

        /* mini FullCalendar removed - date inputs are used instead */

        /* Allow slightly wider modal for calendar */
        @media (min-width: 900px) {
            .modal-content { max-width: 820px; }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>
    <!-- Alert container for notifications (inserted dynamically) -->
    <div id="alertContainer" style="position:fixed; top:80px; right:20px; z-index:1100;"></div>

    <!-- Placeholders for removed wheel features to avoid JS errors -->
    <div id="bienDetails" style="display:none;"></div>
    <div id="blocageSection" style="display:none;"></div>
    <div id="blocagesList" style="display:none;"><div id="blocagesContent"></div></div>

    <div class="dashboard">
        <h1>Tableau de bord Propriétaire</h1>

        <!-- Visual wheel stage: orange background with particles and 3D rotating wheel -->
        <div class="wheel-stage" id="wheelStage">
            <div class="particles-layer" id="particlesLayer"></div>
            <div class="wheel-3d" id="wheel3d"></div>
        </div>

        <!-- Selected cards area (invisible background) -->
        <div class="selected-area">
            <div id="selectedCards" class="selected-carousel" aria-hidden="false"></div>
        </div>

        <!-- Filtres visuels et cards -->
        <div class="filter-cards-area">
            <aside class="filter-panel">
                <h3>Filtres - Biens</h3>
                <div class="filter-list">
                    <?php foreach($biens ?? [] as $b): ?>
                        <label class="filter-item">
                            <input type="checkbox" class="filter-checkbox" data-bien-id="<?php echo $b['id_biens']; ?>">
                            <span class="filter-name"><?php echo htmlspecialchars($b['designation_bien']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </aside>

            <main class="cards-main">
                <div id="cardsGrid" class="cards-grid"></div>
            </main>
        </div>
        
        <!-- Calendrier FullCalendar -->
        <div class="content-area" style="max-width:1000px;">
            <h2>Calendrier des réservations</h2>

            <div style="margin-bottom:12px; display:flex; gap:12px; align-items:center;">
                <div style="padding:6px 0; color:#333; font-size:14px;"><strong>Sélectionnez un ou plusieurs biens via la colonne de gauche</strong></div>
                <div style="margin-left:auto; font-size:14px; color:#666">
                    <span style="display:inline-block;margin-right:8px;"><span style="display:inline-block;width:12px;height:12px;background:#3788d8;margin-right:6px;"></span>Réservations</span>
                    <span style="display:inline-block;margin-left:8px;"><span style="display:inline-block;width:12px;height:12px;background:#ff7f50;margin-right:6px;"></span>Blocages</span>
                </div>
            </div>

            <div id="calendar" style="background:white; border-radius:8px; padding:12px;"></div>

            <div style="margin-top:20px; display:flex; gap:10px;">
                <button class="btn btn-primary" onclick="openBlocageModal()">+ Créer un blocage</button>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher la fiche client -->
    <div id="reservationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Détails de la réservation</h2>
                <button class="close-btn" onclick="closeReservationModal()">&times;</button>
            </div>

            <div class="modal-body">
                <div class="fiche-item">
                    <div class="fiche-label">Bien</div>
                    <div class="fiche-value" id="modal-bien-name">-</div>
                </div>

                <div class="fiche-row">
                    <div>
                        <div class="fiche-label">Nom</div>
                        <div class="fiche-value" id="modal-locataire-nom">-</div>
                    </div>
                    <div>
                        <div class="fiche-label">Prénom</div>
                        <div class="fiche-value" id="modal-locataire-prenom">-</div>
                    </div>
                </div>

                <div class="fiche-item" id="modal-raison-sociale-container" style="display:none;">
                    <div class="fiche-label">Raison Sociale</div>
                    <div class="fiche-value" id="modal-locataire-raison-sociale">-</div>
                </div>

                <div class="fiche-item">
                    <div class="fiche-label">Email</div>
                    <div class="fiche-value" id="modal-locataire-email">-</div>
                </div>

                <div class="fiche-item">
                    <div class="fiche-label">Téléphone</div>
                    <div class="fiche-value" id="modal-locataire-tel">-</div>
                </div>

                <div class="fiche-row">
                    <div>
                        <div class="fiche-label">Date de début</div>
                        <div class="fiche-value" id="modal-date-debut">-</div>
                    </div>
                    <div>
                        <div class="fiche-label">Date de fin</div>
                        <div class="fiche-value" id="modal-date-fin">-</div>
                    </div>
                </div>

                <div class="fiche-item">
                    <div class="fiche-label">Commune</div>
                    <div class="fiche-value" id="modal-commune">-</div>
                </div>

                <div class="fiche-item">
                    <div class="fiche-label">ID Réservation</div>
                    <div class="fiche-value" id="modal-reservation-id">-</div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn-close-modal" onclick="closeReservationModal()">Fermer</button>
            </div>
        </div>
    </div>

    <!-- Modal pour créer/éditer un blocage -->
    <div id="blocageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Créer un blocage</h2>
                <button class="close-btn" onclick="closeBlocageModal()">&times;</button>
            </div>

            <form id="blocageForm" onsubmit="submitBlocageForm(event)">
                <div class="modal-body">
                    <!-- Mini FullCalendar removed: use the date inputs below to sélectionner la période. -->
                    <div class="fiche-item">
                        <label for="blocage-bien" class="fiche-label">Bien *</label>
                        <select id="blocage-bien" required style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
                            <option value="">-- Sélectionnez un bien --</option>
                            <?php foreach($biens ?? [] as $b): ?>
                                <option value="<?php echo $b['id_biens']; ?>"><?php echo htmlspecialchars($b['designation_bien']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="fiche-row">
                        <div class="fiche-item">
                            <label for="blocage-date-debut" class="fiche-label">Date début *</label>
                            <input type="date" id="blocage-date-debut" required style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
                        </div>
                        <div class="fiche-item">
                            <label for="blocage-date-fin" class="fiche-label">Date fin *</label>
                            <input type="date" id="blocage-date-fin" required style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
                        </div>
                    </div>

                    <div class="fiche-item">
                        <label for="blocage-motif" class="fiche-label">Motif *</label>
                        <select id="blocage-motif" required style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px;">
                            <option value="">-- Sélectionnez un motif --</option>
                            <option value="personnel">Personnel</option>
                            <option value="entretien">Entretien</option>
                            <option value="fermeture">Fermeture</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="fiche-item">
                        <label for="blocage-commentaire" class="fiche-label">Commentaire</label>
                        <textarea id="blocage-commentaire" style="width:100%; padding:10px; border:2px solid #e0e0e0; border-radius:6px; min-height:80px;"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-close-modal" onclick="closeBlocageModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary" style="padding:10px 20px;">Créer blocage</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Goodnight. Tous droits réservés.</p>
    </footer>

    <script>
        /* Variables globales */
        let currentIndex = 0;
        let currentBienId = null;
        const biens = <?php echo json_encode($biens ?? []); ?>;
        // FullCalendar global instance (accessible depuis les handlers)
        window.fcCalendar = null;

        /* Wheel 3D removed: protected placeholders are used instead */

        /* ========== AFFICHAGE BIEN ========== */
        function loadBienDetails(bien) {
            const html = `
                <h2>${bien.designation_bien}</h2>
                <p><strong>Commune :</strong> ${bien.commune_nom}</p>
                <p><strong>Capacité :</strong> ${bien.nb_couchage} personnes</p>
                <p><strong>Superficie :</strong> ${bien.superficie_biens} m²</p>
            `;
            document.getElementById('bienDetails').innerHTML = html;
            document.getElementById('blocageSection').style.display = 'block';
            document.getElementById('blocagesList').style.display = 'block';
            
            // Charger les blocages existants
            loadBlocages(bien.id_biens);
        }

        // blocage form handled by submitBlocageForm() attached via onsubmit on the form

        /* ========== CHARGEMENT BLOCAGES ========== */
        async function loadBlocages(bienId) {
            try {
                // Récupérer tous les blocages du bien
                const response = await fetch(`/proprietaire/calendar/events?bien=${bienId}`);
                const events = await response.json();

                const blocages = events.filter(e => e.id && e.id.startsWith('block-'));
                
                if (blocages.length === 0) {
                    document.getElementById('blocagesContent').innerHTML = '<p>Aucun blocage pour ce bien</p>';
                    return;
                }

                let html = '';
                blocages.forEach(b => {
                    const startDate = new Date(b.start);
                    const endDate = new Date(b.end);
                    endDate.setDate(endDate.getDate() - 1);
                    
                    html += `
                        <div class="blocage-item">
                            <div>
                                <div class="blocage-dates">${formatDateFR(startDate)} → ${formatDateFR(endDate)}</div>
                                <div class="blocage-motif">${b.extendedProps.motif.toUpperCase()}${b.extendedProps.commentaire ? ' - ' + b.extendedProps.commentaire : ''}</div>
                            </div>
                            <button class="btn btn-danger" onclick="deleteBlocage('${b.id}')">Supprimer</button>
                        </div>
                    `;
                });

                document.getElementById('blocagesContent').innerHTML = html;
            } catch (err) {
                console.error('Erreur chargement blocages:', err);
            }
        }

        /* ========== SUPPRESSION BLOCAGE ========== */
        async function deleteBlocage(eventId) {
            if (!confirm('Supprimer ce blocage ?')) return;

            try {
                const response = await fetch('/proprietaire/calendar/unblock', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ eventId: eventId })
                });

                const result = await response.json();
                
                if (response.ok && result.success) {
                    showAlert('Blocage supprimé!', 'success');
                    loadBlocages(currentBienId);
                } else {
                    showAlert('Erreur: ' + (result.error || ''), 'error');
                }
            } catch (err) {
                showAlert('Erreur réseau', 'error');
                console.error(err);
            }
        }

        /* ========== UTILITAIRES ========== */
        function showAlert(message, type) {
            const container = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = 'alert alert-' + type;
            alert.innerHTML = message + ' <span style="float:right; cursor:pointer;" onclick="this.parentElement.remove()">×</span>';
            alert.style.display = 'block';
            container.appendChild(alert);

            setTimeout(() => alert.remove(), 5000);
        }

        function formatDateFR(date) {
            return new Intl.DateTimeFormat('fr-FR', {
                weekday: 'short',
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            }).format(date);
        }

        /* Navigation arrows removed with the wheel - guard listeners if present */
        const navLeft = document.querySelector('.nav-arrow.left');
        const navRight = document.querySelector('.nav-arrow.right');
        if (navLeft) {
            navLeft.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + biens.length) % biens.length;
                try { if (typeof updateWheel === 'function') updateWheel(); } catch(e){}
                if (biens[currentIndex]) try { loadBienDetails(biens[currentIndex]); } catch(e){}
            });
        }
        if (navRight) {
            navRight.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % biens.length;
                try { if (typeof updateWheel === 'function') updateWheel(); } catch(e){}
                if (biens[currentIndex]) try { loadBienDetails(biens[currentIndex]); } catch(e){}
            });
        }

        /* ========== INIT ========== */
        document.addEventListener('DOMContentLoaded', () => {
            // Wheel removed: do not initialize it. Build cards grid instead.
            buildCardsGrid();
            initVisualWheel();
        });
    </script>
    <script>
        // FullCalendar initialization and filter handling
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 650,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    // Build filter from selected checkboxes (multi-selection)
                    const selected = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(cb => cb.dataset.bienId);
                    let url = '/proprietaire/calendar/events';
                    if (selected.length === 1) {
                        url += '?bien=' + encodeURIComponent(selected[0]);
                    } else if (selected.length > 1) {
                        // send comma-separated list under 'biens' param
                        url += '?biens=' + encodeURIComponent(selected.join(','));
                    }
                    fetch(url).then(r => r.json()).then(data => successCallback(data)).catch(err => failureCallback(err));
                },
                eventDidMount: function(info) {
                    // Tooltip or additional styling could be added here
                },
                eventClick: function(info) {
                    const ext = info.event.extendedProps || {};
                    if (ext.type === 'reservation') {
                        openReservationModal(ext);
                    } else if (ext.type === 'blocage') {
                        alert('Blocage\nMotif: ' + (ext.motif || '') + '\nCommentaire: ' + (ext.commentaire || ''));
                    }
                }
            });

            calendar.render();
            // keep a global reference so other functions (outside this scope) can call refetchEvents()
            window.fcCalendar = calendar;
            // ensure FullCalendar recomputes sizes after render (helps when inside responsive containers)
            setTimeout(function() { try { window.fcCalendar.updateSize(); } catch(e) { /* ignore */ } }, 100);

            // When card/checkbox selection changes we call refetch from updateCardsVisuals
        });

        // Modal functions for reservation details
        function openReservationModal(reservationData) {
            // Populate modal fields
            document.getElementById('modal-bien-name').textContent = reservationData.bien_name || '-';
            document.getElementById('modal-locataire-nom').textContent = reservationData.locataire_nom || '-';
            document.getElementById('modal-locataire-prenom').textContent = reservationData.locataire_prenom || '-';
            document.getElementById('modal-locataire-email').textContent = reservationData.locataire_email || '-';
            document.getElementById('modal-locataire-tel').textContent = reservationData.locataire_tel || '-';
            document.getElementById('modal-commune').textContent = reservationData.commune || '-';
            document.getElementById('modal-reservation-id').textContent = reservationData.reservation_id || '-';
            
            // Format dates
            const dateDebut = new Date(reservationData.date_debut);
            const dateFin = new Date(reservationData.date_fin);
            document.getElementById('modal-date-debut').textContent = formatDateFR(dateDebut);
            document.getElementById('modal-date-fin').textContent = formatDateFR(dateFin);

            // Show/hide raison sociale if present
            const raisonSociale = document.getElementById('modal-locataire-raison-sociale');
            const raisonSocialeContainer = document.getElementById('modal-raison-sociale-container');
            if (reservationData.locataire_raison_sociale) {
                raisonSociale.textContent = reservationData.locataire_raison_sociale;
                raisonSocialeContainer.style.display = 'block';
            } else {
                raisonSocialeContainer.style.display = 'none';
            }

            // Show modal
            document.getElementById('reservationModal').style.display = 'block';
        }

        function closeReservationModal() {
            document.getElementById('reservationModal').style.display = 'none';
        }

        // Build cards grid and wire checkbox interactions
        function buildCardsGrid() {
            const grid = document.getElementById('cardsGrid');
            if (!grid) return;
            grid.innerHTML = '';

            biens.forEach(b => {
                const card = document.createElement('div');
                card.className = 'card';
                card.dataset.bienId = b.id_biens;

                const imgSrc = b.premiere_photo || '/img/default.jpg';
                card.innerHTML = `
                    <img src="${imgSrc}" alt="${escapeHtml(b.designation_bien)}">
                    <div class="card-body">
                        <div class="card-title">${escapeHtml(b.designation_bien)}</div>
                        <div class="card-desc">${escapeHtml((b.description_biens || '').slice(0,120))}</div>
                        <div class="card-price">${b.prix ?? ''}</div>
                    </div>
                `;

                // toggle selection when clicking card
                card.addEventListener('click', (e) => {
                    // ignore clicks coming from inputs
                    toggleBienSelection(b.id_biens);
                });

                grid.appendChild(card);
            });

            // Wire checkboxes
            document.querySelectorAll('.filter-checkbox').forEach(cb => {
                cb.addEventListener('change', (e) => {
                    const id = cb.dataset.bienId;
                    syncSelectionFromCheckbox(id, cb.checked);
                });
            });

            // initial visual state - no selection => all blurred
            updateCardsVisuals();
        }

        function escapeHtml(str) {
            if (!str) return '';
            return String(str).replace(/[&<>\"']/g, function (s) {
                return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"})[s];
            });
        }

        function toggleBienSelection(id) {
            const cb = document.querySelector('.filter-checkbox[data-bien-id="' + id + '"]');
            if (cb) {
                cb.checked = !cb.checked;
                syncSelectionFromCheckbox(id, cb.checked);
            }
        }

        function syncSelectionFromCheckbox(id, checked) {
            const card = document.querySelector('.card[data-bien-id="' + id + '"]');
            if (!card) return;
            if (checked) card.classList.add('selected'); else card.classList.remove('selected');

            // Update currentBienId when a single bien is selected
            const selected = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(i => i.dataset.bienId);
            if (selected.length === 1) currentBienId = selected[0];
            else currentBienId = null;

            updateCardsVisuals();
        }

        function updateCardsVisuals() {
            const selected = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(i => i.dataset.bienId);
            const cards = Array.from(document.querySelectorAll('.card'));

            if (selected.length === 0) {
                // no selection: all blurred / rotated
                cards.forEach(c => { c.classList.remove('selected'); c.classList.remove('dimmed'); });
                cards.forEach(c => c.style.filter = 'blur(6px) grayscale(30%) brightness(0.9)');
            } else {
                cards.forEach(c => {
                    const id = String(c.dataset.bienId);
                    if (selected.includes(id)) {
                        c.classList.add('selected'); c.classList.remove('dimmed'); c.style.filter = 'none';
                    } else {
                        c.classList.add('dimmed'); c.classList.remove('selected');
                    }
                });
            }

            // If many selected, make a horizontal auto-scroll
            manageSelectedCarousel(selected);

            // Arrange non-selected cards in a subtle 3D wheel/arc in background
            try { arrangeBackgroundWheel(selected); } catch(e) {}

            // Trigger calendar refresh to apply the new filter
            try { if (window.fcCalendar && typeof window.fcCalendar.refetchEvents === 'function') window.fcCalendar.refetchEvents(); } catch(e){}
        }

        /**
         * Arrange non-selected cards in a 3D arc (wheel-like) background.
         * selectedIds: array of string ids
         */
        function arrangeBackgroundWheel(selectedIds) {
            const container = document.getElementById('cardsGrid');
            if (!container) return;
            const all = Array.from(container.querySelectorAll('.card'));
            const nonSelected = all.filter(c => !selectedIds.includes(String(c.dataset.bienId)));

            // If none or only a few non-selected, keep default layout
            if (nonSelected.length === 0) {
                all.forEach(c => { c.style.transform = ''; });
                return;
            }

            const n = nonSelected.length;
            // maximum angle spread
            const maxSpread = Math.min(100, n * 12); // degrees
            const angleStep = n > 1 ? (maxSpread / (n - 1)) : 0;

            for (let i = 0; i < nonSelected.length; i++) {
                const card = nonSelected[i];
                const centerIndex = (n - 1) / 2;
                const offset = i - centerIndex;
                const angle = offset * angleStep; // degrees

                // Depth: more to the back for larger angles
                const depth = -80 - Math.abs(angle) * 1.5; // px
                const tiltX = 8; // keep slight X tilt
                const translateY = 10 + Math.abs(offset) * 6; // push slightly down

                card.style.transform = `rotateY(${angle}deg) rotateX(${tiltX}deg) translateZ(${depth}px) translateY(${translateY}px)`;
                card.style.zIndex = 10; // background layer
            }

            // Ensure selected cards are visually on top and centered-ish
            const selectedCards = all.filter(c => selectedIds.includes(String(c.dataset.bienId)));
            selectedCards.forEach((c, idx) => {
                c.style.transform = '';
                c.style.zIndex = 60 + idx;
            });
        }

        /* ================== Visual wheel initialization ================== */
        function initVisualWheel() {
            const wheel = document.getElementById('wheel3d');
            const particlesLayer = document.getElementById('particlesLayer');
            if (!wheel || !particlesLayer) return;

            // create particles
            const particleCount = 40;
            particlesLayer.innerHTML = '';
            for (let i = 0; i < particleCount; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                const left = Math.random() * 100;
                const top = Math.random() * 100;
                const scale = 0.6 + Math.random() * 1.2;
                p.style.left = left + '%';
                p.style.top = top + '%';
                p.style.transform = `scale(${scale})`;
                p.style.opacity = (0.3 + Math.random() * 0.9).toString();
                p.style.animation = `float${i} 6s ${Math.random()*4}s ease-in-out infinite`; 
                particlesLayer.appendChild(p);
            }

            // keyframes for small floats appended dynamically
            const style = document.createElement('style');
            let animCss = '';
            for (let i = 0; i < particleCount; i++) {
                const dx = (Math.random() - 0.5) * 8;
                const dy = (Math.random() - 0.5) * 8;
                animCss += `@keyframes float${i} { 0%{transform:translate(0,0)} 50%{transform:translate(${dx}px,${dy}px)} 100%{transform:translate(0,0)} }\n`;
            }
            style.innerHTML = animCss;
            document.head.appendChild(style);

            // populate 10 wheel slots using biens (repeat if fewer than 10)
            wheel.innerHTML = '';
            const slotCount = 10;
            if (!Array.isArray(biens) || biens.length === 0) return;
            const radius = 420; // translateZ radius
            for (let i = 0; i < slotCount; i++) {
                const slot = document.createElement('div');
                slot.className = 'wheel-slot';
                const b = biens[i % biens.length];
                const img = b.premiere_photo || '/img/default.jpg';
                slot.innerHTML = `<img src="${img}" alt="${escapeHtml(b.designation_bien)}"><div class="slot-body">${escapeHtml(b.designation_bien)}</div>`;

                const angle = (i / slotCount) * 360; // degrees
                // position around Y-axis in 3D
                slot.style.transform = `rotateY(${angle}deg) translateZ(${radius}px) translateX(0px)`;
                // store bien id for click
                slot.dataset.bienId = b.id_biens;

                // click toggles selection for that bien
                slot.addEventListener('click', (e) => {
                    toggleBienSelection(b.id_biens);
                    // visually nudge the wheel to slow-stop near clicked slot: rotate so clicked slot faces front
                    // compute desired rotation to bring this angle to 0
                    const currentRot = 0; // we use CSS animation; to nudge we'd need more complex control -- keep simple for now
                });

                wheel.appendChild(slot);
            }
        }

        let carouselInterval = null;
        function manageSelectedCarousel(selectedIds) {
            const container = document.getElementById('cardsGrid');
            if (!container) return;

            const selectedCards = Array.from(container.querySelectorAll('.card.selected'));
            // compute visible capacity (approx)
            const cardWidth = selectedCards[0] ? selectedCards[0].offsetWidth + 18 : 280;
            const visible = Math.floor(container.offsetWidth / cardWidth) || 1;

            if (selectedCards.length > visible) {
                // start auto-scroll
                if (carouselInterval) return; // already running
                let pos = 0;
                carouselInterval = setInterval(() => {
                    pos += 1; // px per tick
                    container.scrollLeft = (container.scrollLeft + 1) % (container.scrollWidth || 1);
                }, 60); // slow scroll
            } else {
                // stop auto-scroll
                if (carouselInterval) { clearInterval(carouselInterval); carouselInterval = null; container.scrollLeft = 0; }
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('reservationModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
            const blocageModal = document.getElementById('blocageModal');
            if (event.target === blocageModal) {
                blocageModal.style.display = 'none';
            }
        });

        // Keep calendar responsive on window resize
        window.addEventListener('resize', function() {
            if (window.fcCalendar && typeof window.fcCalendar.updateSize === 'function') {
                try { window.fcCalendar.updateSize(); } catch(e){}
            }
        });

        // Blocage modal functions
        function openBlocageModal() {
            document.getElementById('blocageModal').style.display = 'block';
            // Reset form
            document.getElementById('blocageForm').reset();

            // Prefill selected bien if user clicked a bien in the wheel
            if (currentBienId) {
                const sel = document.getElementById('blocage-bien');
                if (sel) sel.value = currentBienId;
            }

            // Mini calendar removed: date inputs `#blocage-date-debut` and `#blocage-date-fin` are used instead.
        }

        function closeBlocageModal() {
            document.getElementById('blocageModal').style.display = 'none';
            document.getElementById('blocageForm').reset();
        }

        async function submitBlocageForm(event) {
            event.preventDefault();

            const bienId = document.getElementById('blocage-bien').value;
            const dateDebut = document.getElementById('blocage-date-debut').value;
            const dateFin = document.getElementById('blocage-date-fin').value;
            const motif = document.getElementById('blocage-motif').value;
            const commentaire = document.getElementById('blocage-commentaire').value.trim() || null;

            if (!bienId || !dateDebut || !dateFin || !motif) {
                alert('Veuillez remplir tous les champs obligatoires');
                return;
            }

            if (new Date(dateFin) < new Date(dateDebut)) {
                alert('La date de fin doit être après la date de début');
                return;
            }

            try {
                const response = await fetch('/proprietaire/calendar/block', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        bien_id: parseInt(bienId),
                        start: dateDebut,
                        end: dateFin,
                        motif: motif,
                        commentaire: commentaire
                    })
                });

                const result = await response.json();

                    if (response.ok && result.success) {
                        alert('Blocage créé avec succès !');
                        closeBlocageModal();
                        // Refresh calendar events using the global instance
                        if (window.fcCalendar) {
                            window.fcCalendar.refetchEvents();
                        }
                    } else {
                        alert('Erreur: ' + (result.error || 'Problème inconnu'));
                    }
            } catch (err) {
                alert('Erreur réseau: ' + err.message);
                console.error(err);
            }
        }
    </script>
</body>
</html>
