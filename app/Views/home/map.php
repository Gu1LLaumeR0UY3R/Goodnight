<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte des biens - GlobeNight</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css' rel='stylesheet' />
    <style>
        #map {
            height: calc(100vh - 80px);
            width: 100%;
            margin-top: 0;
        }
        .map-container {
            position: relative;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            padding: 10px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .back-button:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .mapboxgl-popup-content {
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .mapboxgl-popup-close-button {
            font-size: 20px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>

    <div class="map-container">
        <a href="/" class="back-button">← Retour à la liste</a>
        <div id="map"></div>
    </div>

    <script src='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js'></script>
    <script>
        // Clé API Mapbox (à remplacer par votre clé)
        mapboxgl.accessToken = 'pk.eyJ1IjoiYW5kYWthMTkiLCJhIjoiY21peDRxbmhxMDBoaTNlc2QzcWZsZW13dSJ9.jyFBBRN_qNE26YrDrbhPKA';

        // Initialiser la carte centrée sur la France
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12',
            center: [1.888334, 46.603354],
            zoom: 5
        });

        // Ajouter les contrôles de navigation
        map.addControl(new mapboxgl.NavigationControl());

        // Données des biens avec leurs coordonnées
        const biens = <?php echo json_encode($biens ?? []); ?>;

        // Ajouter un marqueur pour chaque bien
        biens.forEach(bien => {
            if (bien.ville_latitude_deg && bien.ville_longitude_deg) {
                const popupContent = `
                    <div style="min-width: 200px;">
                        <h3 style="margin: 0 0 10px 0; color: #667eea; font-size: 16px;">${bien.designation_bien}</h3>
                        <p style="margin: 5px 0; font-size: 14px;"><strong>Type:</strong> ${bien.type_bien_nom}</p>
                        <p style="margin: 5px 0; font-size: 14px;"><strong>Commune:</strong> ${bien.commune_nom}</p>
                        <p style="margin: 5px 0; font-size: 14px;"><strong>Superficie:</strong> ${bien.superficie_biens} m²</p>
                        <p style="margin: 5px 0; font-size: 14px;"><strong>Couchages:</strong> ${bien.nb_couchage}</p>
                        <p style="margin: 5px 0; font-size: 14px;"><strong>Prix:</strong> ${bien.prix_semaine ? new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(bien.prix_semaine) + '/semaine' : 'Non renseigné'}</p>
                        <a href="/bien/${bien.id_biens}" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 5px; font-size: 14px;">Voir les détails</a>
                    </div>
                `;

                const popup = new mapboxgl.Popup({ offset: 25 })
                    .setHTML(popupContent);

                new mapboxgl.Marker({ color: '#667eea' })
                    .setLngLat([bien.ville_longitude_deg, bien.ville_latitude_deg])
                    .setPopup(popup)
                    .addTo(map);
            }
        });

        // Ajuster automatiquement le zoom pour voir tous les marqueurs
        if (biens.length > 0) {
            const coordinates = biens
                .filter(b => b.ville_latitude_deg && b.ville_longitude_deg)
                .map(b => [b.ville_longitude_deg, b.ville_latitude_deg]);

            if (coordinates.length > 0) {
                const bounds = coordinates.reduce((bounds, coord) => {
                    return bounds.extend(coord);
                }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));

                map.fitBounds(bounds, {
                    padding: 50,
                    maxZoom: 12
                });
            }
        }
    </script>
</body>
</html>
