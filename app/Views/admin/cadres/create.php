<?php
// Récupérer les messages de session
$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
if ($success) unset($_SESSION['success']);
if ($error) unset($_SESSION['error']);
?>

<div class="admin-page-header">
    <h2>Ajouter un nouveau cadre</h2>
    <a href="/admin/cadres" class="btn btn-secondary">← Retour aux cadres</a>
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

<div class="admin-content-section form-section">
    <h3>📝 Formulaire de création</h3>

    <form action="/cadre/store" method="POST" enctype="multipart/form-data" class="admin-form">
        <!-- Nom du cadre -->
        <div class="form-group">
            <label for="nom">Nom du cadre *</label>
            <input type="text" id="nom" name="nom" required placeholder="Ex: Or Doré, Cadre Mystique, etc.">
            <small>Doit être unique dans le système</small>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="4" placeholder="Décrivez le cadre, son style, ses caractéristiques..." required></textarea>
            <small>Courte description affichée dans le profil utilisateur</small>
        </div>

        <!-- Image du cadre -->
        <div class="form-group">
            <label for="image">Image du cadre (PNG) *</label>
            <div class="file-upload-area">
                <input type="file" id="image" name="image" accept="image/png" required onchange="previewImage()">
                <div class="upload-hint">
                    📁 Cliquez pour sélectionner une image PNG
                    <br>
                    <small>Format: PNG • Max: 200KB • Recommandé: 200x200px ou 300x300px</small>
                </div>
            </div>
            <div id="imagePreview" class="image-preview" style="display: none;">
                <img id="previewImg" src="" alt="Aperçu">
                <button type="button" onclick="clearImage()" class="btn btn-small btn-danger">Changer l'image</button>
            </div>
        </div>

        <!-- Avertissement -->
        <div class="warning-box">
            <h4>⚠️ Important</h4>
            <ul>
                <li>L'image doit être au format PNG uniquement</li>
                <li>Taille maximale: 200KB</li>
                <li>Le cadre doit respecter les politiques de modération</li>
                <li>Les cadres inappropriés seront modérés et désactivés</li>
            </ul>
        </div>

        <!-- Boutons d'action -->
        <div class="form-actions">
            <a href="/admin/cadres" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">✓ Créer le cadre</button>
        </div>
    </form>
</div>

<style>
.form-section {
    max-width: 500px;
}

.admin-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #333;
}

.form-group input[type="text"],
.form-group textarea {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 1rem;
}

.form-group input[type="text"]:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group small {
    color: #999;
    font-size: 0.85rem;
}

.file-upload-area {
    position: relative;
    border: 2px dashed #ddd;
    border-radius: 4px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-upload-area:hover {
    border-color: #667eea;
    background-color: #f8faff;
}

.file-upload-area input[type="file"] {
    display: none;
}

.upload-hint {
    color: #666;
    pointer-events: none;
}

.image-preview {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
    text-align: center;
    margin-top: 12px;
}

.image-preview img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 4px;
    margin-bottom: 12px;
}

.warning-box {
    background-color: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 4px;
    padding: 15px;
    color: #856404;
}

.warning-box h4 {
    margin-top: 0;
    color: #856404;
}

.warning-box ul {
    margin: 10px 0 0 20px;
    padding: 0;
}

.warning-box li {
    margin: 5px 0;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 20px;
    border-top: 1px solid #eee;
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
function previewImage() {
    const input = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function clearImage() {
    document.getElementById('image').value = '';
    document.getElementById('imagePreview').style.display = 'none';
}

// Rendre toute la zone cliquable
document.querySelector('.file-upload-area').addEventListener('click', function() {
    document.getElementById('image').click();
});
</script>
