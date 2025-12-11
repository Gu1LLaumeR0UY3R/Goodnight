<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un cadre - Easter Eggs</title>
    <link rel="stylesheet" href="/css/easter-eggs.css">
    <link rel="stylesheet" href="/css/admin-content.css">
</head>
<body>
<?php
// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: /');
    exit;
}
?>

<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h2>➕ Ajouter un nouveau cadre</h2>
            <p class="subtitle">Créez un nouveau cadre de profil pour les utilisateurs.</p>
        </div>
    </div>

    <div class="admin-content-section">
        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <strong>Erreur :</strong> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <strong>Succès :</strong> <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/admin/cadres/store" enctype="multipart/form-data" id="addFrameForm">
                <div class="form-group">
                    <label for="nom">Nom du cadre <span style="color: #ff5e62;">*</span></label>
                    <input 
                        type="text" 
                        id="nom" 
                        name="nom" 
                        placeholder="Ex: Cadre Bronze, Cadre Arc-en-ciel..."
                        required 
                        maxlength="100"
                        value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>"
                    >
                    <span class="help-text">Le nom qui sera affiché aux utilisateurs</span>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        placeholder="Décrivez ce cadre (optionnel)..."
                        maxlength="500"
                    ><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    <span class="help-text">Une courte description du cadre (optionnel)</span>
                </div>

                <div class="form-group">
                    <label for="fichier">Fichier PNG du cadre <span style="color: #ff5e62;">*</span></label>
                    <input 
                        type="file" 
                        id="fichier" 
                        name="fichier" 
                        accept=".png,image/png"
                        required
                    >
                    <span class="help-text">
                        Format PNG uniquement. Taille recommandée : 512x512px avec transparence.<br>
                        Le cadre doit avoir un centre transparent pour laisser voir l'avatar.
                    </span>
                    
                    <div class="file-preview" id="filePreview">
                        <div class="file-preview-name" id="fileName"></div>
                        <img id="previewImage" alt="Aperçu du cadre">
                    </div>
                </div>

                <div class="form-actions">
                    <a href="/admin/easter-eggs" class="btn btn-ghost">
                        ← Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        ✓ Créer le cadre
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.alert {
    padding: 14px 18px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid;
}

.alert-danger {
    background: rgba(255, 94, 98, 0.15);
    border-color: rgba(255, 94, 98, 0.4);
    color: #ffb3a1;
}

.alert-success {
    background: rgba(76, 175, 80, 0.15);
    border-color: rgba(76, 175, 80, 0.4);
    color: #a8e6a1;
}

.alert strong {
    font-weight: 700;
}
</style>

<script>
// Preview de l'image uploadée
document.getElementById('fichier').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    
    if (file) {
        // Vérifier que c'est bien un PNG
        if (!file.type.match('image/png')) {
            alert('Veuillez sélectionner un fichier PNG');
            e.target.value = '';
            preview.classList.remove('active');
            return;
        }
        
        // Vérifier la taille (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Le fichier est trop volumineux (max 5MB)');
            e.target.value = '';
            preview.classList.remove('active');
            return;
        }
        
        // Afficher le nom du fichier
        fileName.textContent = `Fichier : ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
        
        // Créer un aperçu
        const reader = new FileReader();
        reader.onload = function(event) {
            previewImage.src = event.target.result;
            preview.classList.add('active');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.remove('active');
    }
});

// Validation du formulaire
document.getElementById('addFrameForm').addEventListener('submit', function(e) {
    const nom = document.getElementById('nom').value.trim();
    const fichier = document.getElementById('fichier').files[0];
    
    if (!nom) {
        alert('Veuillez entrer un nom pour le cadre');
        e.preventDefault();
        return false;
    }
    
    if (!fichier) {
        alert('Veuillez sélectionner un fichier PNG');
        e.preventDefault();
        return false;
    }
    
    // Afficher un message de chargement
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '⏳ Création en cours...';
});
</script>

</body>
</html>
