
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bien["designation_bien"]); ?> - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">

    <style>
        /* Carousel styles */
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


        @media (max-width: 600px) {
            .carousel-button { width: 36px; height: 36px; }
        }
    </style>

</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <main>
        <div class="bien-details">
            <div class="bien-header">
                <h1><?php echo htmlspecialchars($bien["designation_bien"]); ?></h1>

            </div>

            <div class="bien-photos">
                <div class="carousel">
                    <div class="slides">
                        <?php if (!empty($photos)): ?>
                            <?php foreach ($photos as $index => $photo): ?>
                                <div class="slide" data-index="<?php echo $index; ?>">
                                    <img src="<?php echo htmlspecialchars($photo["lien_photo"]); ?>" alt="<?php echo htmlspecialchars($photo["nom_photo"]); ?>" data-full="<?php echo htmlspecialchars($photo["lien_photo"]); ?>">
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
                    <?php endif; ?>

                    <?php if (!empty($photos) && count($photos) > 1): ?>
                        <div class="carousel-dots">
                            <?php for ($i = 0; $i < count($photos); $i++): ?>
                                <button data-dot="<?php echo $i; ?>" class="<?php echo $i === 0 ? 'active' : ''; ?>" aria-label="Aller à l'image <?php echo $i + 1; ?>"></button>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
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
                    <p><strong>Prix semaine actuel :</strong> <?php echo htmlspecialchars(($bien["prix_semaine"] ?? null) ? number_format($bien["prix_semaine"], 2, ',', ' ') . ' €' : 'Non renseigné'); ?></p>
                </div>
                <div class="info-block">
                    <h3>Description</h3>
                    <p><?php echo nl2br(htmlspecialchars($bien["description_biens"])); ?></p>
                </div>

                <?php 
                // Affichage du formulaire de réservation
                if (isset($_SESSION['user_id']) && !isset($_SESSION['is_admin']) && (in_array('Locataire', $_SESSION['user_roles'] ?? []) || in_array('Proprietaire', $_SESSION['user_roles'] ?? []))):
                ?>
                    <div class="info-block">
                        <h3>Réserver ce bien</h3>

                        <?php 
                        // Récupération des erreurs et des données de formulaire
                        $errors = $_SESSION['errors'] ?? [];
                        $old_input = $_SESSION['old_input'] ?? [];
                        unset($_SESSION['errors'], $_SESSION['old_input']);

                        if (!empty($errors)): 
                        ?>
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
                <?php elseif (!isset($_SESSION['user_id']) || isset($_SESSION['is_admin']) || (!in_array('Locataire', $_SESSION['user_roles'] ?? []) && !in_array('Proprietaire', $_SESSION['user_roles'] ?? []))): ?>
                    <div class="info-block">
                        <h3>Réserver ce bien</h3>
                        <p>Veuillez vous <a href="/login">connecter</a> pour effectuer une réservation.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> GlobeNight. Tous droits réservés.</p>
    </footer>
    <script>
        (function(){
            // Carousel logic (preserved)
            const slidesContainer = document.querySelector('.slides');
            if (!slidesContainer) return;
            const slides = Array.from(slidesContainer.querySelectorAll('.slide'));
            let current = 0;

            function showSlide(n) {
                if (slides.length === 0) return;
                current = (n + slides.length) % slides.length;
                slides.forEach((s, i) => {
                    s.style.display = (i === current) ? 'block' : 'none';
                });
                // update dots
                const dots = document.querySelectorAll('.carousel-dots button');
                dots.forEach(d => d.classList.remove('active'));
                const activeDot = document.querySelector('.carousel-dots button[data-dot="' + current + '"]') || document.querySelectorAll('.carousel-dots button')[current];
                if (activeDot) activeDot.classList.add('active');
            }

            // initialize
            showSlide(0);

            // Prev / Next
            const prevBtn = document.querySelector('.carousel-button.prev');
            const nextBtn = document.querySelector('.carousel-button.next');
            if (prevBtn) prevBtn.addEventListener('click', () => showSlide(current - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => showSlide(current + 1));

            // Dots
            document.querySelectorAll('.carousel-dots button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-dot'), 10);
                    showSlide(idx);
                });
            });

            // jQuery Magnify implementation
            $(document).ready(function() {
                // Initialisation de Magnify sur toutes les images du carrousel
                // On utilise l'attribut data-magnify-src pour l'image haute résolution
                $('.slide img').each(function() {
                    const fullSrc = $(this).data('full') || $(this).attr('src');
                    $(this).attr('data-magnify-src', fullSrc);
                    $(this).magnify({
                        speed: 200,
                        // Le plugin Magnify fonctionne comme une loupe.
                        // On s'assure que le curseur est de type 'zoom-in' dans le CSS (ligne 17)
                    });
                });
            });

            // small accessibility: keyboard nav for arrows (preserved)
            document.addEventListener('keydown', function(e){
                if (e.key === 'ArrowLeft') showSlide(current - 1);
                if (e.key === 'ArrowRight') showSlide(current + 1);
            });
        })();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/css/magnify.css">
    <script src="/js/jquery.magnify.min.js"></script>
</body>
</html>
