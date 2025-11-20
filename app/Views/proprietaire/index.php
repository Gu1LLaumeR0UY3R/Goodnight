<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Propriétaire - Goodnight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #FE9D15;
            --primary-light: #FEBB5F;
            --bg: #F7F7F7;
            --text: #2c3e50;
        }
        body { background: var(--bg); margin: 0; font-family: 'Segoe UI', sans-serif; }
        .dashboard { max-width: 1300px; margin: 40px auto; padding: 20px; text-align: center; }

        /* Roue 3D (identique à ta dernière version que tu kiffes) */
        .wheel-container { position: relative; width: 900px; height: 520px; margin: 50px auto 60px; perspective: 1600px; }
        .wheel { position: absolute; width: 100%; height: 100%; transform-style: preserve-3d; transition: transform 0.9s cubic-bezier(0.3,0.8,0.3,1); }
        .wheel-card { position: absolute; width: 300px; height: 420px; left: 50%; top: 50%; margin-left: -150px; margin-top: -210px;
            background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            transition: all 0.7s ease; opacity: 0.4; }
        .wheel-card.active { opacity: 1; transform: translateZ(80px) scale(1.25); border: 6px solid var(--primary);
            box-shadow: 0 40px 90px rgba(254,157,21,0.6); z-index: 100; }
        .wheel-card img { width: 100%; height: 200px; object-fit: cover; }
        .wheel-info { padding: 25px; text-align: center; }
        .wheel-info h3 { margin: 0 0 12px; font-size: 21px; color: var(--text); font-weight: 700; }
        .all-biens-card { background: linear-gradient(135deg, #FF8C00, #FFB800, #FFD700, #FF8C00); background-size: 300% 300%;
            animation: gradientShift 12s ease infinite; color: white; display: flex; flex-direction: column;
            justify-content: center; align-items: center; height: 100%; position: relative; overflow: hidden; }
        @keyframes gradientShift { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
        .all-biens-card h3 { font-size: 48px; font-weight: 900; margin: 0 0 20px; text-shadow: 0 4px 12px rgba(0,0,0,0.4); }
        .all-biens-card p { font-size: 32px; font-weight: 800; }

        /* Flèches stylées ‹ › */
        .nav-arrow { position: absolute; top: 50%; transform: translateY(-50%); background: var(--primary); color: white;
            border: none; width: 70px; height: 70px; border-radius: 50%; font-size: 42px; cursor: pointer; z-index: 200;
            box-shadow: 0 10px 30px rgba(0,0,0,0.35); transition: all 0.3s ease; }
        .nav-arrow:hover { background: #e67e22; transform: translateY(-50%) scale(1.2); }
        .nav-arrow.left { left: -40px; }
        .nav-arrow.right { right: -40px; }

        /* Bouton bloquer dates */
        .block-btn {
            display: inline-block; background: #e74c3c; color: white; padding: 12px 28px; border-radius: 50px;
            font-weight: bold; cursor: pointer; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(231,76,60,0.4);
            transition: all 0.3s; }
        .block-btn:hover { background: #c0392b; transform: translateY(-2px); }

        /* Calendrier + dates bloquées */
        #calendar-container { position: relative; background: white; border-radius: 18px; overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.18); max-width: 1200px; margin: 0 auto; }
        .fc-blocked-event { background: #c0392b !important; border-color: #a93226 !important; opacity: 0.9; }
        #calendar-loader { position: absolute; inset: 0; background: rgba(255,255,255,0.98); display: flex;
            flex-direction: column; align-items: center; justify-content: center; gap: 25px; z-index: 10; color: var(--primary); font-size: 24px; }
        .spinner { width: 80px; height: 80px; border: 9px solid #f0f0f0; border-top: 9px solid var(--primary);
            border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <div class="dashboard">
        <h1>Tableau de bord Propriétaire</h1>
        <p class="subtitle">Utilisez les flèches ‹ › pour naviguer entre vos biens</p>

        <!-- Roue 3D (inchangée) -->
        <div class="wheel-container">
            <button class="nav-arrow left" id="prevBtn">‹</button>
            <button class="nav-arrow right" id="nextBtn">›</button>
            <div class="wheel" id="wheel">
                <?php
                $bienModel = new BienModel();
                $biens = $bienModel->getBiensByProprietaire($_SESSION['user_id']);
                $total = count($biens) + 1;
                $angleStep = 360 / $total;
                $radius = 450;

                echo '<div class="wheel-card active" style="transform: rotateY(0deg) translateZ('.$radius.'px);" data-bien-id="">';
                echo '  <div class="wheel-info all-biens-card"><h3>TOUS MES BIENS</h3><p>'.count($biens).' bien'.(count($biens)>1?'s':'').'</p></div>';
                echo '</div>';

                foreach ($biens as $i => $bien):
                    $angle = ($i + 1) * $angleStep;
                    $photo = !empty($bien['premiere_photo']) ? htmlspecialchars($bien['premiere_photo']) : '/images/no-photo.jpg';
                ?>
                    <div class="wheel-card" style="transform: rotateY(<?= $angle ?>deg) translateZ(<?= $radius ?>px);" data-bien-id="<?= $bien['id_biens'] ?>">
                        <img src="<?= $photo ?>" alt="<?= htmlspecialchars($bien['designation_bien']) ?>">
                        <div class="wheel-info">
                            <h3><?= htmlspecialchars($bien['designation_bien']) ?></h3>
                            <p><?= htmlspecialchars($bien['rue_biens']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bouton + Calendrier -->
        <div onclick="toggleBlockMode()" class="block-btn">Bloquer des dates</div>

        <div id="calendar-container">
            <div id="calendar-loader"><div class="spinner"></div><div>Chargement...</div></div>
            <div id="calendar"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wheel = document.getElementById('wheel');
            const cards = document.querySelectorAll('.wheel-card');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const loader = document.getElementById('calendar-loader');
            const total = cards.length;
            const angleStep = 360 / total;
            let currentIndex = 0;
            let currentBienId = '';
            let calendar;
            let blockMode = false;

            // Mise à jour roue + filtre calendrier
            function updateWheel() {
                wheel.style.transform = `rotateY(${-currentIndex * angleStep}deg)`;
                cards.forEach((c,i) => c.classList.toggle('active', i===currentIndex));
                const bienId = cards[currentIndex].dataset.bienId || '';
                if (bienId !== currentBienId) { currentBienId = bienId; loadEvents(bienId); }
            }

            // Chargement événements + dates bloquées
            function loadEvents(bienId = '') {
                loader.style.display = 'flex';
                const url = bienId ? `/proprietaire/calendar/events?bien=${bienId}` : '/proprietaire/calendar/events';
                fetch(url).then(r => r.json()).then(data => {
                    loader.style.display = 'none';
                    calendar.getEventSources().forEach(s => s.remove());
                    calendar.addEventSource(data.events.map(e => ({
                        ...e,
                        backgroundColor: e.title.includes('Bloquée') ? '#c0392b' : '#FE9D15',
                        borderColor: e.title.includes('Bloquée') ? '#a93226' : '#e67e22',
                        textColor: 'white',
                        classNames: e.title.includes('Bloquée') ? 'fc-blocked-event' : ''
                    })));
                });
            }

            // FullCalendar
            calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                locale: 'fr',
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                buttonText: { today: 'Aujourd’hui', month: 'Mois', week: 'Semaine', day: 'Jour' },
                height: 'auto',
                selectable: true,
                selectOverlap: false,
                select: function(info) {
                    if (!blockMode) return;
                    if (confirm(`Bloquer du ${info.startStr} au ${info.endStr} ?`)) {
                        fetch('/proprietaire/calendar/block', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                bien_id: currentBienId || null,
                                start: info.startStr,
                                end: info.endStr
                            })
                        }).then(() => {
                            calendar.addEvent({
                                title: 'Bloquée par le propriétaire',
                                start: info.startStr,
                                end: info.endStr,
                                backgroundColor: '#c0392b',
                                borderColor: '#a93226',
                                classNames: 'fc-blocked-event'
                            });
                        });
                    }
                    calendar.unselect();
                },
                eventClick: function(info) {
                    if (info.event.title.includes('Bloquée') && confirm('Supprimer ce blocage ?')) {
                        info.event.remove();
                        fetch('/proprietaire/calendar/unblock', {
                            method: 'POST',
                            body: JSON.stringify({ eventId: info.event.id })
                        });
                    } else {
                        if (confirm(`Réservation #${info.event.id.replace('res-','')}\n${info.event.title}\nVoir la liste ?`)) {
                            location.href = '/proprietaire/myReservations';
                        }
                    }
                }
            });
            calendar.render();
            loadEvents();

            // Navigation flèches
            prevBtn.onclick = () => { currentIndex = currentIndex > 0 ? currentIndex - 1 : total - 1; updateWheel(); };
            nextBtn.onclick = () => { currentIndex = (currentIndex + 1) % total; updateWheel(); };

            // Mode blocage
            window.toggleBlockMode = function() {
                blockMode = !blockMode;
                document.querySelector('.block-btn').textContent = blockMode ? "Mode blocage activé – Cliquez pour quitter" : "Bloquer des dates";
                document.querySelector('.block-btn').style.background = blockMode ? '#27ae60' : '#e74c3c';
                calendar.setOption('selectable', blockMode);
            };

            updateWheel();
        });
    </script>
</body>
</html>