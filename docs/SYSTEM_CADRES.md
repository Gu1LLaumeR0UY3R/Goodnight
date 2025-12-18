# 🎨 Système CRUD des Cadres de Profil

## Vue d'ensemble

Système simplifié de gestion des cadres de profil personnalisés. Les cadres peuvent être uploadés par l'admin, affichés dans le profil utilisateur et supprimés si nécessaire.

## 📊 Structure de la base de données

### Table `cadres`
```sql
CREATE TABLE cadres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE,
    chemin_fichier VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
```

**Champs :**
- `id` : Identifiant unique
- `nom` : Nom du cadre (unique)
- `chemin_fichier` : Chemin vers l'image PNG (ex: `/cadre/images/gold.png` ou `NULL` pour défaut)
- `description` : Courte description du cadre
- `date_creation` : Date de création
- `date_modification` : Date de dernière modification

## 🛠️ Fichiers créés/modifiés

### 1. **Migration SQL**
📄 `sql_updates/add_cadres_management_system.sql`
- Crée la table `cadres`
- Insère 9 cadres par défaut (dont le cadre par défaut sans image)

### 2. **Model**
📄 `app/Models/CadreModel.php`
- `getAll()` : Récupère tous les cadres
- `getById($id)` : Récupère un cadre par ID
- `getByPath($path)` : Récupère un cadre par son chemin
- `create($data)` : Créer un nouveau cadre
- `delete($id)` : Supprimer un cadre (inclut suppression du fichier)
- `pathExists($path)` : Vérifier si un chemin existe déjà

### 3. **Contrôleur**
📄 `app/Controllers/CadreController.php`

**Routes disponibles :**
- `GET /admin/cadres` : Liste tous les cadres
- `GET /admin/cadres/create` : Formulaire de création
- `POST /cadre/store` : Stocker un nouveau cadre
- `POST /cadre/delete` : Supprimer un cadre

**Validations :**
- ✅ PNG uniquement
- ✅ Taille max 200KB
- ✅ Nom unique
- ✅ Authentification admin requise

### 4. **Vues**
📄 `app/Views/admin/cadres/index.php` - Liste des cadres
📄 `app/Views/admin/cadres/create.php` - Formulaire de création

## 🔌 Intégration avec le système de profil

Les cadres se stockent dans la table `locataire` :
```sql
ALTER TABLE locataire ADD COLUMN cadre_profil VARCHAR(255) NULL;
ALTER TABLE locataire ADD COLUMN frames_unlocked BOOLEAN DEFAULT 0;
```

- `cadre_profil` : Chemin du PNG sélectionné par l'utilisateur
- `frames_unlocked` : Indicateur que l'utilisateur a découvert l'easter egg

## 🎯 Workflow utilisateur

1. **Admin** → `/admin/cadres/create` → Upload PNG
2. **Utilisateur** → Découvre easter egg → `frames_unlocked = 1`
3. **Utilisateur** → `/profile` → Voit sélecteur de cadres
4. **Utilisateur** → Sélectionne cadre → `cadre_profil = '/cadre/images/gold.png'`
5. **Admin** (si nécessaire) → `/admin/cadres` → Supprime cadre inapproprié

## 📁 Dossier des images

Les images PNG sont stockées dans : `/public/cadre/images/`

Format des noms de fichier : `cadre_{timestamp}_{nom}.png`

## 📏 Recommandations pour les images

- **Format** : PNG uniquement
- **Taille max** : 200KB
- **Dimensions recommandées** : 200x200px ou 300x300px
- **Type** : Cadre/overlay transparent (PNG avec alpha)

## 🚀 Utilisation

### Créer un cadre (Admin)
```
1. Aller à /admin/cadres/create
2. Remplir le formulaire :
   - Nom (ex: "Or Prestige")
   - Emoji (ex: "👑")
   - Description
   - Image PNG
3. Cliquer "Créer le cadre"
4. Image uploadée → `/public/cadre/images/cadre_{timestamp}_{nom}.png`
5. Chemin sauvegardé en BDD
```

### Utiliser un cadre (Utilisateur)
```
1. Découvrir l'easter egg (commentaire navbar)
2. Visiter /cadre/ → frames_unlocked = 1
3. Aller sur /profile
4. Sélectionner un cadre dans le sélecteur
5. cadre_profil mis à jour avec le chemin
6. Overlay PNG appliqué sur la photo de profil
```

### Supprimer un cadre (Admin)
```
1. Aller à /admin/cadres
2. Cliquer "Supprimer" sur le cadre
3. Confirmer la suppression
4. Cadre supprimé de BDD + fichier PNG supprimé du serveur
```

## 🔐 Sécurité

- ✅ Authentification admin requise pour toutes les actions
- ✅ Validation du type MIME (PNG uniquement)
- ✅ Vérification de la taille du fichier
- ✅ Noms de fichier sécurisés (timestamp + slug du nom)
- ✅ Suppression du fichier physique lors de la suppression BDD
- ✅ Protection contre les chemins traversée (stockage en dossier dédié)

## 📝 Notes

- Le cadre "Par défaut" ne peut pas être supprimé (chemin_fichier = NULL)
- Les emojis sont gérés en front (JavaScript) pour économiser l'espace BDD
- Aucune fonction de modération/raison de blocage (cadres simplement supprimés si approprié)
- Les cadres supprimés ne sont pas restaurables (suppression définitive)
