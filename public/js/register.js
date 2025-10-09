/**
 * GlobeNight - Registration Form Handler
 * This script handles the dynamic form switching between physical and legal persons
 */

$(function() {
    // Toggle form fields based on account type (physical or legal person)
    $('input[name="type_personne"]').on('change', function() {
        if ($(this).val() === 'morale') {
            $('#form-physique').hide();
            $('#form-morale').show();
        } else {
            $('#form-physique').show();
            $('#form-morale').hide();
        }
    });
});
