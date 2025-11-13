/**
 * GlobeNight - Autocomplete for Communes
 * This script handles the autocomplete functionality for commune search fields
 */

$(function() {
    // Autocomplete for home page search
    $("#commune_search").autocomplete({
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
        minLength: 2,
        select: function(event, ui) {
            // When a commune is selected, use the name instead of the ID
            $("#commune_search").val(ui.item.label);
            return false;
        }
    });

    // Autocomplete for registration page
    $("#commune_search_register").autocomplete({
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
        minLength: 2,
        select: function(event, ui) {
            // When a commune is selected, store its value and code postal
            $("#commune_search_register").val(ui.item.label);
            $("#id_commune").val(ui.item.value);
            return false;
        }
    });
});
