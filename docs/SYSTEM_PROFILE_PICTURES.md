# Documentation - Système de Photo de Profil

## Vue d'ensemble

Le système de photo de profil permet aux utilisateurs de télécharger, modifier et supprimer leur photo de profil.

## Structure

### Base de données
- **Table**: `locataire`
- **Champ**: `pfp_loca` (VARCHAR 255, NULL)
  - Stocke le chemin relatif vers la photo: `/pfp/user_{id}_{timestamp}.{ext}`

### Fichiers

#### Contrôleur
- **Fichier**: `app/Controllers/ProfileController.php`
- **Actions**:
  - `index()`: Affiche la page de profil
  - `uploadProfilePicture()`: Gère l'upload de photo
  - `deleteProfilePicture()`: Supprime la photo de profil

#### Modèle
- **Fichier**: `app/Models/UserModel.php`
- **Méthode ajoutée**: `updateProfilePicture($userId, $pfpPath)`

#### Vue
- **Fichier**: `app/Views/profile/index.php`
- Affiche la photo actuelle ou un placeholder avec l'initiale
- Permet l'upload et la suppression via AJAX

#### Stockage
- **Dossier**: `public/pfp/`
- Les photos sont stockées avec le format: `user_{id}_{timestamp}.{extension}`

### Routes

```php
"profile" => ["controller" => "ProfileController", "action" => "index"],
"profile/uploadProfilePicture" => ["controller" => "ProfileController", "action" => "uploadProfilePicture"],
"profile/deleteProfilePicture" => ["controller" => "ProfileController", "action" => "deleteProfilePicture"],
```

### Navigation
- Lien "👤 Mon Profil" ajouté dans la navbar pour les utilisateurs connectés

## Utilisation

### Pour l'utilisateur

1. **Accéder au profil**: Cliquer sur "👤 Mon Profil" dans la navbar
2. **Ajouter/Modifier une photo**: 
   - Cliquer sur "📷 Changer la photo"
   - Sélectionner une image (JPG, PNG, GIF, WEBP)
   - La photo s'upload automatiquement
3. **Supprimer une photo**: Cliquer sur "🗑️ Supprimer la photo"

### Contraintes
- Formats acceptés: JPEG, JPG, PNG, GIF, WEBP
- Taille maximale: 5MB
- Validation du MIME type côté serveur
- Une seule photo par utilisateur (la nouvelle remplace l'ancienne)

### Sécurité
- Authentification obligatoire
- Validation du type MIME
- Limitation de taille
- Noms de fichiers uniques basés sur l'ID utilisateur et timestamp
- Suppression de l'ancienne photo lors de l'upload d'une nouvelle

## Migration SQL

Pour ajouter la colonne à la base de données existante:

```sql
ALTER TABLE `locataire` 
ADD COLUMN `pfp_loca` VARCHAR(255) NULL DEFAULT NULL AFTER `id_commune`;
```

Fichier de migration: `migrations/add_pfp_loca.sql`

## Affichage de la photo

La photo peut être affichée dans n'importe quelle vue avec:

```php
<?php if (!empty($user['pfp_loca'])): ?>
    <img src="<?php echo htmlspecialchars($user['pfp_loca']); ?>" alt="Photo de profil">
<?php else: ?>
    <div class="placeholder">
        <?php echo strtoupper(substr($user['prenom_locataire'], 0, 1)); ?>
    </div>
<?php endif; ?>
```

## Extensions futures possibles

- Recadrage d'image côté client
- Redimensionnement automatique côté serveur
- Support de plusieurs formats d'image (miniature, moyenne, grande)
- Intégration avec un service de CDN
- Historique des photos de profil
