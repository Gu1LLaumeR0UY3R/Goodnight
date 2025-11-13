<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <!-- Styles du carousel (inchangés) -->
    <style>
        /* ... (ton CSS existant, inchangé) ... */
        .bien-photos { position: relative; display: flex; justify-content: center; align-items: center; }
        .carousel { position: relative; width: 100%; max-width: 900px; }
        .slides { position: relative; overflow: hidden; }
        .slide { display: none; text-align: center; }
        .slide img { max-width: 100%; height: auto; cursor: zoom-in; border-radius: 6px; }
        .carousel-button { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: #fff; border: none; width: 44px; height: 44px; border-radius: 22px; cursor: pointer; font-size: 24px; line-height: 1; display: flex; align-items: center; justify-content: center; }
        .carousel-button:focus { outline: 2px solid #fff; }
        .carousel-button.prev { left: 8px; }
        .carousel-button.next { right: 8px; }
        .carousel-dots { text-align: center; margin-top: 8px; }
        .carousel-dots button { background: #ddd; border: none; width: 10px; height: 10px; border-radius: 50%; margin: 0 4px; cursor: pointer; }
        .carousel-dots button.active { background: #333; }
        .img-modal { display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; }
        .img-modal.open { display: flex; }
        .img-modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); }
        .img-modal-content { position: relative; max-width: 95%; max-height: 95%; z-index: 10000; }
        .img-modal-content img { width: auto; height: auto; max-width: 100%; max-height: 100%; border-radius: 6px; box-shadow: 0 8px 30px rgba(0,0,0,0.6); }
        .img-modal-close { position: absolute; top: -18px; right: -18px; background: #fff; color: #000; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-weight: bold; box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
        main.blurred { filter: blur(6px); transition: filter 0.15s ease-in-out; }
        @media (max-width: 600px) { .carousel-button { width: 36px; height: 36px; } }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="bien-details">
            <div class="bien-header">
                <h1><?php echo htmlspecialchars($bien["designation_bien"]); ?></h1>
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
                    <p><strong>Prix semaine actuel :</strong> 
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

                <!-- FORMULAIRE DE RÉSERVATION (CORRIGÉ) -->
                <?php 
                $userId = $_SESSION['user_id'] ?? null;
                $userRoles = $_SESSION['user_roles'] ?? []; // Tableau de rôles
                $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
                $isOwner = ($userId && isset($bien['id_locataire']) && $bien['id_locataire'] == $userId);
                                            
                // Vérification des rôles
                $isLocataire = in_array('Locataire', $userRoles);
                $isProprietaire = in_array('Propriétaire', $userRoles); // ATTENTION À L'ACCENT !
                                            
                // Peut réserver si : connecté, pas admin, a un rôle valide, et n'est pas le propriétaire du bien
                $canBook = $userId && !$isAdmin && ($isLocataire || $isProprietaire) && !$isOwner;
                ?>
                
                <?php if ($canBook): ?>
                    <div class="info-block">
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
                                    
                        <form action="/reservation/store" method="POST" class="form-reservation">
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
                    <div class="info-block">
                        <h3>Réserver ce bien</h3>
                        <p>
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
                    <div class="info-block">
                        <h3>Réserver ce bien</h3>
                        <p>Veuillez vous <a href="/login">connecter</a> pour effectuer une réservation.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>© <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Ton script carousel + modal (inchangé)
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