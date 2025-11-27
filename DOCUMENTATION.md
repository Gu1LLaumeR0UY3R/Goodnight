# Documentation Technique - Application GlobeNight

## Table des Matières

1. [Architecture de l'Application](#architecture)
2. [Contrôleurs (Controllers)](#controllers)
3. [Modèles (Models)](#models)
4. [Middlewares](#middlewares)
5. [Routes](#routes)
6. [Base de Données](#database)

---

## Architecture de l'Application {#architecture}

L'application suit une architecture MVC (Model-View-Controller) :

- **Models** : Gestion de la logique métier et accès aux données
- **Views** : Templates PHP pour l'affichage
- **Controllers** : Logique de traitement des requêtes
- **Middlewares** : Gestion de l'authentification et des autorisations

### Structure des Dossiers

```
app/
├── Controllers/     # Contrôleurs de l'application
├── Models/         # Modèles de données
└── Views/          # Templates d'affichage
    ├── admin/      # Vues administrateur
    ├── proprietaire/ # Vues propriétaire
    ├── locataire/  # Vues locataire
    ├── home/       # Page d'accueil
    ├── login/      # Connexion
    └── register/   # Inscription

config/             # Configuration de l'application
lib/               # Bibliothèques tierces
public/            # Fichiers accessibles publiquement
    ├── css/       # Feuilles de style
    ├── js/        # Scripts JavaScript
    └── uploads/   # Fichiers uploadés
```

---

## Contrôleurs (Controllers) {#controllers}

### BaseController

**Rôle** : Contrôleur de base dont héritent tous les autres contrôleurs.

#### Méthodes

- `render($view, $data, $cssFiles)` : Rend une vue avec les données fournies
- `redirect($url)` : Redirige vers une URL

---

### AdminController

**Rôle** : Gestion de l'interface d'administration (biens, utilisateurs, rôles, saisons, etc.)

#### Méthodes Principales

**Dashboard**
- `index()` : Affiche le tableau de bord administrateur

**Gestion des Admins**
- `admins()` : Liste tous les administrateurs
- `addAdmin()` : Ajoute un nouvel administrateur
- `editAdmin($id)` : Modifie un administrateur
- `deleteAdmin($id)` : Supprime un administrateur

**Gestion des Rôles**
- `roles()` : Liste tous les rôles
- `addRole()` : Ajoute un nouveau rôle
- `editRole($id)` : Modifie un rôle
- `deleteRole($id)` : Supprime un rôle

**Gestion des Types de Biens**
- `typesBiens()` : Liste tous les types de biens
- `addTypeBien()` : Ajoute un nouveau type de bien
- `editTypeBien($id)` : Modifie un type de bien
- `deleteTypeBien($id)` : Supprime un type de bien

**Gestion des Saisons**
- `saisons()` : Liste toutes les saisons
- `addSaison()` : Ajoute une nouvelle saison
- `editSaison($id)` : Modifie une saison
- `deleteSaison($id)` : Supprime une saison

**Gestion des Biens**
- `biens()` : Liste tous les biens
- `addBien()` : Ajoute un nouveau bien (avec photos et tarifs)
- `editBien($id)` : Modifie un bien (avec gestion des photos et tarifs)
- `deleteBien($id)` : Supprime un bien

**Gestion des Utilisateurs**
- `users()` : Liste tous les utilisateurs
- `addUser()` : Ajoute un nouvel utilisateur
- `editUser($id)` : Modifie un utilisateur
- `deleteUser($id)` : Supprime un utilisateur

**Gestion des Réservations**
- `reservations()` : Liste toutes les réservations
- `addReservation()` : Ajoute une nouvelle réservation
- `editReservation($id)` : Modifie une réservation
- `deleteReservation($id)` : Supprime une réservation

**Gestion des Photos**
- `handlePhotoUpload($bienId, $files)` : Gère l'upload de photos
- `deletePhoto($photoId)` : Supprime une photo

**API Autocomplete**
- `searchUsers()` : Recherche d'utilisateurs (JSON)
- `searchCommunes()` : Recherche de communes (JSON)
- `communes()` : Liste toutes les communes

---

### ProprietaireController

**Rôle** : Gestion de l'espace propriétaire (ses biens, réservations, blocages)

#### Méthodes Principales

**Dashboard**
- `index()` : Affiche le tableau de bord avec roue 3D et filtres

**Gestion des Biens**
- `myBiens()` : Liste les biens du propriétaire connecté
- `addBien()` : Ajoute un nouveau bien (avec photos et tarifs)
- `editBien($id)` : Modifie un bien (avec gestion photos et tarifs)
- `deleteBien($id)` : Supprime un bien
- `deletePhoto($photoId)` : Supprime une photo d'un bien

**Gestion des Réservations**
- `myReservations()` : Liste les réservations des biens du propriétaire

**Calendrier et Blocages**
- `calendarEvents()` : Retourne les événements du calendrier (réservations + blocages) en JSON
- `calendarBlock()` : Crée un blocage de dates avec validation anti-chevauchement
- `calendarUnblock()` : Supprime un blocage

**Utilitaires**
- `handlePhotoUpload($bienId, $files)` : Gère l'upload de photos

---

### LocataireController

**Rôle** : Gestion de l'espace locataire

#### Méthodes Principales

- `index()` : Affiche le tableau de bord locataire
- Hérite des fonctionnalités de réservation via ReservationController

---

### ReservationController

**Rôle** : Gestion des réservations de biens

#### Méthodes Principales

- `store()` : Crée une nouvelle réservation
- `myReservations()` : Liste les réservations du locataire connecté
- `cancel($id)` : Annule une réservation

---

### LoginController

**Rôle** : Gestion de l'authentification des utilisateurs

#### Méthodes Principales

**Connexion**
- `index()` : Affiche le formulaire de connexion
- `login()` : Traite la connexion (admin ou utilisateur)
- `logout()` : Déconnecte l'utilisateur

**Réinitialisation du mot de passe**
- `showResetForm()` : Affiche le formulaire de réinitialisation
- `requestPasswordReset()` : Traite la demande de réinitialisation
- `updatePassword()` : Met à jour le mot de passe

**Utilitaires**
- `redirectByRole()` : Redirige selon le rôle de l'utilisateur

---

### RegisterController

**Rôle** : Gestion de l'inscription des nouveaux utilisateurs

#### Méthodes Principales

- `index()` : Affiche le formulaire d'inscription
- `register()` : Traite l'inscription (personne physique/morale, propriétaire/locataire)

**Fonctionnalités**
- Validation des données (email, téléphone E.164, SIRET)
- Support personne physique et personne morale
- Attribution des rôles (Propriétaire ID:2, Locataire ID:3)
- Validation numéro de téléphone international

---

### HomeController

**Rôle** : Gestion de la page d'accueil publique et recherche de biens

#### Méthodes Principales

- `index()` : Affiche la page d'accueil avec recherche
- `search()` : Recherche de biens par critères
- `details($id)` : Affiche les détails d'un bien
- `autocompleteCommunes()` : Autocomplete pour les communes (JSON)

---

### ProfileController

**Rôle** : Gestion du profil utilisateur

#### Méthodes Principales

- `index()` : Affiche le profil de l'utilisateur
- `uploadProfilePicture()` : Upload d'une photo de profil
- `deleteProfilePicture()` : Supprime la photo de profil

---

### AuthMiddleware

**Rôle** : Middleware de gestion des autorisations

#### Méthodes Statiques

- `ensureSession()` : Démarre la session si nécessaire
- `requireLogin()` : Vérifie que l'utilisateur est connecté
- `checkUserRole($roles)` : Vérifie que l'utilisateur a un rôle spécifique
- `requireRole($roles)` : Vérifie que l'utilisateur a l'un des rôles requis

**Fonctionnalités**
- Les administrateurs ont accès à tout
- Vérification dans `$_SESSION['user_roles']`
- Messages d'erreur détaillés en cas de refus d'accès

---

## Modèles (Models) {#models}

### Model (Classe de Base)

**Rôle** : Classe abstraite de base pour tous les modèles

#### Propriétés
- `$db` : Connexion PDO à la base de données
- `$table` : Nom de la table
- `$primaryKey` : Nom de la clé primaire

#### Méthodes Génériques
- `getAll()` : Retourne tous les enregistrements
- `getById($id)` : Retourne un enregistrement par son ID
- `create($data)` : Crée un nouvel enregistrement
- `update($id, $data)` : Met à jour un enregistrement
- `delete($id)` : Supprime un enregistrement

---

### AdminModel

**Table** : `admin`

#### Méthodes Spécifiques
- `getAdminByEmail($email)` : Recherche un admin par email

**Champs**
- `id_admin`, `nom_admin`, `prenom_admin`, `email_admin`, `mot_de_passe_admin`, `is_admin`

---

### UserModel

**Table** : `locataire`

#### Méthodes Spécifiques

**Authentification**
- `getUserByEmail($email)` : Recherche un utilisateur par email

**Gestion des Rôles**
- `assignRole($userId, $roleId)` : Assigne un rôle à un utilisateur
- `removeRole($userId, $roleId)` : Retire un rôle à un utilisateur
- `getUserRoles($userId)` : Retourne tous les rôles d'un utilisateur

**Recherche**
- `searchUsersByIdRoleAndName($term, $id_roles)` : Recherche d'utilisateurs par nom et rôle
- `getUsersByRole($roleId, $type)` : Retourne les utilisateurs d'un rôle (physique/morale)
- `getAllUsersWithRoles()` : Retourne tous les utilisateurs avec leurs rôles

**Champs**
- `id_locataire`, `nom_locataire`, `prenom_locataire`, `dateNaissance_locataire`
- `email_locataire`, `password_locataire`, `tel_locataire`
- `rue_locataire`, `complement_locataire`, `id_commune`
- `RaisonSociale`, `Siret` (pour personnes morales)
- `pfp_loca` (photo de profil)

---

### BienModel

**Table** : `biens`

#### Méthodes Spécifiques
- `getBiensByProprietaire($proprietaireId)` : Retourne les biens d'un propriétaire
- `search($criteria)` : Recherche de biens par critères multiples
- `getBienWithDetails($id)` : Retourne un bien avec toutes ses informations

**Champs**
- `id_biens`, `designation_bien`, `rue_biens`, `complement_biens`
- `superficie_biens`, `description_biens`, `animaux_biens`, `nb_couchage`
- `id_TypeBien`, `id_commune`, `id_locataire`

---

### PhotoModel

**Table** : `Photos`

#### Méthodes Spécifiques
- `getPhotosByBien($bienId)` : Retourne toutes les photos d'un bien

**Champs**
- `id_photo`, `nom_photo`, `lien_photo`, `id_biens`

---

### ReservationModel

**Table** : `reservation`

#### Méthodes Spécifiques
- `getReservationsByLocataire($locataireId)` : Réservations d'un locataire
- `getReservationsByProprietaire($proprietaireId)` : Réservations des biens d'un propriétaire
- `getReservationsByBien($bienId)` : Réservations d'un bien spécifique

**Champs**
- `id_reservation`, `date_debut`, `date_fin`, `id_biens`, `id_locataire`, `id_tarif`

---

### BlocageModel

**Table** : `blocages`

#### Méthodes Spécifiques
- `getBlocagesByProprietaire($proprietaireId)` : Blocages des biens d'un propriétaire
- `getBlocagesByBien($bienId)` : Blocages d'un bien spécifique
- `checkOverlapWithReservations($bienId, $dateDebut, $dateFin)` : Vérifie les chevauchements

**Champs**
- `id_blocage`, `date_debut`, `date_fin`, `motif`, `id_biens`

---

### TarifModel

**Table** : `tarif`

#### Méthodes Spécifiques
- `getTarifsByBien($bienId)` : Retourne tous les tarifs d'un bien
- `getTarifByBienSaisonAnnee($bienId, $saisonId, $annee)` : Tarif spécifique

**Champs**
- `id_tarif`, `prix_semaine`, `annee`, `id_biens`, `id_saison`

---

### SaisonModel

**Table** : `saison`

**Champs**
- `id_saison`, `lib_saison`, `date_debut`, `date_fin`

---

### TypeBienModel

**Table** : `typebien`

#### Méthodes Spécifiques
- `getAllTypesBiens()` : Retourne tous les types ordonnés

**Champs**
- `id_typebien`, `desc_type_bien`

---

### RoleModel

**Table** : `roles`

**Champs**
- `id_roles`, `nom_roles`

**Rôles Standards**
- ID 1 : Administrateur
- ID 2 : Propriétaire
- ID 3 : Locataire

---

### CommuneModel

**Table** : `commune`

#### Méthodes Spécifiques
- `search($term)` : Recherche de communes par nom ou code postal

**Champs**
- `id_commune`, `ville_nom`, `ville_code_postal`

---

## Middlewares {#middlewares}

### AuthMiddleware

Contrôle l'accès aux différentes sections de l'application.

**Utilisation dans les contrôleurs**
```php
class ProprietaireController extends BaseController {
    public function __construct() {
        AuthMiddleware::requireRole("Propriétaire");
        // ...
    }
}
```

**Règles d'accès**
- Les administrateurs ont accès à toutes les sections
- Les propriétaires ont accès à leur espace propriétaire
- Les locataires ont accès à leur espace locataire
- L'accès est vérifié via `$_SESSION['user_roles']`

---

## Routes {#routes}

### Routes Publiques
- `/` ou `/home` : Page d'accueil
- `/home/search` : Recherche de biens
- `/bien/{id}` : Détails d'un bien
- `/register` : Inscription
- `/login` : Connexion

### Routes Administrateur
- `/admin` : Dashboard admin
- `/admin/admins` : Gestion des admins
- `/admin/users` : Gestion des utilisateurs
- `/admin/biens` : Gestion des biens
- `/admin/roles` : Gestion des rôles
- `/admin/saisons` : Gestion des saisons
- `/admin/typesBiens` : Gestion des types de biens
- `/admin/communes` : Gestion des communes
- `/admin/reservations` : Gestion des réservations

### Routes Propriétaire
- `/proprietaire` : Dashboard propriétaire (roue 3D)
- `/proprietaire/myBiens` : Liste des biens
- `/proprietaire/addBien` : Ajouter un bien
- `/proprietaire/editBien/{id}` : Modifier un bien
- `/proprietaire/myReservations` : Réservations
- `/proprietaire/calendar/events` : Événements calendrier (API JSON)
- `/proprietaire/calendar/block` : Créer un blocage
- `/proprietaire/calendar/unblock` : Supprimer un blocage

### Routes Locataire
- `/locataire` : Dashboard locataire
- `/locataire/myReservations` : Mes réservations
- `/reservation/store` : Créer une réservation
- `/reservation/cancel/{id}` : Annuler une réservation

### Routes Profil
- `/profile` : Mon profil
- `/profile/uploadProfilePicture` : Upload photo de profil
- `/profile/deleteProfilePicture` : Supprimer photo de profil

---

## Base de Données {#database}

### Tables Principales

**admin**
- Administrateurs de l'application

**locataire**
- Utilisateurs (personnes physiques et morales)
- Peut avoir plusieurs rôles via la table de liaison `User_role`

**biens**
- Propriétés à louer
- Liées à un propriétaire (`id_locataire`)
- Liées à une commune et un type de bien

**reservation**
- Réservations de biens par les locataires
- Date de début et fin
- Liée à un tarif

**blocages**
- Périodes bloquées par les propriétaires
- Empêche les réservations sur ces dates

**photos**
- Images des biens
- Stockées dans `/public/uploads/`

**tarif**
- Prix par semaine selon la saison et l'année
- Lié à un bien et une saison

**saison**
- Saisons tarifaires (Haute, Basse, etc.)

**typebien**
- Types de propriétés (Appartement, Maison, Villa, etc.)

**commune**
- Villes et codes postaux

**roles**
- Rôles utilisateurs (Administrateur, Propriétaire, Locataire)

**User_role**
- Table de liaison many-to-many entre utilisateurs et rôles

---

## Fonctionnalités Clés

### 1. Système d'Authentification Multi-Rôles
- Un utilisateur peut avoir plusieurs rôles simultanément
- Les administrateurs ont accès complet
- Vérification des permissions via `AuthMiddleware`

### 2. Gestion des Biens
- Upload multiple de photos avec drag & drop
- Tarification par saison et année
- Blocages de dates pour indisponibilité
- Validation anti-chevauchement réservations/blocages

### 3. Roue 3D Interactive (Dashboard Propriétaire)
- Affichage visuel des biens en rotation 3D
- Filtres par type de bien
- Sélection/désélection de biens
- Animations CSS fluides

### 4. Calendrier FullCalendar
- Affichage des réservations (vert)
- Affichage des blocages (orange)
- Création de blocages avec validation
- Suppression de blocages

### 5. Recherche Avancée
- Recherche par commune
- Filtres multiples (type, capacité, animaux)
- Autocomplete sur les communes

### 6. Validation des Données
- Téléphone international (format E.164) avec intl-tel-input
- SIRET (14 chiffres)
- Vérification des doublons (email)
- Validation des dates (réservations, blocages)

---

## Configuration

### Fichiers de Configuration

**config/config.php**
- Configuration de la base de données
- Constantes de l'application (UPLOAD_DIR, UPLOAD_URL)

### Variables d'Environnement (Session)
```php
$_SESSION['user_id']        // ID de l'utilisateur
$_SESSION['user_email']     // Email
$_SESSION['user_nom']       // Nom
$_SESSION['user_prenom']    // Prénom
$_SESSION['user_roles']     // Array des rôles
$_SESSION['role']           // Rôle principal
$_SESSION['is_admin']       // Boolean (administrateur)
$_SESSION['user_pfp']       // Photo de profil
```

---

## Conventions de Code

### Nomenclature
- **Contrôleurs** : PascalCase + "Controller" (ex: `ProprietaireController`)
- **Modèles** : PascalCase + "Model" (ex: `BienModel`)
- **Méthodes** : camelCase (ex: `myBiens()`)
- **Vues** : snake_case (ex: `my_biens.php`)

### Structure des Méthodes de Contrôleur
```php
public function maMethode($param) {
    // 1. Vérification des permissions (si nécessaire)
    
    // 2. Traitement POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Validation des données
        // Traitement
        // Redirection
        return;
    }
    
    // 3. Récupération des données
    $data = $this->model->getData();
    
    // 4. Rendu de la vue
    $this->render("vue/chemin", ["data" => $data]);
}
```

---

## Dépendances Externes

### Frontend
- **jQuery 3.6.0** : Manipulation DOM
- **jQuery UI 1.13.2** : Autocomplete
- **FullCalendar 6.1.11** : Calendrier
- **intl-tel-input** : Validation téléphone international
- **DataTables** : Tables dynamiques (admin)

### Backend
- **PHP 8.2+** : Langage serveur
- **MySQL/MariaDB** : Base de données
- **PDO** : Accès base de données

---

## Sécurité

### Mesures Implémentées
1. **Hachage des mots de passe** : `password_hash()` avec `PASSWORD_DEFAULT`
2. **Protection CSRF** : Vérification des sessions
3. **Validation des entrées** : Filtrage et échappement
4. **Contrôle d'accès** : Middleware de rôles
5. **Requêtes préparées** : PDO avec paramètres bindés
6. **Échappement HTML** : `htmlspecialchars()` dans les vues

### Bonnes Pratiques
- Ne jamais afficher les erreurs PDO en production
- Valider tous les uploads de fichiers
- Vérifier les permissions sur chaque action sensible
- Logger les actions administratives

---

## Notes de Déploiement

### Prérequis Serveur
- PHP >= 8.2
- MySQL >= 5.7 ou MariaDB >= 10.4
- Extensions PHP : pdo, pdo_mysql, gd, mbstring
- Apache avec mod_rewrite ou Nginx

### Configuration Apache
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

### Permissions
- `public/uploads/` : 755 (écriture serveur)
- Autres fichiers : 644
- Répertoires : 755

---

## Maintenance

### Logs
- Logs PHP : Configurer `error_log` dans php.ini
- Logs applicatifs : Utiliser `error_log()` dans le code

### Backup
- Base de données : Export régulier avec `mysqldump`
- Fichiers uploads : Sauvegarde du dossier `public/uploads/`

---

*Documentation générée le 27 novembre 2025*
