/**
 * GlobeNight - Autocomplétion pour les Communes
 * 
 * Ce script gère la fonctionnalité d'autocomplétion pour les champs de recherche de communes.
 * Il utilise jQuery UI Autocomplete pour offrir des suggestions en temps réel pendant la saisie.
 * 
 * Deux champs sont gérés :
 * 1. Le champ de recherche de la page d'accueil (recherche de biens)
 * 2. Le champ de commune lors de l'inscription
 */

$(function() {
    /**
     * Configuration de l'autocomplétion pour le champ de recherche de la page d'accueil
     * Permet de rechercher des biens par commune
     */
    $("#commune_search").autocomplete({
        /**
         * Source des suggestions - effectue une requête AJAX vers le serveur
         * @param {Object} request - Contient request.term (le texte saisi par l'utilisateur)
         * @param {Function} response - Fonction de callback pour renvoyer les résultats
         */
        source: function(request, response) {
            // Requête AJAX vers le contrôleur HomeController
            $.ajax({
                url: "/home/autocompleteCommunes", // Endpoint qui retourne les communes correspondantes
                dataType: "json", // On attend une réponse au format JSON
                data: {
                    term: request.term // Envoie le texte saisi par l'utilisateur
                },
                success: function(data) {
                    // Transmet les résultats à jQuery UI pour affichage des suggestions
                    response(data);
                }
            });
        },
        minLength: 2, // Nombre minimum de caractères avant de déclencher l'autocomplétion
        
        /**
         * Callback quand l'utilisateur navigue dans les suggestions avec les flèches du clavier
         * @param {Event} event - L'événement de focus
         * @param {Object} ui - Contient ui.item (l'élément survolé avec label et value)
         */
        focus: function(event, ui) {
            // Affiche le nom de la commune (label) au lieu de l'ID (value)
            $("#commune_search").val(ui.item.label);
            return false; // Empêche le comportement par défaut
        },
        
        /**
         * Callback quand l'utilisateur sélectionne une suggestion (clic ou Entrée)
         * @param {Event} event - L'événement de sélection
         * @param {Object} ui - Contient ui.item (l'élément sélectionné avec label et value)
         */
        select: function(event, ui) {
            // Utilise le nom de la commune (label) au lieu de l'ID (value)
            // Cela permet une recherche par nom de commune lisible
            $("#commune_search").val(ui.item.label);
            return false; // Empêche le comportement par défaut
        }
    });

    /**
     * Configuration de l'autocomplétion pour le champ de commune lors de l'inscription
     * Stocke à la fois le nom de la commune (visible) et son ID (dans un champ caché)
     */
    $("#commune_search_register").autocomplete({
        /**
         * Source des suggestions - même endpoint que pour la recherche
         * @param {Object} request - Contient request.term (le texte saisi)
         * @param {Function} response - Fonction de callback pour les résultats
         */
        source: function(request, response) {
            $.ajax({
                url: "/home/autocompleteCommunes",
                dataType: "json",
                data: {
                    term: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 2, // Commence à suggérer après 2 caractères
        
        /**
         * Callback de sélection - stocke le nom ET l'ID de la commune
         * @param {Event} event - L'événement de sélection
         * @param {Object} ui - Contient ui.item.label (nom) et ui.item.value (ID)
         */
        select: function(event, ui) {
            // Affiche le nom de la commune dans le champ visible
            $("#commune_search_register").val(ui.item.label);
            // Stocke l'ID de la commune dans un champ caché pour l'envoi du formulaire
            $("#id_commune").val(ui.item.value);
            return false; // Empêche le comportement par défaut
        }
    });
});
