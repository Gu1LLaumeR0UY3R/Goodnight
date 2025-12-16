<?php
/**
 * Template pour la section des prestations
 * À inclure dans add_bien.php et edit_bien.php
 */
?>

<fieldset class="form-section">
    <legend>Prestations et Équipements</legend>
    <div class="accordion-container">
        <div class="accordion-header" onclick="togglePrestations()">
            <div class="accordion-title">
                ✨ Gérer les prestations et équipements (<?php echo count($prestations ?? []); ?> disponibles)
            </div>
            <span class="accordion-icon" id="accordion-icon-prestations">▼</span>
        </div>
        <div class="accordion-content" id="accordion-content-prestations">
            <!-- Barre de recherche avec autocomplétion -->
            <div style="margin-bottom: 2rem; position: relative;">
                <label for="prestation-search" style="display: block; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-primary); font-size: 1rem;">🏠 Ajouter une prestation :</label>
                <div style="position: relative;">
                    <input type="text" 
                           id="prestation-search" 
                           placeholder="Tapez le nom d'une prestation (ex: piscine, wifi, climatisation...)" 
                           autocomplete="off"
                           style="width: 100%; padding: 1rem; border: 2px solid var(--border-color); border-radius: 12px; font-size: 1rem; font-family: inherit; background: var(--bg-primary); color: var(--text-primary); transition: all 0.3s;">
                    <div id="prestation-autocomplete" 
                         style="position: absolute; top: 100%; left: 0; right: 0; background: var(--bg-primary); border: 2px solid var(--border-color); border-top: none; border-radius: 0 0 12px 12px; max-height: 350px; overflow-y: auto; display: none; z-index: 1000; box-shadow: var(--shadow); margin-top: -2px;">
                    </div>
                </div>
            </div>

            <!-- Prestations sélectionnées -->
            <div id="prestations-selected">
                <!-- Les prestations sélectionnées s'ajouteront dynamiquement ici -->
            </div>

            <!-- Input hidden pour stocker les données finales -->
            <input type="hidden" id="prestations-data" name="prestations-data" value="{}">
        </div>
    </div>
</fieldset>

<!-- Scripts pour les prestations -->
<script>
    // Passer les données au script de prestations
    window.prestationsData = <?php echo json_encode($prestations ?? []); ?>;
    window.bienPrestations = <?php echo json_encode($bienPrestations ?? []); ?>;
</script>
