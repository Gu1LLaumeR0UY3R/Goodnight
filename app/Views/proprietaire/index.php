<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Propriétaire - Goodnight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/calendar_proprio.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
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

    <!-- Modal pour afficher les détails d'un blocage existant -->
    <div id="blocageDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Détails du blocage</h2>
                <button class="close-btn" onclick="closeBlocageDetailsModal()">&times;</button>
            </div>

            <div class="modal-body">
                <div class="fiche-item">
                    <div class="fiche-label">Motif</div>
                    <div class="fiche-value" id="modal-blocage-motif">-</div>
                </div>

                <div class="fiche-row">
                    <div>
                        <div class="fiche-label">Date de début</div>
                        <div class="fiche-value" id="modal-blocage-debut">-</div>
                    </div>
                    <div>
                        <div class="fiche-label">Date de fin</div>
                        <div class="fiche-value" id="modal-blocage-fin">-</div>
                    </div>
                </div>

                <div class="fiche-item">
                    <div class="fiche-label">Commentaire</div>
                    <div class="fiche-value" id="modal-blocage-commentaire">-</div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-danger" onclick="deleteBlocageFromModal()">Supprimer</button>
                <button class="btn-close-modal" onclick="closeBlocageDetailsModal()">Fermer</button>
            </div>
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
                firstDay: 1, // semaine commence lundi
                locale: 'fr',
                height: 650,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour'
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
                        openBlocageDetailsModal(info.event);
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

        // Blocage details modal functions
        let currentBlocageEventId = null; // Store the event ID for deletion
        function openBlocageDetailsModal(event) {
            const ext = event.extendedProps || {};
            // Populate modal fields
            document.getElementById('modal-blocage-motif').textContent = (ext.motif || '-').toUpperCase();
            
            // Format dates
            const dateDebut = new Date(event.start);
            const dateFin = new Date(event.end);
            dateFin.setDate(dateFin.getDate() - 1); // end is exclusive, adjust for display
            document.getElementById('modal-blocage-debut').textContent = formatDateFR(dateDebut);
            document.getElementById('modal-blocage-fin').textContent = formatDateFR(dateFin);
            
            document.getElementById('modal-blocage-commentaire').textContent = ext.commentaire ? ext.commentaire : 'Aucun';
            
            // Store event ID for deletion
            currentBlocageEventId = event.id;
            
            // Show modal
            document.getElementById('blocageDetailsModal').style.display = 'block';
        }

        function closeBlocageDetailsModal() {
            document.getElementById('blocageDetailsModal').style.display = 'none';
            currentBlocageEventId = null;
        }

        async function deleteBlocageFromModal() {
            if (!currentBlocageEventId) return;
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce blocage ?')) return;

            try {
                const response = await fetch('/proprietaire/calendar/unblock', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ eventId: currentBlocageEventId })
                });

                const result = await response.json();
                
                if (response.ok && result.success) {
                    closeBlocageDetailsModal();
                    if (window.fcCalendar) {
                        window.fcCalendar.refetchEvents();
                    }
                } else {
                    alert('Erreur: ' + (result.error || 'Impossible de supprimer le blocage'));
                }
            } catch (err) {
                alert('Erreur réseau: ' + err.message);
                console.error(err);
            }
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
            const blocageDetailsModal = document.getElementById('blocageDetailsModal');
            if (event.target === blocageDetailsModal) {
                blocageDetailsModal.style.display = 'none';
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
