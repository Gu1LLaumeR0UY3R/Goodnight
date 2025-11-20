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
            --text-light: #7f8c8d;
        }
        body { background: var(--bg); margin: 0; font-family: 'Segoe UI', sans-serif; }
        .dashboard { max-width: 1200px; margin: 40px auto; padding: 20px; text-align: center; }
        h1 { color: var(--text); margin-bottom: 10px; }
        .subtitle { color: var(--text-light); margin-bottom: 50px; }

        /* CARROUSEL CIRCULAIRE 3D */
        .wheel-container {
            position: relative;
            width: 600px;
            height: 500px;
            margin: 0 auto 80px;
            perspective: 1200px;
        }
        .wheel {
            position: absolute;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.1s linear;
        }
        .wheel-card {
            position: absolute;
            width: 280px;
            height: 380px;
            left: 160px;
            top: 60px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            opacity: 0.4;
            transform-style: preserve-3d;
            transition: all 0.6s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: grab;
        }
        .wheel-card.active {
            opacity: 1;
            transform: scale(1.15);
            box-shadow: 0 25px 60px rgba(254,157,21,0.4);
            border: 4px solid var(--primary);
            z-index: 100;
        }
        .wheel-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .wheel-info {
            padding: 20px;
            text-align: center;
        }
        .wheel-info h3 {
            margin: 0 0 10px;
            font-size: 19px;
            color: var(--text);
            font-weight: 600;
        }
        .wheel-info p {
            margin: 0;
            color: var(--text-light);
            font-size: 14px;
        }
        .all-biens-card {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .all-biens-card h3 { font-size: 26px; font-weight: 800; }
        .all-biens-card p { font-size: 18px; opacity: 0.95; }

        #calendar-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
            margin: 0 auto;
            max-width: 1100px;
        }
        #calendar-loader {
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0.97);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            z-index: 10;
            color: var(--primary);
            font-size: 22px;
        }
        .spinner {
            width: 70px;
            height: 70px;
            border: 8px solid #f0f0f0;
            border-top: 8px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <div class="dashboard">
        <h1>Tableau de bord Propriétaire</h1>
        <p class="subtitle">Maintenez le clic et faites tourner la roue pour choisir un bien</p>

        <!-- ROUE CIRCULAIRE 3D -->
        <div class="wheel-container">
            <div class="wheel" id="wheel">
                <!-- Tous les biens -->
                <div class="wheel-card active" data-bien-id="">
                    <div class="wheel-info all-biens-card">
                        <h3>Tous mes biens</h3>
                        <p>
                            <?php
                            $bienModel = new BienModel();
                            $biens = $bienModel->getBiensByProprietaire($_SESSION['user_id']);
                            echo count($biens) . ' bien' . (count($biens) > 1 ? 's' : '');
                            ?>
                        </p>
                    </div>
                </div>

                <?php foreach ($biens as $i => $bien):
                    $photo = !empty($bien['premiere_photo']) ? htmlspecialchars($bien['premiere_photo']) : '/images/no-photo.jpg';
                ?>
                    <div class="wheel-card" data-bien-id="<?= $bien['id_biens'] ?>">
                        <img src="<?= $photo ?>" alt="<?= htmlspecialchars($bien['designation_bien']) ?>">
                        <div class="wheel-info">
                            <h3><?= htmlspecialchars($bien['designation_bien']) ?></h3>
                            <p><?= htmlspecialchars($bien['rue_biens']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CALENDRIER -->
        <div id="calendar-container">
            <div id="calendar-loader">
                <div class="spinner"></div>
                <div>Chargement des réservations...</div>
            </div>
            <div id="calendar"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const wheel = document.getElementById('wheel');
            const cards = document.querySelectorAll('.wheel-card');
            const loader = document.getElementById('calendar-loader');
            let calendar;
            let angle = 0;
            let isDragging = false;
            let previousX = 0;

            const total = cards.length;
            const angleStep = 360 / total;

            function positionCards() {
                cards.forEach((card, i) => {
                    const cardAngle = i * angleStep;
                    const rotated = (cardAngle + angle) % 360;
                    const distance = Math.abs(Math.min(rotated > 180 ? 360 - rotated : rotated, 180));
                    const scale = 1 - (distance / 180) * 0.5;
                    const opacity = 0.3 + (1 - distance / 180) * 0.7;
                    const zIndex = Math.round(1000 - distance);

                    card.style.transform = `rotateY(${rotated}deg) translateZ(300px) scale(${scale})`;
                    card.style.opacity = opacity;
                    card.style.zIndex = zIndex;
                    card.classList.toggle('active', distance < 30);
                });
            }

            function loadEvents(bienId = '') {
                loader.style.display = 'flex';
                const url = bienId ? `/proprietaire/calendar/events?bien=${bienId}` : '/proprietaire/calendar/events';
                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        loader.style.display = 'none';
                        calendar.getEventSources().forEach(s => s.remove());
                        calendar.addEventSource(data.events.map(e => ({
                            ...e,
                            backgroundColor: '#FE9D15',
                            borderColor: '#e67e22',
                            textColor: 'white'
                        })));
                    });
            }

            // FullCalendar
            calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                locale: 'fr',
                buttonText: { today: 'Aujourd’hui', month: 'Mois', week: 'Semaine', day: 'Jour' },
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                height: 'auto',
                eventClick: info => {
                    info.jsEvent.preventDefault();
                    if (confirm(`Réservation #${info.event.id.replace('res-', '')}\n${info.event.title}\n\nVoir la liste ?`)) {
                        location.href = '/proprietaire/myReservations';
                    }
                }
            });
            calendar.render();
            loadEvents();

            // Drag & rotation
            wheel.addEventListener('mousedown', e => {
                isDragging = true;
                previousX = e.clientX;
                wheel.style.cursor = 'grabbing';
            });
            document.addEventListener('mousemove', e => {
                if (!isDragging) return;
                const delta = e.clientX - previousX;
                angle -= delta * 0.4;
                previousX = e.clientX;
                positionCards();
                // Trouver la carte active
                let activeCard = null;
                cards.forEach(card => {
                    if (card.classList.contains('active')) activeCard = card;
                });
                if (activeCard) loadEvents(activeCard.dataset.bienId || '');
            });
            document.addEventListener('mouseup', () => {
                isDragging = false;
                wheel.style.cursor = 'grab';
            });

            // Mobile
            let touchStart = 0;
            wheel.addEventListener('touchstart', e => { touchStart = e.touches[0].clientX; });
            wheel.addEventListener('touchmove', e => {
                const delta = e.touches[0].clientX - touchStart;
                angle -= delta * 0.6;
                touchStart = e.touches[0].clientX;
                positionCards();
                let activeCard = document.querySelector('.wheel-card.active');
                if (activeCard) loadEvents(activeCard.dataset.bienId || '');
            });

            positionCards();
        });
    </script>
</body>
</html>