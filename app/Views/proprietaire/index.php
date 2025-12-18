<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Propriétaire - Goodnight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/calendar_proprio.css">
    <link rel="stylesheet" href="/css/dashboard-proprio.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="home-sunset">
    <?php include __DIR__ . '/../layout/navbar.php'; ?>
    <!-- Alert container for notifications (inserted dynamically) -->
    <div id="alertContainer"></div>

    <!-- Placeholders for removed wheel features to avoid JS errors -->
    <div id="bienDetails"></div>
    <div id="blocageSection"></div>
    <div id="blocagesList"><div id="blocagesContent"></div></div>

    <div class="dashboard">
        <h1>Tableau de bord Propriétaire</h1>

        <!-- KPI Summary -->
        <section class="kpi-bar" aria-label="Résumé des indicateurs">
            <div class="kpi-card">
                <div class="kpi-icon">🏠</div>
                <div class="kpi-value" id="kpi-total">0</div>
                <div class="kpi-label">Biens</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon">✅</div>
                <div class="kpi-value" id="kpi-valides">0</div>
                <div class="kpi-label">Validés</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon">⏳</div>
                <div class="kpi-value" id="kpi-attente">0</div>
                <div class="kpi-label">En attente</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-icon">📅</div>
                <div class="kpi-value" id="kpi-resa-avenir">—</div>
                <div class="kpi-label">Réservations à venir</div>
            </div>
        </section>

        <!-- Actions rapides -->
        <div class="quick-actions">
            <a class="qa-btn" href="/proprietaire/addBien">+ Ajouter un bien</a>
            <button class="qa-btn" type="button" onclick="openBlocageModal()">Créer un blocage</button>
            <a class="qa-btn" href="/proprietaire/myReservations">Voir mes réservations</a>
        </div>

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
                
                <!-- Boutons de sélection rapide -->
                <div class="filter-actions">
                    <button class="btn-filter-action" onclick="selectAllBiens()" title="Tout sélectionner">
                        <span>✓</span> Tout sélectionner
                    </button>
                    <button class="btn-filter-action" onclick="unselectAllBiens()" title="Tout désélectionner">
                        <span>✗</span> Tout désélectionner
                    </button>
                </div>

                <!-- Filtres par type de bien -->
                <?php if (!empty($typesBiens)): ?>
                <div class="filter-by-type">
                    <h4>Par type</h4>
                    <div class="type-filter-buttons">
                        <button class="btn-type-filter active" 
                                data-type-id="all"
                                onclick="filterByType('all')">
                            📋 Tous
                        </button>
                        <?php foreach($typesBiens as $type): ?>
                            <button class="btn-type-filter" 
                                    data-type-id="<?php echo $type['id_typebien']; ?>"
                                    onclick="filterByType(<?php echo $type['id_typebien']; ?>)">
                                <?php echo htmlspecialchars($type['desc_type_bien']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Filtres par statut -->
                <div class="filter-by-status">
                    <h4>Par statut</h4>
                    <div class="status-filter-buttons">
                        <button class="btn-status-filter active" data-status-id="all" onclick="filterByStatus('all')">Tous</button>
                        <button class="btn-status-filter" data-status-id="valide" onclick="filterByStatus('valide')">Validés</button>
                        <button class="btn-status-filter" data-status-id="en_attente" onclick="filterByStatus('en_attente')">En attente</button>
                    </div>
                </div>

                <div class="filter-list">
                    <?php foreach($biens ?? [] as $b): ?>
                        <label class="filter-item">
                            <input type="checkbox" 
                                   class="filter-checkbox" 
                                   data-bien-id="<?php echo $b['id_biens']; ?>"
                                   data-type-id="<?php echo $b['id_TypeBien']; ?>">
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
        <div class="content-area">
            <h2>Calendrier des réservations</h2>

            <div class="calendar-legend">
                <div class="calendar-instruction"><strong>Sélectionnez un ou plusieurs biens via la colonne de gauche</strong></div>
                <div class="calendar-legend-items">
                    <span class="legend-item"><span class="legend-dot reservations"></span>Réservations</span>
                    <span class="legend-item"><span class="legend-dot blocked"></span>Blocages</span>
                </div>
            </div>

            <div id="calendar"></div>

            <div class="calendar-actions">
                <button class="btn btn-primary" onclick="openBlocageModal()">+ Créer un blocage</button>
            </div>
        </div>

        <!-- Statistiques et graphiques -->
        <div class="stats-section">
            <div class="stats-header">
                <h2>📊 Statistiques et revenus</h2>
                <button class="btn-toggle-filters" onclick="toggleStatsFilters()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                        <circle cx="8" cy="6" r="2"></circle>
                        <circle cx="16" cy="12" r="2"></circle>
                        <circle cx="12" cy="18" r="2"></circle>
                    </svg>
                    Filtres avancés
                </button>
            </div>
            
            <!-- Panneau de filtres avancés (repliable) -->
            <div class="stats-filters-panel" id="statsFiltersPanel" style="display:none;">
                <div class="stats-filters-grid">
                    <!-- Filtre par bien (multi-sélection) -->
                    <div class="stats-filter-group stats-filter-biens-full">
                        <label class="stats-filter-label">🏠 Biens sélectionnés</label>
                        <div class="stats-biens-badges" id="statsBiensBadges">
                            <button class="stats-bien-badge active" data-bien-id="all" onclick="toggleBienFilter(this, 'all')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                </svg>
                                Tous les biens
                            </button>
                            <?php foreach($biens ?? [] as $b): ?>
                                <button class="stats-bien-badge" data-bien-id="<?php echo $b['id_biens']; ?>" onclick="toggleBienFilter(this, <?php echo $b['id_biens']; ?>)">
                                    <?php echo htmlspecialchars($b['designation_bien']); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Filtre par type de bien -->
                    <div class="stats-filter-group">
                        <label class="stats-filter-label">🏘️ Type de bien</label>
                        <select id="statsFilterType" class="stats-filter-select" onchange="applyStatsFilters()">
                            <option value="all">Tous types</option>
                            <?php foreach($typesBiens ?? [] as $type): ?>
                                <option value="<?php echo $type['id_typebien']; ?>"><?php echo htmlspecialchars($type['desc_type_bien']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Période personnalisée -->
                    <div class="stats-filter-group">
                        <label class="stats-filter-label">📅 Date début</label>
                        <input type="date" id="statsFilterDateDebut" class="stats-filter-input" onchange="applyStatsFilters()">
                    </div>
                    
                    <div class="stats-filter-group">
                        <label class="stats-filter-label">📅 Date fin</label>
                        <input type="date" id="statsFilterDateFin" class="stats-filter-input" onchange="applyStatsFilters()">
                    </div>
                    
                    <!-- Bouton reset -->
                    <div class="stats-filter-group stats-filter-actions">
                        <button class="btn-reset-filters" onclick="resetStatsFilters()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="1 4 1 10 7 10"></polyline>
                                <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                            </svg>
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Sélecteur de période -->
            <div class="stats-controls">
                <div class="period-selector">
                    <button class="btn-period" data-period="hour" onclick="changePeriod('hour')">24H</button>
                    <button class="btn-period" data-period="day" onclick="changePeriod('day')">7J</button>
                    <button class="btn-period active" data-period="month" onclick="changePeriod('month')">Mois</button>
                    <button class="btn-period" data-period="year" onclick="changePeriod('year')">Année</button>
                </div>
                
                <div class="date-selector" id="dateSelector">
                    <select id="yearSelect" onchange="updateCharts()">
                        <?php for($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo $y == date('Y') ? 'selected' : ''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            
            <!-- Indicateurs de comparaison -->
            <div class="comparison-bar" id="comparisonBar" style="display:none;">
                <div class="comparison-item">
                    <span class="comparison-label">📊 Réservations:</span>
                    <span class="comparison-value" id="compReservations">-</span>
                </div>
                <div class="comparison-item">
                    <span class="comparison-label">💰 Revenus:</span>
                    <span class="comparison-value" id="compRevenue">-</span>
                </div>
            </div>
            
            <!-- Graphiques principaux -->
            <div class="charts-grid">
                <div class="chart-card">
                    <h3>📊 Réservations</h3>
                    <canvas id="reservationsChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3>💰 Revenus (€)</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            
            <!-- Graphiques avancés -->
            <div class="charts-grid">
                <div class="chart-card">
                    <h3>📈 Taux d'occupation (%)</h3>
                    <canvas id="occupancyChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3>🏘️ Revenus par bien (€)</h3>
                    <canvas id="revenueByBienChart"></canvas>
                </div>
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

                <div class="fiche-item hidden-field" id="modal-raison-sociale-container">
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

            <form id="blocageForm" class="blocage-form" onsubmit="submitBlocageForm(event)">
                <div class="modal-body">
                    <!-- Mini FullCalendar removed: use the date inputs below to sélectionner la période. -->
                    <div class="fiche-item">
                        <label for="blocage-bien" class="fiche-label">Bien *</label>
                        <select id="blocage-bien" required>
                            <option value="">-- Sélectionnez un bien --</option>
                            <?php foreach($biens ?? [] as $b): ?>
                                <option value="<?php echo $b['id_biens']; ?>"><?php echo htmlspecialchars($b['designation_bien']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="fiche-row">
                        <div class="fiche-item">
                            <label for="blocage-date-debut" class="fiche-label">Date début *</label>
                            <input type="date" id="blocage-date-debut" required>
                        </div>
                        <div class="fiche-item">
                            <label for="blocage-date-fin" class="fiche-label">Date fin *</label>
                            <input type="date" id="blocage-date-fin" required>
                        </div>
                    </div>

                    <div class="fiche-item">
                        <label for="blocage-motif" class="fiche-label">Motif *</label>
                        <select id="blocage-motif" required>
                            <option value="">-- Sélectionnez un motif --</option>
                            <option value="personnel">Personnel</option>
                            <option value="entretien">Entretien</option>
                            <option value="fermeture">Fermeture</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <div class="fiche-item">
                        <label for="blocage-commentaire" class="fiche-label">Commentaire</label>
                        <textarea id="blocage-commentaire"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-close-modal" onclick="closeBlocageModal()">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-submit">Créer blocage</button>
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
        <div class="sunset">
            <div class="sun"></div>
            <div class="horizon"></div>
        </div>
        <p>&copy; 2025 Goodnight. Tous droits réservés.</p>
    </footer>

    <script>
        /* Variables globales */
        let currentIndex = 0;
        let currentBienId = null;
        const biens = <?php echo json_encode($biens ?? []); ?>;
        // FullCalendar global instance (accessible depuis les handlers)
        window.fcCalendar = null;

            // KPI computation
            (function updateKPIs(){
                try {
                    const total = Array.isArray(biens) ? biens.length : 0;
                    const valides = Array.isArray(biens) ? biens.filter(b => (b.statut_validation || '').toLowerCase() === 'valide').length : 0;
                    const attente = Array.isArray(biens) ? biens.filter(b => (b.statut_validation || '').toLowerCase() === 'en_attente').length : 0;
                    const elTotal = document.getElementById('kpi-total');
                    const elValides = document.getElementById('kpi-valides');
                    const elAttente = document.getElementById('kpi-attente');
                    if (elTotal) elTotal.textContent = total;
                    if (elValides) elValides.textContent = valides;
                    if (elAttente) elAttente.textContent = attente;
                } catch (e) { /* no-op */ }
                // Fetch events once to compute upcoming reservations
                try {
                    const url = '/proprietaire/calendar/events';
                    fetch(url)
                        .then(r => r.json())
                        .then(events => {
                            const today = new Date();
                            today.setHours(0,0,0,0);
                            const count = (events || []).filter(ev => {
                                if (!ev || !ev.extendedProps) return false;
                                if (ev.extendedProps.type !== 'reservation') return false;
                                const start = new Date(ev.start);
                                return start >= today;
                            }).length;
                            const el = document.getElementById('kpi-resa-avenir');
                            if (el) el.textContent = String(count);
                        })
                        .catch(() => {
                            const el = document.getElementById('kpi-resa-avenir');
                            if (el) el.textContent = '0';
                        });
                } catch (e) {
                    const el = document.getElementById('kpi-resa-avenir');
                    if (el) el.textContent = '0';
                }
            })();

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
            alert.innerHTML = message + ' <span class="alert-close" onclick="this.parentElement.remove()">×</span>';
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
                card.dataset.typeId = b.id_TypeBien;
                const status = (b.statut_validation || '').toLowerCase();
                card.dataset.status = status;

                const imgSrc = b.premiere_photo || '/img/default.jpg';
                card.innerHTML = `
                    <div class="badge-status ${status === 'valide' ? 'badge-valid' : (status === 'en_attente' ? 'badge-pending' : 'badge-refused')}">
                        ${status === 'valide' ? 'Validé' : (status === 'en_attente' ? 'En attente' : (status ? 'Refusé' : '—'))}
                    </div>
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

            // initial visual state - hide all cards
            hideAllCards();
        }

        // Cacher toutes les cartes au départ
        function hideAllCards() {
            const cards = Array.from(document.querySelectorAll('.card'));
            cards.forEach(card => {
                card.style.display = 'none';
                card.style.opacity = '0';
            });
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
            
            if (checked) {
                // Afficher la carte (nette)
                card.style.display = '';
                card.style.opacity = '1';
                card.style.filter = 'none';
                card.classList.add('selected');
                card.classList.remove('dimmed');
                card.classList.remove('slide-out');
            } else {
                // Animation de sortie
                card.classList.add('slide-out');
                card.classList.remove('selected');
                
                // Cacher la carte après l'animation
                setTimeout(() => {
                    if (!card.classList.contains('selected')) {
                        card.style.display = 'none';
                        card.style.opacity = '0';
                    }
                }, 500);
            }

            // Update currentBienId when a single bien is selected
            const selected = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(i => i.dataset.bienId);
            if (selected.length === 1) currentBienId = selected[0];
            else currentBienId = null;

            updateCardsVisualsForSelection();
        }

        // Mise à jour visuelle pour la sélection manuelle
        function updateCardsVisualsForSelection() {
            const selected = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(i => i.dataset.bienId);
            
            // If many selected, make a horizontal auto-scroll
            manageSelectedCarousel(selected);

            // Trigger calendar refresh to apply the new filter
            try { 
                if (window.fcCalendar && typeof window.fcCalendar.refetchEvents === 'function') 
                    window.fcCalendar.refetchEvents(); 
            } catch(e){}
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
            if (!wheel) return;

            // Clear existing content
            wheel.innerHTML = '';
            if (!Array.isArray(biens) || biens.length === 0) return;

            // Fonction pour créer une carte
            function createSlot(b) {
                const slot = document.createElement('div');
                slot.className = 'wheel-slot';
                const img = b.premiere_photo || '/img/default.jpg';
                slot.innerHTML = `
                    <img src="${img}" alt="${escapeHtml(b.designation_bien)}">
                    <div class="slot-body">
                        <h3>${escapeHtml(b.designation_bien)}</h3>
                        <p>${escapeHtml((b.description_biens || '').slice(0, 80))}</p>
                        <p style="color: var(--accent-primary); font-weight: 700;">${b.prix || ''}</p>
                    </div>
                `;
                slot.dataset.bienId = b.id_biens;
                slot.addEventListener('click', (e) => {
                    toggleBienSelection(b.id_biens);
                });
                return slot;
            }

            // Dupliquer les cartes au moins 3 fois pour créer l'effet de carousel infini
            const repetitions = Math.max(3, Math.ceil(15 / biens.length));
            
            for (let rep = 0; rep < repetitions; rep++) {
                biens.forEach((b) => {
                    wheel.appendChild(createSlot(b));
                });
            }

            // Démarrer l'auto-scroll
            startAutoScroll();
        }

        let autoScrollInterval = null;
        let isScrolling = false;

        function startAutoScroll() {
            const wheel = document.getElementById('wheel3d');
            if (!wheel) return;

            // Configuration
            const scrollSpeed = 1; // pixels par frame
            const frameRate = 30; // ms entre chaque frame

            // Arrêter l'auto-scroll au survol
            wheel.addEventListener('mouseenter', () => {
                if (autoScrollInterval) {
                    clearInterval(autoScrollInterval);
                    autoScrollInterval = null;
                }
            });

            // Reprendre l'auto-scroll quand la souris quitte
            wheel.addEventListener('mouseleave', () => {
                if (!autoScrollInterval) {
                    startScrolling();
                }
            });

            // Fonction de défilement
            function startScrolling() {
                autoScrollInterval = setInterval(() => {
                    wheel.scrollLeft += scrollSpeed;
                    
                    // Réinitialiser au milieu quand on atteint les 2/3 pour un effet infini
                    const maxScroll = wheel.scrollWidth - wheel.clientWidth;
                    const resetPoint = maxScroll * 0.66;
                    
                    if (wheel.scrollLeft >= resetPoint) {
                        wheel.scrollLeft = maxScroll * 0.33;
                    }
                }, frameRate);
            }

            // Démarrer l'animation
            startScrolling();
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

        // Fonctions de filtrage rapide
        function selectAllBiens() {
            const checkboxes = document.querySelectorAll('.filter-checkbox:not([style*="display: none"])');
            checkboxes.forEach(cb => {
                const parentItem = cb.closest('.filter-item');
                if (parentItem && parentItem.style.display !== 'none') {
                    cb.checked = true;
                    syncSelectionFromCheckbox(cb.dataset.bienId, true);
                }
            });
        }

        function unselectAllBiens() {
            const checkboxes = document.querySelectorAll('.filter-checkbox');
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    cb.checked = false;
                    syncSelectionFromCheckbox(cb.dataset.bienId, false);
                }
            });
        }

        function filterByType(typeId) {
            // Décocher toutes les checkboxes
            document.querySelectorAll('.filter-checkbox').forEach(cb => {
                cb.checked = false;
            });

            const allItems = document.querySelectorAll('.filter-item');
            const allCards = document.querySelectorAll('.cards-grid .card');
            
            if (typeId === null || typeId === 'all') {
                // Cacher toutes les cartes
                allCards.forEach(card => {
                    card.style.display = 'none';
                    card.style.opacity = '0';
                    card.classList.remove('selected');
                    card.classList.remove('dimmed');
                });
                // Afficher tous les items de filtre
                allItems.forEach(item => {
                    item.style.display = '';
                });
            } else {
                // Filtrer les items de la liste
                allItems.forEach(item => {
                    const checkbox = item.querySelector('.filter-checkbox');
                    if (checkbox && checkbox.dataset.typeId === String(typeId)) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Afficher les cartes du type sélectionné (nettes et visibles)
                allCards.forEach(card => {
                    if (card.dataset.typeId === String(typeId)) {
                        card.style.display = '';
                        card.style.opacity = '1';
                        card.style.pointerEvents = 'auto';
                        card.style.filter = 'none';
                        card.classList.add('selected');
                        card.classList.remove('dimmed');
                    } else {
                        card.style.display = 'none';
                        card.style.opacity = '0';
                        card.style.pointerEvents = 'none';
                        card.classList.remove('selected');
                        card.classList.remove('dimmed');
                    }
                });
            }

            // Mettre en surbrillance le bouton de type actif
            document.querySelectorAll('.btn-type-filter').forEach(btn => {
                btn.classList.remove('active');
            });
            const activeBtn = document.querySelector('.btn-type-filter[data-type-id="' + typeId + '"]');
            if (activeBtn) {
                activeBtn.classList.add('active');
            }

            // Rafraîchir le calendrier
            try { 
                if (window.fcCalendar && typeof window.fcCalendar.refetchEvents === 'function') 
                    window.fcCalendar.refetchEvents(); 
            } catch(e){}
        }

        function filterByStatus(statusId) {
            // Reset buttons state
            document.querySelectorAll('.btn-status-filter').forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.querySelector('.btn-status-filter[data-status-id="' + statusId + '"]');
            if (activeBtn) activeBtn.classList.add('active');

            const allItems = document.querySelectorAll('.filter-item');
            const allCards = document.querySelectorAll('.cards-grid .card');

            // Show all filter items (status is not represented as checkbox meta), focus on cards only
            allItems.forEach(item => { item.style.display = ''; });

            if (statusId === 'all') {
                // Do not change selection; only affect card visibility to show selected ones normally
                updateCardsVisuals();
            } else {
                allCards.forEach(card => {
                    if ((card.dataset.status || '') === statusId) {
                        card.style.display = '';
                        card.style.opacity = '1';
                        card.classList.add('selected');
                        card.classList.remove('dimmed');
                        card.style.filter = 'none';
                    } else {
                        card.style.display = 'none';
                        card.style.opacity = '0';
                        card.classList.remove('selected');
                    }
                });
            }

            // Refresh calendar to reflect current card set
            try {
                if (window.fcCalendar && typeof window.fcCalendar.refetchEvents === 'function') window.fcCalendar.refetchEvents();
            } catch(e) {}
        }
    </script>

    <!-- Scripts de gestion des graphiques -->
    <script>
        // Variables globales pour les graphiques
        let reservationsChartInstance = null;
        let revenueChartInstance = null;
        let occupancyChartInstance = null;
        let revenueByBienChartInstance = null;
        let currentPeriod = 'month';

        // Initialiser les graphiques au chargement
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
            updateCharts();
            updateAdvancedCharts();
        });

        function changePeriod(period) {
            currentPeriod = period;
            
            // Mise à jour des boutons actifs
            document.querySelectorAll('.btn-period').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.period === period) {
                    btn.classList.add('active');
                }
            });
            
            // Masquer/afficher le sélecteur d'année selon la période
            const yearSelect = document.getElementById('yearSelect');
            if (period === 'hour' || period === 'day') {
                yearSelect.style.display = 'none';
            } else {
                yearSelect.style.display = 'inline-block';
            }
            
            updateCharts();
        }

        function initCharts() {
            // Configuration commune pour tous les graphiques
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            };

            // Graphique des réservations
            const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
            reservationsChartInstance = new Chart(reservationsCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Réservations',
                        data: [],
                        backgroundColor: 'rgba(55, 136, 216, 0.8)',
                        borderColor: 'rgba(55, 136, 216, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: commonOptions
            });

            // Graphique des revenus (2 courbes superposées)
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            revenueChartInstance = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Réservations',
                            data: [],
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Revenus (€)',
                            data: [],
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Réservations',
                                color: 'rgba(59, 130, 246, 1)',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                color: 'rgba(59, 130, 246, 1)'
                            },
                            grid: {
                                color: 'rgba(59, 130, 246, 0.1)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Revenus (€)',
                                color: 'rgba(16, 185, 129, 1)',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                color: 'rgba(16, 185, 129, 1)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Graphique taux d'occupation
            const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
            occupancyChartInstance = new Chart(occupancyCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Taux d\'occupation (%)',
                        data: [],
                        backgroundColor: 'rgba(245, 158, 11, 0.8)',
                        borderColor: 'rgba(245, 158, 11, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            ...commonOptions.scales.y,
                            max: 100
                        }
                    }
                }
            });

            // Graphique revenus par bien
            const revenueByBienCtx = document.getElementById('revenueByBienChart').getContext('2d');
            revenueByBienChartInstance = new Chart(revenueByBienCtx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Revenus (€)',
                        data: [],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(199, 199, 199, 0.8)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 2,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right'
                        }
                    }
                }
            });
        }

        async function updateCharts() {
            const year = document.getElementById('yearSelect').value;
            
            let url = `/proprietaire/stats?period=${currentPeriod}`;
            if (currentPeriod === 'month' || currentPeriod === 'year') {
                url += `&year=${year}`;
            }
            
            try {
                const response = await fetch(url);
                const data = await response.json();
                
                // Mettre à jour le graphique des réservations
                reservationsChartInstance.data.labels = data.labels;
                reservationsChartInstance.data.datasets[0].data = data.reservations;
                reservationsChartInstance.update();
                
                // Mettre à jour le graphique des revenus (2 courbes)
                revenueChartInstance.data.labels = data.labels;
                revenueChartInstance.data.datasets[0].data = data.reservations; // Courbe bleue (axe gauche)
                revenueChartInstance.data.datasets[1].data = data.revenue;      // Courbe verte (axe droit)
                revenueChartInstance.update();
                
                // Afficher la comparaison si disponible
                if (data.comparison) {
                    displayComparison(data.comparison);
                } else {
                    document.getElementById('comparisonBar').style.display = 'none';
                }
            } catch (error) {
                console.error('Erreur lors de la récupération des statistiques:', error);
            }
        }
        
        function displayComparison(comparison) {
            const compBar = document.getElementById('comparisonBar');
            const compRes = document.getElementById('compReservations');
            const compRev = document.getElementById('compRevenue');
            
            // Réservations
            const resClass = comparison.reservationsDiff >= 0 ? 'positive' : 'negative';
            const resIcon = comparison.reservationsDiff >= 0 ? '↑' : '↓';
            compRes.className = 'comparison-value ' + resClass;
            compRes.innerHTML = `${resIcon} ${Math.abs(comparison.reservationsDiff)} (${comparison.reservationsPercent > 0 ? '+' : ''}${comparison.reservationsPercent}%)`;
            
            // Revenus
            const revClass = comparison.revenueDiff >= 0 ? 'positive' : 'negative';
            const revIcon = comparison.revenueDiff >= 0 ? '↑' : '↓';
            compRev.className = 'comparison-value ' + revClass;
            compRev.innerHTML = `${revIcon} ${Math.abs(comparison.revenueDiff).toFixed(2)}€ (${comparison.revenuePercent > 0 ? '+' : ''}${comparison.revenuePercent}%)`;
            
            compBar.style.display = 'flex';
        }

        async function updateAdvancedCharts() {
            const year = document.getElementById('yearSelect').value;
            
            try {
                const response = await fetch(`/proprietaire/stats/advanced?year=${year}`);
                const data = await response.json();
                
                // Mettre à jour le graphique du taux d'occupation
                const occupancyLabels = data.occupancy.map(item => item.bien);
                const occupancyData = data.occupancy.map(item => item.rate);
                
                occupancyChartInstance.data.labels = occupancyLabels;
                occupancyChartInstance.data.datasets[0].data = occupancyData;
                occupancyChartInstance.update();
                
                // Mettre à jour le graphique des revenus par bien
                const revenueLabels = data.revenue.map(item => item.bien);
                const revenueData = data.revenue.map(item => item.revenue);
                
                revenueByBienChartInstance.data.labels = revenueLabels;
                revenueByBienChartInstance.data.datasets[0].data = revenueData;
                revenueByBienChartInstance.update();
            } catch (error) {
                console.error('Erreur lors de la récupération des statistiques avancées:', error);
            }
        }

        // Mettre à jour les graphiques avancés quand l'année change
        document.getElementById('yearSelect').addEventListener('change', function() {
            updateAdvancedCharts();
        });

        /* ===== FILTRES AVANCÉS DES STATISTIQUES ===== */
        function toggleStatsFilters() {
            const panel = document.getElementById('statsFiltersPanel');
            if (panel.style.display === 'none') {
                panel.style.display = 'block';
            } else {
                panel.style.display = 'none';
            }
        }

        /* ===== TOGGLE BIEN FILTER ===== */
        function toggleBienFilter(button, bienId) {
            if (bienId === 'all') {
                // Si on clique sur "Tous", désactiver tous les autres et activer seulement "Tous"
                const allBadges = document.querySelectorAll('.stats-bien-badge');
                allBadges.forEach(badge => badge.classList.remove('active'));
                button.classList.add('active');
            } else {
                // Si on clique sur un bien spécifique
                const allButton = document.querySelector('.stats-bien-badge[data-bien-id="all"]');
                allButton.classList.remove('active');
                button.classList.toggle('active');
                
                // Si aucun bien n'est sélectionné, réactiver "Tous"
                const anyActive = document.querySelector('.stats-bien-badge.active:not([data-bien-id="all"])');
                if (!anyActive) {
                    allButton.classList.add('active');
                }
            }
            
            // Appliquer les filtres
            applyStatsFilters();
        }

        async function applyStatsFilters() {
            // Collecter les biens sélectionnés (badges actifs)
            const activeBadges = Array.from(document.querySelectorAll('.stats-bien-badge.active'));
            let selectedBienIds = [];
            
            const allActive = activeBadges.find(badge => badge.dataset.bienId === 'all');
            if (!allActive) {
                selectedBienIds = activeBadges.map(badge => badge.dataset.bienId);
            }

            const typeId = document.getElementById('statsFilterType').value;
            const dateDebut = document.getElementById('statsFilterDateDebut').value;
            const dateFin = document.getElementById('statsFilterDateFin').value;
            const year = document.getElementById('yearSelect').value;

            // Construire l'URL avec tous les filtres
            let url = `/proprietaire/stats?period=${currentPeriod}`;
            
            if (currentPeriod === 'month' || currentPeriod === 'year') {
                url += `&year=${year}`;
            }
            
            if (selectedBienIds.length > 0) {
                url += `&bien_ids=${selectedBienIds.join(',')}`;
            }
            
            if (typeId !== 'all') {
                url += `&type_id=${typeId}`;
            }
            
            if (dateDebut) {
                url += `&date_debut=${dateDebut}`;
            }
            
            if (dateFin) {
                url += `&date_fin=${dateFin}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();
                
                // Mettre à jour tous les graphiques avec les données filtrées
                reservationsChartInstance.data.labels = data.labels;
                reservationsChartInstance.data.datasets[0].data = data.reservations;
                reservationsChartInstance.update();
                
                revenueChartInstance.data.labels = data.labels;
                revenueChartInstance.data.datasets[0].data = data.reservations;
                revenueChartInstance.data.datasets[1].data = data.revenue;
                revenueChartInstance.update();
                
                if (data.comparison) {
                    displayComparison(data.comparison);
                } else {
                    document.getElementById('comparisonBar').style.display = 'none';
                }

                // Mettre à jour aussi les graphiques avancés avec les filtres
                updateAdvancedChartsWithFilters(selectedBienIds, typeId);
            } catch (error) {
                console.error('Erreur lors de l\'application des filtres:', error);
            }
        }

        async function updateAdvancedChartsWithFilters(selectedBienIds, typeId) {
            const year = document.getElementById('yearSelect').value;
            
            let url = `/proprietaire/stats/advanced?year=${year}`;
            
            if (Array.isArray(selectedBienIds) && selectedBienIds.length > 0) {
                url += `&bien_ids=${selectedBienIds.join(',')}`;
            }
            
            if (typeId !== 'all') {
                url += `&type_id=${typeId}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();
                
                const occupancyLabels = data.occupancy.map(item => item.bien);
                const occupancyData = data.occupancy.map(item => item.rate);
                
                occupancyChartInstance.data.labels = occupancyLabels;
                occupancyChartInstance.data.datasets[0].data = occupancyData;
                occupancyChartInstance.update();
                
                const revenueLabels = data.revenue.map(item => item.bien);
                const revenueData = data.revenue.map(item => item.revenue);
                
                revenueByBienChartInstance.data.labels = revenueLabels;
                revenueByBienChartInstance.data.datasets[0].data = revenueData;
                revenueByBienChartInstance.update();
            } catch (error) {
                console.error('Erreur lors de la mise à jour des graphiques avancés:', error);
            }
        }

        function resetStatsFilters() {
            // Réinitialiser tous les filtres
            // Biens: activer "Tous" et désactiver les autres
            const allBadges = document.querySelectorAll('.stats-bien-badge');
            allBadges.forEach(badge => {
                if (badge.dataset.bienId === 'all') {
                    badge.classList.add('active');
                } else {
                    badge.classList.remove('active');
                }
            });
            
            document.getElementById('statsFilterType').value = 'all';
            document.getElementById('statsFilterDateDebut').value = '';
            document.getElementById('statsFilterDateFin').value = '';
            
            // Recharger les graphiques sans filtres
            updateCharts();
            updateAdvancedCharts();
        }
    </script>
</body>
</html>
