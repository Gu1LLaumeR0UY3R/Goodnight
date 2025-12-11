<?php
// Récupérer les messages de session
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
if ($success) unset($_SESSION['success']);
if ($error) unset($_SESSION['error']);
?>

<div class="admin-page-header">
    <h2>Gestion des Cadres de Profil</h2>
    <a href="/admin/cadres/create" class="btn btn-primary">
        <span>➕</span> Ajouter un nouveau cadre
    </a>
</div>

<?php if ($success): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="admin-content-section">
    <h3>📋 Cadres Actifs</h3>
    
    <?php if (empty($cadres)): ?>
        <p class="no-data">Aucun cadre disponible.</p>
    <?php else: ?>
        <div class="cadres-grid">
            <?php foreach ($cadres as $cadre): 
                $isDefault = $cadre['chemin_fichier'] === null;
            ?>
                <div class="cadre-card">
                    <!-- Aperçu du cadre -->
                    <div class="cadre-preview">
                        <?php if (!$isDefault && $cadre['chemin_fichier']): ?>
                            <img src="<?php echo htmlspecialchars($cadre['chemin_fichier']); ?>" alt="<?php echo htmlspecialchars($cadre['nom']); ?>" class="frame-image">
                        <?php else: ?>
                            <div class="frame-default" style="font-size: 2.5rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                ⭐
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Infos du cadre -->
                    <div class="cadre-info">
                        <h4><?php echo htmlspecialchars($cadre['nom']); ?></h4>
                        <?php if ($cadre['description']): ?>
                            <p class="cadre-description"><?php echo htmlspecialchars($cadre['description']); ?></p>
                        <?php endif; ?>

                        <div class="cadre-meta">
                            <small>Créé le: <?php echo date('d/m/Y', strtotime($cadre['date_creation'])); ?></small>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="cadre-actions">
                        <?php if ($cadre['chemin_fichier'] !== null): ?>
                            <button class="btn btn-small btn-danger" onclick="deleteCadre(<?php echo $cadre['id']; ?>, '<?php echo htmlspecialchars($cadre['nom']); ?>')">
                                🗑️ Supprimer
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.cadres-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.cadre-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.cadre-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.cadre-preview {
    width: 100%;
    height: 150px;
    background: #f9f9f9;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    overflow: hidden;
}

.cadre-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

.frame-default {
    font-size: 3rem;
}

.cadre-info h4 {
    margin: 10px 0 5px 0;
    font-size: 1rem;
}

.cadre-description {
    font-size: 0.85rem;
    color: #666;
    margin: 8px 0;
}

.cadre-meta {
    font-size: 0.8rem;
    color: #999;
    margin-top: 8px;
}

.cadre-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    flex-wrap: wrap;
}

.btn-small {
    padding: 6px 12px;
    font-size: 0.85rem;
}

#moderateModal textarea {
    width: 100%;
    min-height: 100px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
}

.alert {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
</style>

<script>
function deleteCadre(cadreId, cadreName) {
    if (!confirm(`Êtes-vous sûr de vouloir supprimer le cadre "${cadreName}" ? Cette action est irréversible.`)) {
        return;
    }

    fetch('/cadre/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id: cadreId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Cadre supprimé avec succès.');
            location.reload();
        } else {
            alert('Erreur: ' + (data.error || 'Impossible de supprimer le cadre'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la suppression.');
    });
}
</script>
