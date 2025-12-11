# 🎨 DOCUMENTATION SYSTÈME EASTER EGGS

## Vue d'ensemble

Le système Easter Eggs permet de gérer des cadres de profil PNG que les utilisateurs peuvent débloquer et appliquer à leurs avatars. Ce système est entièrement administrable depuis l'interface d'administration.

---

## 📁 Structure des fichiers

### SQL
- **`sql_updates/add_easter_eggs_system.sql`** : Script de création de la base de données
  - Table `cadres_profil` : Stocke les cadres disponibles
  - Modification de la table `locataire` : Ajoute les colonnes `id_cadre_actif` et `frames_unlocked`

### Contrôleurs
- **`app/Controllers/EasterEggController.php`** : Gestion des Easter Eggs
  - `index()` : Affiche la page de gestion
  - `create()` : Affiche le formulaire d'ajout
  - `store()` : Enregistre un nouveau cadre
  - `delete()` : Supprime un cadre (API JSON)

### Modèles
- **`app/Models/CadreModel.php`** : Gestion des cadres en base de données
  - `getAll()` : Récupère tous les cadres
  - `getById($id)` : Récupère un cadre par ID
  - `create($data)` : Crée un nouveau cadre
  - `delete($id)` : Supprime un cadre (+ fichier physique)

### Vues
- **`app/Views/admin/easter_eggs/index.php`** : Page principale de gestion
- **`app/Views/admin/easter_eggs/create.php`** : Formulaire d'ajout de cadre

### Assets
- **`public/css/easter-eggs.css`** : Styles dédiés au système Easter Eggs
- **`public/js/easter-eggs.js`** : Interactions JavaScript (onglets, suppression, upload)
- **`public/cadre/frames/`** : Dossier de stockage des fichiers PNG

---

## 🗄️ Structure de la base de données

### Table `cadres_profil`

```sql
CREATE TABLE cadres_profil (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  description TEXT,
  chemin_fichier VARCHAR(255),
  date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

### Table `locataire` (modifications)

```sql
ALTER TABLE locataire 
  ADD COLUMN id_cadre_actif INT UNSIGNED DEFAULT NULL,
  ADD COLUMN frames_unlocked BOOLEAN DEFAULT 0;

ALTER TABLE locataire 
  ADD CONSTRAINT fk_locataire_cadre 
  FOREIGN KEY (id_cadre_actif) 
  REFERENCES cadres_profil(id) 
  ON DELETE SET NULL;
```

**Colonnes ajoutées :**
- `id_cadre_actif` : ID du cadre actuellement équipé par l'utilisateur
- `frames_unlocked` : Booléen indiquant si l'utilisateur a découvert l'Easter Egg

---

## 🚀 Installation

### 1. Base de données

Exécutez le script SQL :

```bash
mysql -u root -p goodnight < sql_updates/add_easter_eggs_system.sql
```

Ou via phpMyAdmin : Importez le fichier `add_easter_eggs_system.sql`

### 2. Permissions des dossiers

Assurez-vous que le dossier de stockage existe et est accessible en écriture :

```bash
mkdir -p public/cadre/frames
chmod 755 public/cadre/frames
```

### 3. Routing

Ajoutez les routes dans votre fichier de routing (généralement `public/index.php` ou un fichier dédié) :

```php
// Easter Eggs Admin
'/admin/easter-eggs' => ['controller' => 'EasterEggController', 'action' => 'index'],
'/admin/cadres/create' => ['controller' => 'EasterEggController', 'action' => 'create'],
'/admin/cadres/store' => ['controller' => 'EasterEggController', 'action' => 'store'],
'/cadre/delete' => ['controller' => 'EasterEggController', 'action' => 'delete'],
```

---

## 💻 Utilisation

### Interface d'administration

#### Accès
Connectez-vous en tant qu'administrateur et accédez à :
```
https://votre-site.com/admin/easter-eggs
```

#### Fonctionnalités

**1. Visualiser les cadres**
- Liste de tous les cadres avec aperçu
- Informations : nom, description, chemin fichier, date de création

**2. Ajouter un cadre**
- Cliquez sur "➕ Ajouter un cadre"
- Remplissez le formulaire :
  - **Nom** (requis) : Nom affiché du cadre
  - **Description** (optionnel) : Description du cadre
  - **Fichier PNG** (requis) : Format PNG uniquement, max 5MB
- Cliquez sur "✓ Créer le cadre"

**3. Supprimer un cadre**
- Cliquez sur "🗑️ Supprimer" sur la carte du cadre
- Confirmez la suppression
- Le fichier PNG est automatiquement supprimé du serveur

### Onglets

**🎨 Cadres de profil** : Gestion des cadres PNG  
**🔮 Autres easter eggs** : Documentation des autres Easter Eggs (futur)

---

## 🎨 Spécifications des cadres PNG

### Format recommandé
- **Type** : PNG avec transparence (alpha channel)
- **Dimensions** : 512x512 pixels
- **Taille** : Maximum 5MB
- **Centre transparent** : Le centre doit être transparent pour laisser voir l'avatar

### Exemple de structure

```
┌─────────────────────┐
│   ╔═══════════╗    │
│   ║           ║    │ ← Bordure du cadre (opaque)
│   ║  [AVATAR] ║    │
│   ║           ║    │ ← Centre (transparent)
│   ╚═══════════╝    │
└─────────────────────┘
```

### Conseils de création
- Utilisez Photoshop, GIMP ou Figma
- Créez un masque circulaire au centre
- Ajoutez des effets : dégradés, textures, ombres
- Exportez en PNG-24 avec transparence

---

## 🔌 API

### Supprimer un cadre (DELETE)

**Endpoint** : `POST /cadre/delete`

**Headers** :
```
Content-Type: application/json
```

**Body** :
```json
{
  "id": 5
}
```

**Réponse succès** :
```json
{
  "success": true,
  "message": "Cadre supprimé avec succès"
}
```

**Réponse erreur** :
```json
{
  "success": false,
  "error": "Cadre introuvable"
}
```

---

## 🎯 Intégration utilisateur (À implémenter)

### Débloquer l'Easter Egg

Quand un utilisateur trouve l'Easter Egg, mettez à jour `frames_unlocked` :

```php
// Dans le contrôleur approprié
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("UPDATE locataire SET frames_unlocked = 1 WHERE id_locataire = ?");
$stmt->execute([$userId]);
```

### Afficher les cadres disponibles

```php
if ($user['frames_unlocked']) {
    // Récupérer tous les cadres
    $cadres = $cadreModel->getAll();
    // Afficher dans la vue profil
}
```

### Appliquer un cadre

```php
$userId = $_SESSION['user_id'];
$cadreId = $_POST['cadre_id'];

// Vérifier que l'utilisateur a débloqué les cadres
$user = $userModel->getById($userId);
if ($user['frames_unlocked']) {
    $stmt = $db->prepare("UPDATE locataire SET id_cadre_actif = ? WHERE id_locataire = ?");
    $stmt->execute([$cadreId, $userId]);
}
```

### Afficher l'avatar avec cadre

```php
<?php
$user = $userModel->getById($userId);
$avatar = $user['photo_profil'] ?? '/pfp/default.png';
$cadre = null;

if ($user['id_cadre_actif']) {
    $cadre = $cadreModel->getById($user['id_cadre_actif']);
}
?>

<div class="avatar-container">
    <img src="<?= $avatar ?>" alt="Avatar" class="avatar">
    <?php if ($cadre): ?>
        <img src="<?= $cadre['chemin_fichier'] ?>" alt="Cadre" class="avatar-frame">
    <?php endif; ?>
</div>

<style>
.avatar-container {
    position: relative;
    width: 150px;
    height: 150px;
}

.avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-frame {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}
</style>
```

---

## 🐛 Dépannage

### Le fichier ne s'upload pas

**Problème** : Erreur lors de l'upload  
**Solution** : Vérifiez les permissions du dossier `public/cadre/frames/`
```bash
chmod -R 755 public/cadre/frames/
```

### Les cadres n'apparaissent pas

**Problème** : Table `cadres_profil` introuvable  
**Solution** : Exécutez le script SQL d'installation

### Erreur 404 sur les routes

**Problème** : Routes non configurées  
**Solution** : Ajoutez les routes dans votre système de routing

### Le cadre ne s'affiche pas sur l'avatar

**Problème** : Chemin du fichier incorrect  
**Solution** : Vérifiez que le chemin dans la BDD correspond au fichier physique

---

## 📊 Données d'exemple

8 cadres sont créés automatiquement lors de l'installation :

1. **Cadre Bronze** - Simple et élégant
2. **Cadre Argent** - Pour les collectionneurs
3. **Cadre Or** - Prestigieux
4. **Cadre Arc-en-ciel** - Magique et coloré
5. **Cadre Néon** - Style futuriste
6. **Cadre Vintage** - Rétro et nostalgique
7. **Cadre Cristal** - Transparent et élégant
8. **Cadre Feu** - Enflammé et passionné

---

## 🔒 Sécurité

### Vérifications implémentées

✅ Authentification admin requise pour toutes les opérations  
✅ Validation du type MIME (PNG uniquement)  
✅ Limitation de taille (5MB max)  
✅ Noms de fichiers uniques (évite les collisions)  
✅ Suppression du fichier physique lors de la suppression en BDD  
✅ Protection contre les injections SQL (requêtes préparées)  
✅ Échappement HTML dans les vues  

---

## 📝 TODO / Améliorations futures

- [ ] Système de rareté des cadres (commun, rare, épique, légendaire)
- [ ] Système de succès/achievements pour débloquer plus de cadres
- [ ] Marketplace pour échanger des cadres entre utilisateurs
- [ ] Cadres animés (GIF ou APNG)
- [ ] Prévisualisation en temps réel sur l'avatar
- [ ] Statistiques d'utilisation des cadres
- [ ] Cadres saisonniers (Noël, Halloween, etc.)
- [ ] API publique pour les développeurs tiers

---

## 📞 Support

Pour toute question ou problème :
- Consultez cette documentation
- Vérifiez les logs d'erreur PHP
- Inspectez la console JavaScript du navigateur
- Contactez l'équipe de développement

---

**Dernière mise à jour** : 11 décembre 2025  
**Version** : 1.0.0  
**Auteur** : GitHub Copilot
