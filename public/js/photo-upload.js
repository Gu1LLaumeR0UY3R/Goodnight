
/**
 * GlobeNight - Upload de Photos avec Drag & Drop
 * 
 * Ce script gère la fonctionnalité de glisser-déposer (drag & drop) pour l'upload de photos
 * et affiche des miniatures de prévisualisation des images sélectionnées.
 * 
 * Fonctionnalités principales :
 * - Drag & Drop de fichiers images
 * - Prévisualisation des images avant upload
 * - Suppression individuelle des images
 * - Validation que seules les images sont acceptées
 * - Gestion de plusieurs zones d'upload sur une même page
 */

(function() {
    'use strict'; // Mode strict pour un code plus sûr

    /**
     * Initialise le système de drag & drop pour toutes les zones d'upload de photos
     * Recherche tous les éléments avec la classe .photo-drop-zone et configure leurs événements
     */
    function initPhotoUpload() {
        // Sélectionne toutes les zones de dépôt de photos sur la page
        const dropZones = document.querySelectorAll('.photo-drop-zone');
        
        // Parcourt chaque zone d'upload et configure les événements
        dropZones.forEach(dropZone => {
            // Récupère les éléments nécessaires dans cette zone
            const fileInput = dropZone.querySelector('input[type="file"]'); // Input de type file
            const previewContainer = dropZone.querySelector('.photo-preview-container'); // Conteneur des miniatures
            const dropText = dropZone.querySelector('.drop-zone-text'); // Texte d'indication
            
            // Si les éléments essentiels n'existent pas, passer à la zone suivante
            if (!fileInput || !previewContainer) return;

            /**
             * Empêche les comportements par défaut du navigateur pour les événements de drag
             * Important : sans cela, le navigateur pourrait ouvrir le fichier au lieu de le déposer
             */
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                // Applique preventDefaults sur la zone de dépôt
                dropZone.addEventListener(eventName, preventDefaults, false);
                // Applique aussi sur le body pour éviter les comportements indésirables
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            /**
             * Ajoute un effet visuel (highlighting) quand on survole la zone avec un fichier
             * Améliore le feedback utilisateur
             */
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    // Ajoute la classe CSS qui change l'apparence de la zone
                    dropZone.classList.add('drop-zone-active');
                }, false);
            });

            /**
             * Retire l'effet visuel quand on sort de la zone ou qu'on dépose le fichier
             */
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    // Retire la classe CSS pour revenir à l'apparence normale
                    dropZone.classList.remove('drop-zone-active');
                }, false);
            });

            /**
             * Gère le dépôt (drop) des fichiers dans la zone
             * Récupère les fichiers depuis l'événement dataTransfer
             */
            dropZone.addEventListener('drop', (e) => {
                // dataTransfer contient les données du drag & drop
                const dt = e.dataTransfer;
                // Récupère la liste des fichiers déposés
                const files = dt.files;
                // Traite ces fichiers
                handleFiles(files, fileInput, previewContainer, dropText, e);
            }, false);

            /**
             * Gère la sélection de fichiers via le clic sur le bouton "Parcourir"
             * Alternative au drag & drop pour les utilisateurs qui préfèrent cliquer
             */
            fileInput.addEventListener('change', (e) => {
                // Récupère les fichiers sélectionnés dans l'input
                const files = e.target.files;
                // Traite ces fichiers
                handleFiles(files, fileInput, previewContainer, dropText, e);
            });

            /**
             * Permet de cliquer sur la zone de dépôt pour ouvrir le sélecteur de fichiers
             * Améliore l'UX en rendant toute la zone cliquable
             */
            dropZone.addEventListener('click', (e) => {
                // Vérifie qu'on a cliqué sur la zone ou le texte (pas sur les miniatures)
                if (e.target === dropZone || e.target === dropText) {
                    // Déclenche le clic sur l'input file (ouvre le sélecteur)
                    fileInput.click();
                }
            });
        });
    }

    /**
     * Empêche les comportements par défaut des événements
     * Nécessaire pour que le drag & drop fonctionne correctement
     * @param {Event} e - L'événement à bloquer
     */
    function preventDefaults(e) {
        e.preventDefault(); // Empêche l'action par défaut
        e.stopPropagation(); // Empêche la propagation de l'événement
    }

    // Handle files (from drop or file input)
    function handleFiles(files, fileInput, previewContainer, dropText, event) {
        // Convert FileList to Array
        const filesArray = Array.from(files);
        
        // Filter only image files
        const imageFiles = filesArray.filter(file => file.type.startsWith('image/'));
        
        if (imageFiles.length === 0) {
            alert('Veuillez sélectionner uniquement des fichiers image.');
            return;
        }

        // Get existing files from the input
        // If the event is a 'change' event (from file input click), clear existing files to avoid duplication.
        // Otherwise (for drag and drop), combine with existing files.
        const existingFiles = (event && event.type === 'change') ? [] : Array.from(fileInput.files);
        
        // Combine existing files with new image files
        const combinedFiles = [...existingFiles, ...imageFiles];

        // Update the file input with the combined files
        const dataTransfer = new DataTransfer();
        combinedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;

        // Clear previous previews and re-render all combined files
        previewContainer.innerHTML = '';

        // Hide drop text when files are selected
        if (dropText && combinedFiles.length > 0) {
            dropText.style.display = 'none';
        }

        // Generate preview for each combined file
        combinedFiles.forEach((file, index) => {
            previewFile(file, previewContainer, index, fileInput, dropText);
        });
    }

    /**
     * Génère une prévisualisation (miniature) pour un fichier image
     * Utilise FileReader pour lire le fichier et créer une URL de données (data URL)
     * 
     * @param {File} file - Le fichier image à prévisualiser
     * @param {HTMLElement} previewContainer - Conteneur où ajouter la miniature
     * @param {number} index - Index du fichier dans la liste (pour le bouton de suppression)
     * @param {HTMLInputElement} fileInput - L'input file (pour la suppression)
     * @param {HTMLElement} dropText - Le texte d'indication (pour le réafficher si besoin)
     */
    function previewFile(file, previewContainer, index, fileInput, dropText) {
        // FileReader permet de lire le contenu des fichiers côté client
        const reader = new FileReader();
        
        /**
         * Callback appelé quand le fichier a été lu avec succès
         * e.target.result contient une data URL (base64) de l'image
         */
        reader.onload = function(e) {
            // Crée le conteneur de la miniature
            const previewItem = document.createElement('div');
            previewItem.className = 'photo-preview-item';
            previewItem.dataset.index = index; // Stocke l'index pour la suppression
            
            // Crée l'élément image
            const img = document.createElement('img');
            img.src = e.target.result; // Data URL de l'image
            img.alt = file.name; // Nom du fichier comme texte alternatif
            
            // Crée le bouton de suppression (X)
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button'; // Important : type button pour ne pas soumettre le formulaire
            removeBtn.className = 'photo-remove-btn';
            removeBtn.innerHTML = '&times;'; // Symbole × (multiplication/croix)
            removeBtn.title = 'Supprimer cette photo'; // Tooltip au survol
            
            // Écouteur pour supprimer cette photo au clic sur le bouton
            removeBtn.addEventListener('click', (event) => {
                event.stopPropagation(); // Empêche le clic de se propager à la zone de dépôt
                removePhoto(index, previewContainer, fileInput, dropText);
            });
            
            // Affiche le nom du fichier sous la miniature
            const fileName = document.createElement('div');
            fileName.className = 'photo-file-name';
            fileName.textContent = file.name;
            
            // Assemble tous les éléments de la miniature
            previewItem.appendChild(img); // Image
            previewItem.appendChild(removeBtn); // Bouton X
            previewItem.appendChild(fileName); // Nom du fichier
            
            // Ajoute la miniature complète au conteneur
            previewContainer.appendChild(previewItem);
        };
        
        // Lance la lecture du fichier en tant que data URL (base64)
        // Déclenche le callback onload ci-dessus quand c'est terminé
        reader.readAsDataURL(file);
    }

    /**
     * Supprime une photo de la sélection
     * Met à jour l'input file, retire la miniature et réindexe les éléments restants
     * 
     * @param {number} indexToRemove - Index de la photo à supprimer
     * @param {HTMLElement} previewContainer - Conteneur des miniatures
     * @param {HTMLInputElement} fileInput - L'input file à mettre à jour
     * @param {HTMLElement} dropText - Le texte d'indication à réafficher si besoin
     */
    function removePhoto(indexToRemove, previewContainer, fileInput, dropText) {
        /**
         * Reconstruit la FileList sans le fichier à supprimer
         * Nécessite l'utilisation de DataTransfer car FileList est en lecture seule
         */
        const dataTransfer = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        // Ajoute tous les fichiers SAUF celui à supprimer
        files.forEach((file, index) => {
            if (index !== indexToRemove) {
                dataTransfer.items.add(file);
            }
        });
        
        // Met à jour l'input avec la nouvelle liste de fichiers
        fileInput.files = dataTransfer.files;
        
        // Supprime la miniature du DOM
        const previewItem = previewContainer.querySelector(`[data-index="${indexToRemove}"]`);
        if (previewItem) {
            previewItem.remove();
        }

        /**
         * Réindexe tous les éléments restants
         * Nécessaire car les index doivent correspondre aux positions dans fileInput.files
         */
        const remainingItems = previewContainer.querySelectorAll('.photo-preview-item');
        remainingItems.forEach((item, newIndex) => {
            item.dataset.index = newIndex;
        });

        /**
         * Si plus aucune photo n'est sélectionnée, réaffiche le texte d'indication
         * "Glissez vos photos ici ou cliquez pour parcourir"
         */
        if (fileInput.files.length === 0 && dropText) {
            dropText.style.display = 'block';
        }
    }

    /**
     * Point d'entrée principal - Initialise le système quand le DOM est prêt
     * Vérifie si le DOM est déjà chargé ou s'il faut attendre l'événement DOMContentLoaded
     */
    if (document.readyState === 'loading') {
        // Le DOM est en cours de chargement, attendre qu'il soit prêt
        document.addEventListener('DOMContentLoaded', initPhotoUpload);
    } else {
        // Le DOM est déjà chargé, initialiser immédiatement
        initPhotoUpload();
    }
})(); // IIFE (Immediately Invoked Function Expression) pour éviter la pollution du scope global

