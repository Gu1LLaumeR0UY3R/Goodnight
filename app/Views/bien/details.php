<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <!-- Modern FullCalendar & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <style>
        /* Reset & Variables */
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
        }

        main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Header Section */
        .bien-details {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .bien-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .bien-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .bien-header .proprietaire-info {
            font-size: 1.1rem;
            opacity: 0.95;
            font-weight: 400;
            margin-top: 0.5rem;
        }

        /* Carousel */
        .bien-photos {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--bg-light);
            padding: 2rem 1rem;
        }

        .carousel {
            position: relative;
            width: 100%;
            max-width: 1000px;
        }

        .slides {
            position: relative;
            overflow: hidden;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
        }

        .slide {
            display: none;
            text-align: center;
        }

        .slide img {
            max-width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: cover;
            cursor: zoom-in;
            border-radius: var(--radius);
        }

        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 152, 0, 0.9);
            color: #fff;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 28px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            box-shadow: var(--shadow-md);
        }

        .carousel-button:hover {
            background: var(--primary-dark);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-button:focus { outline: 2px solid #fff; }
        .carousel-button.prev { left: 12px; }
        .carousel-button.next { right: 12px; }

        .carousel-dots {
            text-align: center;
            margin-top: 12px;
        }

        .carousel-dots button {
            background: #ddd;
            border: none;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin: 0 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .carousel-dots button.active {
            background: var(--primary);
            transform: scale(1.3);
        }

        /* Modal Zoom */
        .img-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .img-modal.open { display: flex; }

        .img-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(8px);
        }

        .img-modal-content {
            position: relative;
            max-width: 95%;
            max-height: 95%;
            z-index: 10000;
        }

        .img-modal-content img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;
            border-radius: var(--radius);
            box-shadow: 0 12px 48px rgba(0,0,0,0.8);
        }

        .img-modal-close {
            position: absolute;
            top: -20px;
            right: -20px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s;
        }

        .img-modal-close:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }

        main.blurred {
            filter: blur(6px);
            transition: filter 0.15s ease-in-out;
        }

        /* Info Section */
        .bien-info {
            padding: 2rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .info-block {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
        }

        .info-block:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .info-block h3 {
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
        }

        .info-block p {
            line-height: 1.8;
            color: var(--text-light);
            margin: 0.5rem 0;
        }

        .info-block p strong {
            color: var(--text-dark);
            font-weight: 600;
        }

        /* Form Styling */
        .form-reservation {
            display: grid;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-group input[type="date"] {
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input[type="date"]:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.1);
        }

        .btn {
            padding: 0.9rem 2rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #c62828;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        /* Calendar Section */
        .bien-calendar-container {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            margin-bottom: 0;
        }

        .bien-calendar-container h2 {
            color: white;
            margin-top: 0;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        #bienCalendar {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
        }

        /* Reservation Form Below Calendar */
        .reservation-section-calendar {
            background: white;
            border-radius: 0 0 var(--radius) var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .reservation-section-calendar h3 {
            color: var(--primary);
            font-size: 1.8rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            text-align: center;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 1rem;
        }

        .reservation-section-calendar .form-reservation {
            max-width: 600px;
            margin: 0 auto;
            background: var(--bg-light);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-sm);
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 2rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (min-width: 768px) {
            .bien-info {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .carousel-button {
                width: 40px;
                height: 40px;
                font-size: 24px;
            }

            .bien-header h1 {
                font-size: 1.8rem;
            }

            .img-modal-close {
                top: -15px;
                right: -15px;
                width: 35px;
                height: 35px;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="bien-details">
            <div class="bien-header">
                <h1><?php echo htmlspecialchars($bien["designation_bien"]); ?></h1>
                <?php 
                    // Afficher le nom du propriétaire
                    $proprietaireNom = '';
                    if (!empty($bien['proprietaire_raison_sociale'])) {
                        $proprietaireNom = $bien['proprietaire_raison_sociale'];
                    } elseif (!empty($bien['proprietaire_prenom']) && !empty($bien['proprietaire_nom'])) {
                        $proprietaireNom = $bien['proprietaire_prenom'] . ' ' . $bien['proprietaire_nom'];
                    }
                    if ($proprietaireNom): 
                ?>
                    <p class="proprietaire-info">Propriété de <?php echo htmlspecialchars($proprietaireNom); ?></p>
                <?php endif; ?>
            </div>

            

            <!-- Carousel (inchangé) -->
            <div class="bien-photos">
                <div class="carousel">
                    <div class="slides">
                        <?php if (!empty($photos)): ?>
                            <?php foreach ($photos as $index => $photo): ?>
                                <div class="slide" data-index="<?php echo $index; ?>">
                                    <img src="<?php echo htmlspecialchars($photo["lien_photo"]); ?>" 
                                         alt="<?php echo htmlspecialchars($photo["nom_photo"]); ?>" 
                                         data-full="<?php echo htmlspecialchars($photo["lien_photo"]); ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="slide" data-index="0">
                                <img src="/images/default.jpg" alt="Aucune photo disponible">
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($photos) && count($photos) > 1): ?>
                        <button class="carousel-button prev" aria-label="Image précédente">‹</button>
                        <button class="carousel-button next" aria-label="Image suivante">›</button>
                        <div class="carousel-dots">
                            <?php for ($i = 0; $i < count($photos); $i++): ?>
                                <button data-dot="<?php echo $i; ?>" class="<?php echo $i === 0 ? 'active' : ''; ?>" 
                                        aria-label="Aller à l'image <?php echo $i + 1; ?>"></button>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Modal zoom (inchangé) -->
            <div id="imgModal" class="img-modal" aria-hidden="true">
                <div class="img-modal-backdrop" data-close></div>
                <div class="img-modal-content">
                    <button class="img-modal-close" aria-label="Fermer">X</button>
                    <img src="" alt="Agrandissement" id="modalImage">
                </div>
            </div>

            <div class="bien-info">
                <div class="info-block">
                    <h3>Informations Générales</h3>
                    <p><strong>Type :</strong> <?php echo htmlspecialchars($bien["type_bien_nom"]); ?></p>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($bien["rue_biens"]); ?>, <?php echo htmlspecialchars($bien["complement_biens"]); ?></p>
                    <p><strong>Commune :</strong> <?php echo htmlspecialchars($bien["commune_nom"]); ?></p>
                    <p><strong>Superficie :</strong> <?php echo htmlspecialchars($bien["superficie_biens"]); ?> m²</p>
                    <p><strong>Nombre de couchages :</strong> <?php echo htmlspecialchars($bien["nb_couchage"]); ?></p>
                    <p><strong>Animaux acceptés :</strong> <?php echo $bien["animaux_biens"] ? 'Oui' : 'Non'; ?></p>
                    <p><strong>Prix jour actuel :</strong> 
                        <?php echo ($bien["prix_semaine"] ?? null) 
                            ? number_format($bien["prix_semaine"], 2, ',', ' ') . ' €' 
                            : 'Non renseigné'; ?>
                    </p>
                    <p>
                         <?php
                            // DEBUG - À RETIRER après vérification
                            echo "<!-- DEBUG SESSION -->";
                            echo "<!-- user_id: " . ($_SESSION['user_id'] ?? 'NON DÉFINI') . " -->";
                            echo "<!-- is_admin: " . (isset($_SESSION['is_admin']) ? 'OUI' : 'NON') . " -->";
                            echo "<!-- user_roles: " . print_r($_SESSION['user_roles'] ?? [], true) . " -->";
                            echo "<!-- END DEBUG -->";
                        ?>
                    </p>
                </div>

                <div class="info-block">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($bien["description_biens"])); ?></p>
                </div>
            </div>
        </div>

        <!-- Calendrier des réservations -->
        <div class="bien-calendar-container">
          <h2>Calendrier des réservations</h2>
          
          <!-- Légende -->
          <div style="background: white; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; display: flex; gap: 2rem; justify-content: center; align-items: center;">
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                  <span style="display: inline-block; width: 20px; height: 20px; background: #4caf50; border-radius: 4px;"></span>
                  <span style="color: var(--text-dark); font-weight: 500;">Réservations</span>
              </div>
              <div style="display: flex; align-items: center; gap: 0.5rem;">
                  <span style="display: inline-block; width: 20px; height: 20px; background: #ff7f50; border-radius: 4px;"></span>
                  <span style="color: var(--text-dark); font-weight: 500;">Périodes bloquées</span>
              </div>
          </div>
          
          <div id="bienCalendar"></div>
        </div>

        <!-- FORMULAIRE DE RÉSERVATION (En dessous du calendrier) -->
        <?php 
        $userId = $_SESSION['user_id'] ?? null;
        $userRoles = $_SESSION['user_roles'] ?? []; // Tableau de rôles
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
        $isOwner = ($userId && isset($bien['id_locataire']) && $bien['id_locataire'] == $userId);
                                    
        // Vérification des rôles
        $isLocataire = in_array('Locataire', $userRoles);
        $isPropriétaire = in_array('Propriétaire', $userRoles); // ATTENTION À L'ACCENT !
                                    
        // Peut réserver si : connecté, pas admin, a un rôle valide, et n'est pas le propriétaire du bien
        $canBook = $userId && !$isAdmin && ($isLocataire || $isPropriétaire) && !$isOwner;
        ?>
        
        <?php if ($canBook): ?>
            <div class="reservation-section-calendar">
                <h3>Réserver ce bien</h3>
        
                <?php 
                $errors = $_SESSION['errors'] ?? [];
                $old_input = $_SESSION['old_input'] ?? [];
                unset($_SESSION['errors'], $_SESSION['old_input']);
                ?>
        
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                            
                <form action="/reservation/store" method="POST" class="form-reservation" id="reservationForm">
                    <input type="hidden" name="id_biens" value="<?php echo htmlspecialchars($bien['id_biens']); ?>">
                            
                    <div class="form-group">
                        <label for="date_debut">Date de début :</label>
                        <input type="date" id="date_debut" name="date_debut" required 
                               value="<?php echo htmlspecialchars($old_input['date_debut'] ?? date('Y-m-d')); ?>"
                               min="<?php echo date('Y-m-d'); ?>">
                    </div>
                            
                    <div class="form-group">
                        <label for="date_fin">Date de fin :</label>
                        <input type="date" id="date_fin" name="date_fin" required 
                               value="<?php echo htmlspecialchars($old_input['date_fin'] ?? date('Y-m-d', strtotime('+7 days'))); ?>"
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                            
                    <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
                </form>
            </div>
                            
        <?php elseif ($userId): ?>
            <!-- Connecté mais pas autorisé à réserver -->
            <div class="reservation-section-calendar">
                <h3>Réserver ce bien</h3>
                <p style="text-align: center; color: #666;">
                    <?php if ($isOwner): ?>
                        Vous êtes le propriétaire de ce bien, vous ne pouvez pas le réserver.
                    <?php elseif ($isAdmin): ?>
                        Les administrateurs ne peuvent pas effectuer de réservations.
                    <?php elseif (empty($userRoles)): ?>
                        Aucun rôle n'est assigné à votre compte. Veuillez contacter l'administrateur.
                    <?php else: ?>
                        Rôle actuel : <?php echo implode(', ', $userRoles); ?>. 
                        Vous devez être Locataire ou Propriétaire pour réserver.
                    <?php endif; ?>
                </p>
            </div>
                    
        <?php else: ?>
            <!-- Non connecté -->
            <div class="reservation-section-calendar">
                <h3>Réserver ce bien</h3>
                <p style="text-align: center;">Veuillez vous <a href="/login" style="color: var(--primary); text-decoration: underline;">connecter</a> pour effectuer une réservation.</p>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>© <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/node_modules/@fullcalendar/core/index.global.min.js"></script>
    <script src="/node_modules/@fullcalendar/daygrid/index.global.min.js"></script>
    <script src="/node_modules/@fullcalendar/interaction/index.global.min.js"></script>
    <script src="/node_modules/@fullcalendar/core/locales/fr.global.min.js"></script>
    <script>
        // Ton script carousel + modal (inchangé)
            
            // Initialisation de FullCalendar
            document.addEventListener('DOMContentLoaded', function() {
                // Initialisation du calendrier unique
                const bienCalendarEl = document.getElementById('bienCalendar');
                if (bienCalendarEl) {
                    const bienCalendar = new FullCalendar.Calendar(bienCalendarEl, {
                        initialView: 'dayGridMonth',
                        locale: 'fr',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek'
                        },
                        events: '/get_reservations.php?id_biens=<?php echo htmlspecialchars($bien["id_biens"]); ?>',
                        eventDidMount: function(info) {
                            info.el.querySelector('.fc-event-title').textContent = 'Réservé';
                        },
                        selectable: true,
                        select: function(info) {
                            // Remplir automatiquement les champs de date du formulaire
                            const startDate = info.startStr;
                            // FullCalendar utilise une date de fin exclusive, donc on retire 1 jour
                            const endDate = new Date(info.end);
                            endDate.setDate(endDate.getDate() - 1);
                            const endDateStr = endDate.toISOString().split('T')[0];
                            
                            const dateDebutInput = document.getElementById('date_debut');
                            const dateFinInput = document.getElementById('date_fin');
                            
                            if (dateDebutInput) dateDebutInput.value = startDate;
                            if (dateFinInput) dateFinInput.value = endDateStr;
                            
                            // Faire défiler vers le formulaire
                            const formContainer = document.querySelector('.form-reservation');
                            if (formContainer) {
                                formContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        },
                        eventClick: function(info) {
                            info.jsEvent.preventDefault();
                        }
                    });
                    bienCalendar.render();
                }

                // Popup confirmation calcul prix total (prix jour * nb jours)
                const dailyPrice = <?php echo json_encode($bien['prix_semaine'] ?? null); ?>; // Interprété comme prix/jour
                const form = document.getElementById('reservationForm');
                if (form) {
                    form.addEventListener('submit', function(e){
                        const startEl = document.getElementById('date_debut');
                        const endEl = document.getElementById('date_fin');
                        if (!startEl || !endEl) return;
                        const startDate = new Date(startEl.value);
                        const endDate = new Date(endEl.value);
                        if (isNaN(startDate.getTime()) || isNaN(endDate.getTime()) || endDate < startDate) {
                            return; // Laisser la validation HTML faire son travail
                        }
                        const diffMs = endDate.getTime() - startDate.getTime();
                        const dayCount = Math.round(diffMs / (1000*60*60*24)) + 1; // Inclusif
                        if (!dailyPrice) {
                            if (!confirm(`Durée: ${dayCount} jour(s)\nPrix: Non renseigné\nConfirmer la réservation ?`)) {
                                e.preventDefault();
                            }
                            return;
                        }
                        const total = dailyPrice * dayCount;
                        const message = `Durée: ${dayCount} jour(s)\nPrix jour: ${Number(dailyPrice).toFixed(2)} €\nTotal: ${total.toFixed(2)} €\n\nConfirmer la réservation ?`;
                        if (!confirm(message)) {
                            e.preventDefault();
                        }
                    });
                }
            });
        (function(){
            const slidesContainer = document.querySelector('.slides');
            if (!slidesContainer) return;
            const slides = Array.from(slidesContainer.querySelectorAll('.slide'));
            let current = 0;

            function showSlide(n) {
                if (slides.length === 0) return;
                current = (n + slides.length) % slides.length;
                slides.forEach((s, i) => s.style.display = (i === current) ? 'block' : 'none');
                document.querySelectorAll('.carousel-dots button').forEach(d => d.classList.remove('active'));
                const activeDot = document.querySelector(`.carousel-dots button[data-dot="${current}"]`);
                if (activeDot) activeDot.classList.add('active');
            }
            showSlide(0);

            document.querySelector('.carousel-button.prev')?.addEventListener('click', () => showSlide(current - 1));
            document.querySelector('.carousel-button.next')?.addEventListener('click', () => showSlide(current + 1));
            document.querySelectorAll('.carousel-dots button').forEach(btn => {
                btn.addEventListener('click', () => showSlide(parseInt(btn.dataset.dot, 10)));
            });

            // Modal
            const modal = document.getElementById('imgModal');
            const modalImage = document.getElementById('modalImage');
            const mainEl = document.querySelector('main');
            let ignoreBackdropClick = false;

            slides.forEach(s => {
                const img = s.querySelector('img');
                if (!img) return;
                s.addEventListener('click', e => e.stopPropagation());
                img.addEventListener('click', function(e) {
                    e.preventDefault(); e.stopPropagation();
                    const src = this.dataset.full || this.src;
                    modal.classList.add('open');
                    modal.setAttribute('aria-hidden', 'false');
                    mainEl.classList.add('blurred');
                    modalImage.src = src;
                    ignoreBackdropClick = true;
                    setTimeout(() => ignoreBackdropClick = false, 300);
                });
            });

            function closeModal() {
                modal.classList.remove('open');
                modal.setAttribute('aria-hidden', 'true');
                modalImage.src = '';
                mainEl.classList.remove('blurred');
            }

            modal.querySelector('.img-modal-close')?.addEventListener('click', closeModal);
            modal.querySelector('[data-close]')?.addEventListener('click', function(e) {
                if (ignoreBackdropClick || e.target !== this) return;
                closeModal();
            });
            modal.querySelector('.img-modal-content')?.addEventListener('click', e => e.stopPropagation());
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeModal();
                if (!modal.classList.contains('open')) {
                    if (e.key === 'ArrowLeft') showSlide(current - 1);
                    if (e.key === 'ArrowRight') showSlide(current + 1);
                }
            });
        })();
    </script>
</body>
</html>