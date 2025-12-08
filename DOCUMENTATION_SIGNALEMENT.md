# Documentation du Système de Signalement

## Vue d'ensemble

Le système de signalement permet aux utilisateurs (connectés ou anonymes) de signaler des biens immobiliers qui contiennent du contenu inapproprié, des informations trompeuses, ou toute autre violation. Les signalements sont ensuite traités par les administrateurs.

---

## Architecture du Système

### 1. Base de données

#### Table `signalements`

```sql
CREATE TABLE signalements (
    id_signalement INT AUTO_INCREMENT PRIMARY KEY,
    id_biens INT NOT NULL,
    id_locataire INT NULL,
    email_signaleur VARCHAR(255) NULL,
    motif ENUM('contenu_inapproprie', 'fausses_informations', 'photos_trompeuses', 'arnaque', 'autre') NOT NULL,
    description TEXT NULL,
    statut ENUM('en_attente', 'traite', 'rejete') DEFAULT 'en_attente',
    date_signalement DATETIME NOT NULL,
    date_traitement DATETIME NULL,
    id_admin_traitant INT NULL,
    commentaire_admin TEXT NULL,
    FOREIGN KEY (id_biens) REFERENCES biens(id_biens) ON DELETE CASCADE,
    FOREIGN KEY (id_locataire) REFERENCES locataire(id_locataire) ON DELETE SET NULL,
    FOREIGN KEY (id_admin_traitant) REFERENCES admin(id_admin) ON DELETE SET NULL
)
```

#### Champs principaux

| Champ | Type | Description |
|-------|------|-------------|
| `id_signalement` | INT | Identifiant unique du signalement |
| `id_biens` | INT | Référence au bien signalé |
| `id_locataire` | INT (nullable) | ID de l'utilisateur connecté qui signale |
| `email_signaleur` | VARCHAR(255) | Email pour les signalements anonymes |
| `motif` | ENUM | Raison du signalement |
| `description` | TEXT | Description détaillée du problème |
| `statut` | ENUM | État du signalement (en_attente, traite, rejete) |
| `date_signalement` | DATETIME | Date de création |
| `date_traitement` | DATETIME | Date de traitement par l'admin |
| `id_admin_traitant` | INT | Admin qui a traité le signalement |
| `commentaire_admin` | TEXT | Commentaire interne de l'admin |

#### Motifs de signalement disponibles

1. **contenu_inapproprie** - Contenu inapproprié (violence, haine, etc.)
2. **fausses_informations** - Informations mensongères sur le bien
3. **photos_trompeuses** - Photos ne correspondant pas au bien
4. **arnaque** - Tentative d'escroquerie
5. **autre** - Autre problème

---

## Composants du Système

### 1. Modèle - `SignalementModel.php`

Le modèle gère toutes les opérations de base de données liées aux signalements.

#### Méthodes principales

##### `create($data)`
Crée un nouveau signalement.

```php
$data = [
    'id_biens' => 123,
    'id_locataire' => 456, // null pour anonyme
    'email_signaleur' => 'user@example.com', // null si connecté
    'motif' => 'fausses_informations',
    'description' => 'Le bien n\'a pas 3 chambres comme indiqué'
];
$id = $signalementModel->create($data);
```

##### `getSignalementsEnAttente()`
Récupère tous les signalements en attente avec les détails du bien, propriétaire et signaleur.

```php
$signalements = $signalementModel->getSignalementsEnAttente();
// Retourne un tableau avec:
// - Informations du signalement
// - designation_bien, proprietaire, signaleur
```

##### `countSignalementsEnAttente()`
Compte le nombre de signalements en attente (pour le badge navbar).

```php
$count = $signalementModel->countSignalementsEnAttente();
```

##### `countSignalementsByBien($idBiens)`
Compte les signalements actifs pour un bien spécifique.

```php
$count = $signalementModel->countSignalementsByBien(123);
```

##### `traiterSignalement($id, $adminId, $commentaire)`
Marque un signalement comme traité.

```php
$success = $signalementModel->traiterSignalement(
    $id = 1,
    $adminId = 5,
    $commentaire = "Bien vérifié et corrigé"
);
```

##### `rejeterSignalement($id, $adminId, $commentaire)`
Rejette un signalement (non fondé).

```php
$success = $signalementModel->rejeterSignalement(
    $id = 1,
    $adminId = 5,
    $commentaire = "Signalement non fondé"
);
```

##### `hasUserReported($idBiens, $idLocataire)`
Vérifie si un utilisateur a déjà signalé un bien (évite les doublons).

```php
$alreadyReported = $signalementModel->hasUserReported(123, 456);
if ($alreadyReported) {
    // Afficher un message d'erreur
}
```

##### `getById($id)`
Récupère un signalement complet par son ID avec tous les détails joints.

---

### 2. Contrôleurs

#### `HomeController::signaler($id)`

Gère la soumission des signalements par les utilisateurs.

**Route**: `/signaler/{id_biens}`  
**Méthode**: POST

**Processus**:
1. Vérifie que c'est une requête POST
2. Récupère les données du formulaire
3. Vérifie si l'utilisateur n'a pas déjà signalé ce bien
4. Crée le signalement
5. Redirige avec message flash de confirmation

**Paramètres POST attendus**:
```php
$_POST = [
    'motif' => 'fausses_informations',
    'description' => 'Description du problème',
    'email_signaleur' => 'user@example.com' // uniquement si non connecté
];
```

#### `AdminController::signalements()`

Affiche la liste des signalements en attente.

**Route**: `/admin/signalements`  
**Vue**: `admin/signalements.php`

#### `AdminController::traiterSignalement($id)`

Marque un signalement comme traité.

**Route**: `/admin/traiterSignalement/{id}`  
**Méthode**: GET (redirection)

#### `AdminController::rejeterSignalement($id)`

Rejette un signalement.

**Route**: `/admin/rejeterSignalement/{id}`  
**Méthode**: GET (redirection)

---

### 3. Vues

#### `bien/details.php` - Bouton de signalement

Interface utilisateur pour signaler un bien.

**Localisation**: Dans l'en-tête du bien, à côté du titre

**Fonctionnalités**:
- Bouton "🚩 Signaler" rouge
- Modal avec formulaire de signalement
- Dropdown de sélection du motif
- Zone de texte pour la description
- Champ email pour les utilisateurs non connectés
- Validation côté client

**Code JavaScript**:
```javascript
function openSignalementModal() {
    document.getElementById('signalementModal').style.display = 'flex';
}

function closeSignalementModal() {
    document.getElementById('signalementModal').style.display = 'none';
}
```

#### `admin/signalements.php` - Interface admin

Interface de gestion des signalements pour les administrateurs.

**Fonctionnalités**:
- Table DataTables avec tous les signalements en attente
- Colonnes: Bien, Propriétaire, Motif, Signaleur, Date, Actions
- Badges colorés par motif:
  - 🔴 Rouge (#ff5252) - Contenu inapproprié
  - 🟠 Orange (#ffa726) - Fausses informations
  - 🔵 Bleu (#42a5f5) - Photos trompeuses
  - 🔴 Rouge foncé (#d32f2f) - Arnaque
  - ⚪ Gris (#9e9e9e) - Autre

**Actions disponibles**:
- 👁️ **Voir** - Ouvre le bien dans un nouvel onglet
- 📋 **Détails** - Affiche la modal avec tous les détails du signalement
- ✅ **Traiter** - Marque comme traité et archive
- ❌ **Rejeter** - Rejette le signalement

**Modal de détails**:
Affiche toutes les informations du signalement:
- Bien signalé
- Motif
- Description complète
- Signaleur (nom ou email)
- Propriétaire du bien
- Date et heure

---

### 4. Badge de notification navbar

Les administrateurs voient un compteur rouge sur le lien "Signalements" dans la navbar.

**Localisation**: `app/Views/layout/navbar.php`

```php
<li>
    <a href="/admin/signalements" class="navbar-link">
        Signalements
        <?php if ($countSignalements > 0): ?>
            <span class="badge-notif" style="background: #ff5252;">
                <?php echo $countSignalements; ?>
            </span>
        <?php endif; ?>
    </a>
</li>
```

Le compteur s'affiche uniquement quand il y a des signalements en attente.

---

## Flux de Travail

### Pour l'utilisateur (signaler un bien)

1. **Accéder à un bien** → Page `/bien/{id}`
2. **Cliquer sur "🚩 Signaler"** → Modal s'ouvre
3. **Sélectionner un motif** → Dropdown avec 5 options
4. **Décrire le problème** (optionnel) → Zone de texte
5. **Entrer email si non connecté** → Champ email visible uniquement pour anonymes
6. **Soumettre** → POST vers `/signaler/{id}`
7. **Confirmation** → Message flash "Votre signalement a été envoyé avec succès"

### Pour l'administrateur

1. **Voir le badge** → Navbar affiche le nombre de signalements
2. **Accéder aux signalements** → `/admin/signalements`
3. **Consulter la liste** → Table DataTables triée par date
4. **Voir le bien** → Clic sur 👁️ ouvre le bien dans nouvel onglet
5. **Consulter les détails** → Clic sur 📋 affiche la modal
6. **Décider de l'action**:
   - **Traiter** → Marque comme résolu, archive le signalement
   - **Rejeter** → Marque comme non fondé
7. **Confirmation** → Message flash de succès

---

## Sécurité

### Prévention des doublons

Le système empêche un utilisateur connecté de signaler plusieurs fois le même bien.

```php
if ($signalementModel->hasUserReported($id, $_SESSION['user_id'])) {
    $_SESSION['flash'] = [
        'type' => 'warning',
        'message' => 'Vous avez déjà signalé ce bien.'
    ];
    $this->redirect('/bien/' . $id);
    return;
}
```

### Anonymat

- Les utilisateurs non connectés peuvent signaler en fournissant un email
- L'email n'est visible que par les administrateurs
- Les signalements anonymes sont marqués comme "Anonyme" dans l'interface

### Validation des données

- Le motif est validé via ENUM en base de données
- Les statuts sont limités à 3 valeurs: `en_attente`, `traite`, `rejete`
- Les relations sont protégées par des clés étrangères avec CASCADE/SET NULL

---

## États des signalements

| Statut | Description | Visible par admin | Action requise |
|--------|-------------|-------------------|----------------|
| `en_attente` | Nouveau signalement non traité | ✅ Oui | ⏳ À traiter |
| `traite` | Signalement vérifié et résolu | ❌ Non (archivé) | ✅ Terminé |
| `rejete` | Signalement non fondé | ❌ Non (archivé) | ✅ Terminé |

---

## Intégration avec le système de validation

Les signalements et la validation des biens sont deux systèmes distincts mais complémentaires :

- **Validation** : Contrôle préventif avant publication
- **Signalement** : Contrôle curatif après publication

Un bien peut être :
1. **Validé** → Visible publiquement → Peut être signalé
2. **En attente** → Non visible → Ne peut pas être signalé
3. **Refusé** → Non visible → Ne peut pas être signalé

---

## Routes

| URL | Méthode | Contrôleur | Description |
|-----|---------|-----------|-------------|
| `/signaler/{id}` | POST | HomeController::signaler | Soumettre un signalement |
| `/admin/signalements` | GET | AdminController::signalements | Liste des signalements |
| `/admin/traiterSignalement/{id}` | GET | AdminController::traiterSignalement | Marquer comme traité |
| `/admin/rejeterSignalement/{id}` | GET | AdminController::rejeterSignalement | Rejeter un signalement |

---

## Messages Flash

Le système utilise des messages flash pour informer l'utilisateur :

### Pour l'utilisateur

**Succès** (vert):
```
Votre signalement a été envoyé avec succès. Merci de votre contribution.
```

**Avertissement** (orange):
```
Vous avez déjà signalé ce bien.
```

### Pour l'administrateur

**Succès** (vert):
```
Le signalement a été marqué comme traité.
```

**Avertissement** (orange):
```
Le signalement a été rejeté.
```

**Erreur** (rouge):
```
Une erreur est survenue lors du traitement du signalement.
```

---

## Base de données - Script d'installation

Le script SQL se trouve dans : `sql_updates/add_signalement_system.sql`

### Installation

**Via phpMyAdmin** :
1. Ouvrir phpMyAdmin
2. Sélectionner la base `goodnight`
3. Onglet "Importer"
4. Sélectionner le fichier `add_signalement_system.sql`
5. Cliquer sur "Exécuter"

**Via ligne de commande MySQL** :
```bash
mysql -u root -p goodnight < sql_updates/add_signalement_system.sql
```

**Via PowerShell** :
```powershell
cd c:\wamp64\www\SIO-2\Projet-Location\Goodnight-main
& "C:\wamp64\bin\mysql\mysql8.1.0\bin\mysql.exe" -u root -p goodnight -e "source sql_updates/add_signalement_system.sql"
```

---

## Tests

### Scénarios de test

#### 1. Signalement par utilisateur connecté

**Prérequis** : Être connecté en tant que locataire

1. Accéder à un bien validé
2. Cliquer sur "🚩 Signaler"
3. Sélectionner "Fausses informations"
4. Entrer "Le nombre de chambres est incorrect"
5. Soumettre
6. ✅ **Attendu** : Message "Votre signalement a été envoyé avec succès"

#### 2. Signalement anonyme

**Prérequis** : Ne pas être connecté

1. Accéder à un bien validé
2. Cliquer sur "🚩 Signaler"
3. Sélectionner "Photos trompeuses"
4. Entrer une description
5. Entrer email: `test@example.com`
6. Soumettre
7. ✅ **Attendu** : Message de confirmation, signalement visible pour admin avec email

#### 3. Prévention doublon

**Prérequis** : Avoir déjà signalé le bien

1. Accéder au même bien
2. Cliquer sur "🚩 Signaler"
3. Remplir le formulaire
4. Soumettre
5. ✅ **Attendu** : Message "Vous avez déjà signalé ce bien"

#### 4. Traitement par admin

**Prérequis** : Être connecté en tant qu'admin, avoir un signalement en attente

1. Voir badge notification sur "Signalements"
2. Cliquer sur "Signalements"
3. Voir la liste des signalements
4. Cliquer sur 📋 pour voir les détails
5. Cliquer sur ✅ "Traiter"
6. Confirmer
7. ✅ **Attendu** : Message "Le signalement a été marqué comme traité", compteur -1

#### 5. Rejet par admin

**Prérequis** : Être connecté en tant qu'admin, avoir un signalement en attente

1. Accéder à `/admin/signalements`
2. Cliquer sur ❌ "Rejeter"
3. Confirmer
4. ✅ **Attendu** : Message "Le signalement a été rejeté", disparaît de la liste

---

## Maintenance

### Vérifier les signalements en attente

```sql
SELECT COUNT(*) FROM signalements WHERE statut = 'en_attente';
```

### Lister les biens les plus signalés

```sql
SELECT 
    b.id_biens,
    b.designation_bien,
    COUNT(s.id_signalement) as nb_signalements
FROM biens b
INNER JOIN signalements s ON b.id_biens = s.id_biens
WHERE s.statut = 'en_attente'
GROUP BY b.id_biens
ORDER BY nb_signalements DESC;
```

### Statistiques des motifs

```sql
SELECT 
    motif,
    COUNT(*) as nb_signalements
FROM signalements
GROUP BY motif
ORDER BY nb_signalements DESC;
```

### Nettoyer les anciens signalements traités

```sql
-- Supprimer les signalements traités de plus de 6 mois
DELETE FROM signalements 
WHERE statut IN ('traite', 'rejete') 
AND date_traitement < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

---

## Améliorations futures possibles

1. **Système de notification email** :
   - Notifier le propriétaire quand son bien est signalé
   - Envoyer un email de confirmation au signaleur

2. **Statistiques détaillées** :
   - Dashboard des signalements par période
   - Graphiques des motifs les plus fréquents
   - Taux de traitement par admin

3. **Actions automatiques** :
   - Masquer automatiquement un bien après X signalements
   - Système de score de confiance pour les propriétaires

4. **Historique complet** :
   - Voir tous les signalements (traités et rejetés)
   - Filtres par statut, motif, date

5. **Commentaires des propriétaires** :
   - Permettre au propriétaire de répondre au signalement
   - Système de contestation

6. **Prévention des abus** :
   - Limiter le nombre de signalements par IP/utilisateur
   - Captcha pour les signalements anonymes

7. **Intégration avec validation** :
   - Remettre automatiquement en validation si trop de signalements
   - Suspendre temporairement la publication

---

## Dépannage

### Le bouton "Signaler" n'apparaît pas

**Causes possibles** :
- Le bien n'est pas validé (seuls les biens validés peuvent être signalés)
- Erreur JavaScript dans la console
- Fichier `bien/details.php` non à jour

**Solution** : Vérifier que `statut_validation = 'valide'` pour le bien

### Le compteur ne s'affiche pas dans la navbar

**Causes possibles** :
- Aucun signalement en attente
- `SignalementModel.php` non chargé dans `navbar.php`

**Solution** : Vérifier la méthode `countSignalementsEnAttente()`

### Erreur "Class SignalementModel not found"

**Cause** : Le fichier du modèle n'est pas inclus

**Solution** : Ajouter `require_once __DIR__ . '/../Models/SignalementModel.php';`

### Les signalements traités réapparaissent

**Cause** : Le statut n'est pas mis à jour correctement

**Solution** : Vérifier que la méthode `traiterSignalement()` met bien `statut = 'traite'`

---

## Support

Pour toute question ou problème concernant le système de signalement, consulter :
- Ce fichier de documentation
- `DOCUMENTATION_VALIDATION.md` pour le système de validation
- Le code source dans `app/Models/SignalementModel.php`

---

**Dernière mise à jour** : 8 décembre 2025  
**Version** : 1.0  
**Auteur** : Système GlobeNight
