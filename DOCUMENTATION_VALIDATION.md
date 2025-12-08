# Système de Validation des Biens - Documentation

**Date de mise en place :** 8 décembre 2025  
**Version :** 1.0

---

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture et logique](#architecture-et-logique)
3. [Structure de la base de données](#structure-de-la-base-de-données)
4. [Fonctionnalités implémentées](#fonctionnalités-implémentées)
5. [Interface propriétaire](#interface-propriétaire)
6. [Interface administrateur](#interface-administrateur)
7. [Gestion des champs critiques](#gestion-des-champs-critiques)
8. [Installation](#installation)
9. [Fichiers modifiés](#fichiers-modifiés)

---

## 🎯 Vue d'ensemble

Le système de validation des biens permet à un administrateur de contrôler la qualité des annonces avant leur publication publique. Lorsqu'un propriétaire crée ou modifie un bien, celui-ci peut être soumis à validation selon la nature des modifications.

### Principe de fonctionnement

```
┌─────────────────────────────────────────────────────────────┐
│  Propriétaire crée/modifie un bien                          │
└───────────────────────┬─────────────────────────────────────┘
                        │
                        ▼
        ┌───────────────────────────────┐
        │ Modification de champ          │
        │ critique ?                     │
        └───────┬───────────────┬────────┘
                │               │
            OUI │               │ NON
                │               │
                ▼               ▼
    ┌──────────────────┐  ┌──────────────────┐
    │ Statut:          │  │ Statut: reste    │
    │ EN ATTENTE       │  │ VALIDÉ           │
    │ Non visible      │  │ Visible          │
    └────────┬─────────┘  └──────────────────┘
             │
             ▼
    ┌──────────────────┐
    │ Admin valide/    │
    │ refuse           │
    └────────┬─────────┘
             │
      ┌──────┴──────┐
      │             │
      ▼             ▼
┌─────────┐   ┌─────────┐
│ VALIDÉ  │   │ REFUSÉ  │
│ Visible │   │ Masqué  │
└─────────┘   └─────────┘
```

---

## 🏗️ Architecture et logique

### Option C : Validation sélective (implémentée)

Seuls les **champs critiques** déclenchent une re-validation :

#### Champs CRITIQUES (nécessitent validation)
- `designation_bien` - Nom du bien
- `description_biens` - Description complète
- `id_TypeBien` - Type de bien (Villa, Maison, etc.)
- `photos` - Ajout ou suppression de photos

#### Champs NON-CRITIQUES (modification libre)
- `superficie_biens` - Superficie
- `nb_couchage` - Nombre de couchages
- `animaux_biens` - Acceptation des animaux
- `complement_biens` - Complément d'adresse
- Prestations (WiFi, Piscine, etc.)
- Tarifs

---

## 💾 Structure de la base de données

### Nouvelles colonnes ajoutées à la table `biens`

```sql
ALTER TABLE `biens` 
ADD COLUMN `statut_validation` ENUM('en_attente', 'valide', 'refuse') NOT NULL DEFAULT 'en_attente',
ADD COLUMN `date_soumission` DATETIME DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN `date_validation` DATETIME NULL,
ADD COLUMN `id_admin_validateur` INT NULL,
ADD COLUMN `motif_refus` TEXT NULL;

-- Index pour améliorer les performances
ALTER TABLE `biens` ADD INDEX `idx_statut_validation` (`statut_validation`);
```

### États possibles

| Statut | Description | Visibilité publique |
|--------|-------------|---------------------|
| `en_attente` | En attente de validation admin | ❌ Non visible |
| `valide` | Validé par un admin | ✅ Visible |
| `refuse` | Refusé par un admin | ❌ Non visible |

---

## ✨ Fonctionnalités implémentées

### 1. Filtrage automatique des biens

**Fichier :** `app/Models/BienModel.php`

La méthode `getBiensWithDetails()` filtre automatiquement pour n'afficher que les biens validés sur :
- Page d'accueil (`/home`)
- Carte interactive (`/home/map`)
- Recherche

```php
WHERE b.statut_validation = 'valide'
```

### 2. Détection des champs critiques

**Fichier :** `app/Models/BienModel.php`

```php
private function detectCriticalFieldsChanged($id, $newData) {
    $currentData = $this->getById($id);
    $criticalFields = ['designation_bien', 'description_biens', 'id_TypeBien'];
    
    foreach ($criticalFields as $field) {
        if (isset($newData[$field]) && $currentData[$field] != $newData[$field]) {
            return true;
        }
    }
    return false;
}
```

### 3. Système de messages flash

**Fichier :** `app/Views/proprietaire/my_biens.php`

Messages contextuels affichés selon l'action :

```php
$_SESSION['flash'] = [
    'type' => 'success|warning|error',
    'message' => 'Texte du message'
];
```

Types de messages :
- 🟢 **success** : Modification sans re-validation
- 🟡 **warning** : Soumission/Re-validation nécessaire
- 🔴 **error** : Erreur survenue

---

## 👨‍💼 Interface propriétaire

### Page "Mes Biens" (`/proprietaire/myBiens`)

**Fichier :** `app/Views/proprietaire/my_biens.php`

#### Badge de statut

Chaque bien affiche un badge coloré :

| Badge | Statut | Couleur |
|-------|--------|---------|
| 🟡 En attente | `en_attente` | Orange (#ffa726) |
| 🟢 Validé | `valide` | Vert (#66bb6a) |
| 🔴 Refusé | `refuse` | Rouge (#ef5350) |

**Tooltip :** Au survol du badge "Refusé", le motif s'affiche si renseigné.

### Formulaire de modification (`/proprietaire/editBien/{id}`)

**Fichier :** `app/Views/proprietaire/edit_bien.php`

#### Alerte informative (en haut du formulaire)

```
ℹ️ Attention : La modification des champs suivants nécessite une re-validation :
• Désignation du bien
• Description
• Type de bien
• Photos
```

#### Alerte JavaScript avant soumission (Option A)

Avant de soumettre le formulaire, une popup s'affiche si des champs critiques ont été modifiés :

```javascript
⚠️ ATTENTION

Vous avez modifié les champs critiques suivants :
• Désignation du bien
• Description

Votre bien sera remis en attente de validation.
Il ne sera plus visible publiquement jusqu'à validation par un administrateur.

Voulez-vous continuer ?
[Annuler] [OK]
```

### Messages flash contextuels

#### À la création d'un bien
```
🟡 Votre bien "[Nom]" a été soumis avec succès ! 
Il est en attente de validation par un administrateur.
```

#### Après modification critique
```
🟡 Votre bien a été modifié et est maintenant en attente de validation. 
Il ne sera plus visible publiquement jusqu'à validation par un admin.
```

#### Après modification non-critique
```
🟢 Votre bien a été modifié avec succès ! 
Les modifications sont immédiatement visibles.
```

---

## 🛡️ Interface administrateur

### Page de validation (`/admin/validations`)

**Fichier :** `app/Views/admin/validations.php`

#### DataTable des biens en attente

Colonnes affichées :
- **Désignation** - Nom du bien
- **Type** - Type de bien (Villa, Maison, etc.)
- **Propriétaire** - Nom du propriétaire
- **Commune** - Localisation
- **Date soumission** - Date et heure de soumission

#### Actions disponibles

| Bouton | Action | Résultat |
|--------|--------|----------|
| 👁️ Voir | Prévisualiser le bien | Ouvre `/bien/{id}` dans nouvel onglet |
| ✅ Valider | Valider le bien | Statut → `valide`, visible publiquement |
| ❌ Refuser | Refuser le bien | Ouvre modal pour motif |

#### Modal de refus

Lorsque l'admin clique sur ❌ Refuser :

```
┌─────────────────────────────────────────────┐
│ Refuser le bien "Villa Paradis"            │
│                                             │
│ Motif du refus (optionnel) :               │
│ ┌─────────────────────────────────────────┐ │
│ │ Ex: Photos de mauvaise qualité,        │ │
│ │ description incomplète...               │ │
│ └─────────────────────────────────────────┘ │
│                                             │
│              [Annuler] [Confirmer le refus] │
└─────────────────────────────────────────────┘
```

Le motif est stocké dans `biens.motif_refus` et affiché au propriétaire.

### Badge de notification (navbar)

**Fichier :** `app/Views/layout/navbar.php`

Dans la navbar admin, un compteur rouge affiche le nombre de biens en attente :

```
Administration | Validations (3) <- Badge rouge avec le nombre
```

Le compteur est dynamique et se met à jour automatiquement.

---

## 🔄 Gestion des champs critiques

### Logique de détection

**Fichier :** `app/Models/BienModel.php` - Méthode `update()`

```php
public function update($id, $data) {
    // Détection des champs critiques modifiés
    $champsCritiquesModifies = $this->detectCriticalFieldsChanged($id, $data);
    
    $sql = "UPDATE biens SET ...";
    
    // Si champ critique modifié → remettre en attente
    if ($champsCritiquesModifies) {
        $sql .= ", statut_validation = 'en_attente', date_soumission = NOW()";
    }
    
    // Retourne true/false pour indiquer si re-validation nécessaire
    return $champsCritiquesModifies;
}
```

### Gestion des photos (cas particulier)

Les photos sont considérées comme champ critique :
- L'alerte JavaScript détecte si `input[type=file]` contient des fichiers
- Ajout de photos → Re-validation automatique
- **Note :** La suppression de photos n'est pas détectée côté JavaScript

---

## 📦 Installation

### 1. Exécuter le script SQL

**Fichier :** `sql_updates/add_validation_system.sql`

#### Option A : Via phpMyAdmin (recommandé)

1. Ouvrir http://localhost/phpmyadmin
2. Sélectionner la base `goodnight`
3. Onglet "SQL"
4. Copier-coller le contenu du fichier
5. Cliquer "Exécuter"

#### Option B : Via ligne de commande MySQL

```powershell
# Depuis le répertoire du projet
Get-Content sql_updates/add_validation_system.sql | & "C:\wamp64\bin\mysql\mysql8.0.31\bin\mysql.exe" -u root goodnight
```

### 2. Vérifier l'installation

```sql
-- Vérifier que les colonnes ont été ajoutées
DESCRIBE biens;

-- Vérifier que l'index a été créé
SHOW INDEX FROM biens;
```

### 3. Migration des données existantes

Le script SQL met automatiquement tous les biens existants en statut `valide` pour ne pas casser l'existant :

```sql
UPDATE `biens` SET `statut_validation` = 'valide', `date_validation` = NOW();
```

---

## 📁 Fichiers modifiés

### Fichiers créés

```
sql_updates/add_validation_system.sql          - Script de migration SQL
app/Views/admin/validations.php                - Page admin de validation
DOCUMENTATION_VALIDATION.md                    - Cette documentation
```

### Modèles (Models)

```
app/Models/BienModel.php
├── Méthode create()                           - Ajout statut 'en_attente'
├── Méthode update()                           - Détection champs critiques
├── detectCriticalFieldsChanged()              - Détection des modifications
├── getBiensWithDetails()                      - Filtre statut 'valide'
├── getBiensEnAttente()                        - Liste biens en attente
├── validerBien()                              - Validation admin
├── refuserBien()                              - Refus admin
└── countBiensEnAttente()                      - Compteur pour navbar
```

### Contrôleurs (Controllers)

```
app/Controllers/ProprietaireController.php
├── addBien()                                  - Message flash soumission
└── editBien()                                 - Message flash contextuel

app/Controllers/AdminController.php
├── validations()                              - Affiche page validation
├── validerBien()                              - Action validation
└── refuserBien()                              - Action refus
```

### Vues (Views)

```
app/Views/proprietaire/my_biens.php
├── Colonne "Statut"                           - Badge coloré
├── Système de messages flash                  - Affichage contextuel
└── Tooltip sur badge refusé                   - Motif du refus

app/Views/proprietaire/edit_bien.php
├── Alerte informative                         - Liste champs critiques
└── JavaScript confirmCriticalChanges()        - Popup de confirmation

app/Views/admin/validations.php
├── DataTable biens en attente                 - Liste complète
├── Boutons d'action                           - Voir/Valider/Refuser
└── Modal de refus                             - Saisie du motif

app/Views/layout/navbar.php
└── Badge notification                         - Compteur biens en attente
```

### Routes

```
public/index.php
├── admin/validations                          - Page validation admin
├── admin/validerBien/{id}                     - Valider un bien
└── admin/refuserBien/{id}                     - Refuser un bien
```

---

## 🧪 Scénarios de test

### Test 1 : Création d'un nouveau bien (Propriétaire)

1. ✅ Se connecter en tant que propriétaire
2. ✅ Aller sur "Mes Biens" → "Ajouter un nouveau bien"
3. ✅ Remplir le formulaire et soumettre
4. ✅ **Résultat attendu :** 
   - Message flash : "Votre bien a été soumis avec succès !"
   - Badge 🟡 "En attente" affiché
   - Bien non visible sur la page d'accueil

### Test 2 : Modification non-critique (Propriétaire)

1. ✅ Modifier la superficie d'un bien validé
2. ✅ **Résultat attendu :**
   - Pas d'alerte JavaScript
   - Message flash : "Modifié avec succès !"
   - Badge reste 🟢 "Validé"
   - Bien toujours visible publiquement

### Test 3 : Modification critique (Propriétaire)

1. ✅ Modifier la description d'un bien validé
2. ✅ **Résultat attendu :**
   - Alerte JavaScript avec confirmation
   - Si confirmé : Message flash "En attente de validation"
   - Badge passe à 🟡 "En attente"
   - Bien n'est plus visible publiquement

### Test 4 : Validation admin

1. ✅ Se connecter en tant qu'admin
2. ✅ Aller sur "Validations" (compteur affiché si biens en attente)
3. ✅ Cliquer sur ✅ Valider
4. ✅ **Résultat attendu :**
   - Bien disparaît de la liste
   - Compteur navbar décrémente
   - Bien visible publiquement

### Test 5 : Refus admin

1. ✅ Cliquer sur ❌ Refuser
2. ✅ Saisir un motif : "Photos floues"
3. ✅ Confirmer
4. ✅ **Résultat attendu :**
   - Bien disparaît de la liste
   - Propriétaire voit badge 🔴 "Refusé"
   - Motif visible au survol du badge

---

## 🔧 Configuration avancée

### Modifier les champs critiques

**Fichier :** `app/Models/BienModel.php` - Ligne ~311

```php
$criticalFields = ['designation_bien', 'description_biens', 'id_TypeBien'];

// Pour ajouter un champ :
$criticalFields = ['designation_bien', 'description_biens', 'id_TypeBien', 'superficie_biens'];
```

**Important :** Également modifier le JavaScript dans `edit_bien.php` pour détecter le nouveau champ.

### Personnaliser les messages flash

**Fichier :** `app/Controllers/ProprietaireController.php`

```php
$_SESSION['flash'] = [
    'type' => 'warning',
    'message' => 'Votre message personnalisé'
];
```

### Désactiver l'alerte JavaScript

**Fichier :** `app/Views/proprietaire/edit_bien.php`

Supprimer `onsubmit="return confirmCriticalChanges()"` du formulaire.

---

## 📊 Statistiques et monitoring

### Requêtes SQL utiles

```sql
-- Nombre de biens par statut
SELECT statut_validation, COUNT(*) as total 
FROM biens 
GROUP BY statut_validation;

-- Biens en attente depuis plus de 7 jours
SELECT designation_bien, date_soumission 
FROM biens 
WHERE statut_validation = 'en_attente' 
  AND DATEDIFF(NOW(), date_soumission) > 7;

-- Statistiques de validation par admin
SELECT 
    a.nom_admin, 
    COUNT(*) as nb_validations 
FROM biens b
JOIN admin a ON b.id_admin_validateur = a.id_admin
WHERE b.statut_validation = 'valide'
GROUP BY a.nom_admin;
```

---

## 🐛 Dépannage

### Le bien reste en attente après validation

**Cause :** Cache ou session non rafraîchie

**Solution :**
```sql
SELECT statut_validation FROM biens WHERE id_biens = X;
```
Vérifier que le statut est bien `valide` en base.

### Le compteur navbar ne s'affiche pas

**Cause :** Erreur dans `navbar.php`

**Solution :** Vérifier les logs PHP et que `BienModel` est bien chargé.

### L'alerte JavaScript ne se déclenche pas

**Cause :** JavaScript non chargé ou erreur console

**Solution :** 
1. Ouvrir la console navigateur (F12)
2. Vérifier qu'il n'y a pas d'erreur JavaScript
3. Tester `console.log(initialValues)` dans le script

---

## 📝 Notes techniques

- **Compatibilité :** PHP 7.4+, MySQL 5.7+
- **Dépendances :** jQuery 3.6.0, DataTables 1.13.6
- **Performance :** Index ajouté sur `statut_validation` pour optimiser les requêtes
- **Sécurité :** Tous les inputs sont échappés avec `htmlspecialchars()`

---

## 🚀 Améliorations futures possibles

- [ ] Notifications email au propriétaire lors de validation/refus
- [ ] Historique des modifications avec versioning
- [ ] Système de commentaires admin → propriétaire
- [ ] Délai maximum de validation (alerte après X jours)
- [ ] Tableau de bord admin avec statistiques
- [ ] Export CSV des biens en attente
- [ ] Validation en masse (cocher plusieurs biens)
- [ ] Filtres avancés (par propriétaire, par date, etc.)

---

**Développé le :** 8 décembre 2025  
**Système :** Option C - Validation sélective avec feedback utilisateur  
**Statut :** ✅ Production ready
