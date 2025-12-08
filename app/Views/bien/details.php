<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/sunset-background.css">
    <link rel="stylesheet" href="/css/bien-details.css">
    <!-- Modern FullCalendar & CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main class="page-shell">
        <div class="bien-details glass-card">
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
        <div class="bien-calendar-container glass-card">
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
            <div class="reservation-section-calendar glass-card">
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
            <div class="reservation-section-calendar glass-card">
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
            <div class="reservation-section-calendar glass-card">
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