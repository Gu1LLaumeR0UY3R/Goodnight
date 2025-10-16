/**
 * GlobeNight - Registration Form Handler
 * This script handles the dynamic display of the SIRET field based on the selected role.
 */

$(function() {
    // Function to toggle SIRET field visibility
    function toggleSiretField() {
        if ($('#role_proprietaire').is(':checked')) {
            $('#siret_field').show();
        } else {
            $('#siret_field').hide();
        }
    }

    // Initial call to set the correct state on page load
    toggleSiretField();

    // Attach change event listener to role radio buttons
    $('input[name="role_choice"]').on('change', function() {
        toggleSiretField();
    });
});
