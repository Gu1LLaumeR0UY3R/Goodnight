/**
 * GlobeNight - Registration Form Handler
 * This script handles the dynamic display of fields based on the selected type of person (physique/morale).
 */

$(function() {
    // Function to toggle fields visibility based on type_personne
    function togglePersonTypeFields() {
        if ($('#morale').is(':checked')) {
            $('#form-morale').show();
            $('#form-physique').hide();
            $('#date_naissance').prop('required', false);
            $('#raison_sociale').prop('required', true);
            $('#siret').prop('required', true);
        } else {
            $('#form-morale').hide();
            $('#form-physique').show();
            $('#date_naissance').prop('required', true);
            $('#raison_sociale').prop('required', false);
            $('#siret').prop('required', false);
        }
    }

    // Initial call to set the correct state on page load
    togglePersonTypeFields();

    // Attach change event listener to type_personne radio buttons
    $('input[name="type_personne"]').on('change', function() {
        togglePersonTypeFields();
    });
});

