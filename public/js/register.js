/**
 * GlobeNight - Gestionnaire du Formulaire d'Inscription
 * 
 * Ce script gère l'affichage dynamique des champs du formulaire d'inscription
 * en fonction du type de personne sélectionné (physique ou morale).
 * 
 * Fonctionnement :
 * - Personne physique : affiche les champs nom, prénom, date de naissance
 * - Personne morale : affiche les champs raison sociale, SIRET
 */

$(function() {
    /**
     * Bascule l'affichage des champs selon le type de personne sélectionné
     * Gère également l'attribut 'required' pour la validation HTML5
     * 
     * Personne Morale :
     * - Affiche : raison_sociale, siret
     * - Masque : date_naissance
     * 
     * Personne Physique :
     * - Affiche : date_naissance
     * - Masque : raison_sociale, siret
     */
    function togglePersonTypeFields() {
        // Vérifie si le radio button "Personne Morale" est coché
        if ($('#morale').is(':checked')) {
            // Affiche le bloc de champs pour personne morale
            $('#form-morale').show();
            // Masque le bloc de champs pour personne physique
            $('#form-physique').hide();
            
            // Ajuste les attributs 'required' pour la validation du formulaire
            $('#date_naissance').prop('required', false); // Date de naissance non obligatoire
            $('#raison_sociale').prop('required', true);  // Raison sociale obligatoire
            $('#siret').prop('required', true);           // SIRET obligatoire
        } else {
            // Le radio button "Personne Physique" est coché (par défaut ou par choix)
            
            // Masque le bloc de champs pour personne morale
            $('#form-morale').hide();
            // Affiche le bloc de champs pour personne physique
            $('#form-physique').show();
            
            // Ajuste les attributs 'required' pour la validation du formulaire
            $('#date_naissance').prop('required', true);   // Date de naissance obligatoire
            $('#raison_sociale').prop('required', false);  // Raison sociale non obligatoire
            $('#siret').prop('required', false);           // SIRET non obligatoire
        }
    }

    /**
     * Appel initial au chargement de la page
     * Configure l'état correct des champs selon le choix par défaut
     * Important : garantit que l'affichage est cohérent dès le chargement
     */
    togglePersonTypeFields();

    /**
     * Écouteur d'événement sur les boutons radio du type de personne
     * Déclenche togglePersonTypeFields() à chaque changement de sélection
     * Permet de basculer dynamiquement entre personne physique et morale
     */
    $('input[name="type_personne"]').on('change', function() {
        togglePersonTypeFields();
    });
});

