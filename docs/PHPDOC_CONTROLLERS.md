# Documentation PHPDoc - Contrôleurs

Ce document contient la documentation détaillée de toutes les méthodes des contrôleurs avec les annotations PHPDoc à ajouter.

---

## BaseController

```php
/**
 * Contrôleur de base dont héritent tous les autres contrôleurs
 * 
 * Fournit les méthodes communes de rendu de vue et de redirection
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class BaseController {
    
    /**
     * Rend une vue avec les données fournies
     * 
     * Extrait les données du tableau associatif pour les rendre disponibles
     * comme variables dans la vue PHP
     * 
     * @param string $view Chemin de la vue relative au dossier Views (ex: "admin/index")
     * @param array $data Données à passer à la vue sous forme de tableau associatif
     * @param array $cssFiles Liste des fichiers CSS à inclure
     * @return void
     */
    protected function render($view, $data = [], $cssFiles = []) {}
    
    /**
     * Redirige vers une URL spécifiée
     * 
     * Utilise un header HTTP 302 et termine l'exécution du script
     * 
     * @param string $url URL de destination (relative ou absolue)
     * @return void
     */
    protected function redirect($url) {}
}
```

---

## AdminController

```php
/**
 * Contrôleur de l'interface d'administration
 * 
 * Gère toutes les fonctionnalités administratives :
 * - Gestion des utilisateurs et admins
 * - Gestion des biens et types de biens
 * - Gestion des rôles et permissions
 * - Gestion des saisons et tarifs
 * - Gestion des communes
 * - Gestion des réservations
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class AdminController extends BaseController {
    
    /**
     * Affiche le tableau de bord administrateur
     * 
     * Page d'accueil de l'administration avec liens vers toutes
     * les sections de gestion
     * 
     * @return void
     */
    public function index() {}
    
    /* ==================== GESTION DES ADMINS ==================== */
    
    /**
     * Liste tous les administrateurs
     * 
     * Affiche une table DataTable avec tous les comptes administrateurs
     * 
     * @return void
     */
    public function admins() {}
    
    /**
     * Ajoute un nouvel administrateur
     * 
     * GET : Affiche le formulaire d'ajout
     * POST : Traite les données et crée le compte admin
     * 
     * @return void
     */
    public function addAdmin() {}
    
    /**
     * Modifie un administrateur existant
     * 
     * GET : Affiche le formulaire pré-rempli
     * POST : Met à jour les informations de l'admin
     * 
     * @param int $id Identifiant de l'administrateur
     * @return void
     */
    public function editAdmin($id) {}
    
    /**
     * Supprime un administrateur
     * 
     * Supprime définitivement un compte administrateur
     * 
     * @param int $id Identifiant de l'administrateur
     * @return void
     */
    public function deleteAdmin($id) {}
    
    /* ==================== GESTION DES RÔLES ==================== */
    
    /**
     * Liste tous les rôles
     * 
     * Affiche tous les rôles disponibles (Administrateur, Propriétaire, Locataire)
     * 
     * @return void
     */
    public function roles() {}
    
    /**
     * Ajoute un nouveau rôle
     * 
     * GET : Affiche le formulaire d'ajout
     * POST : Crée un nouveau rôle
     * 
     * @return void
     */
    public function addRole() {}
    
    /**
     * Modifie un rôle existant
     * 
     * GET : Affiche le formulaire pré-rempli
     * POST : Met à jour le rôle
     * 
     * @param int $id Identifiant du rôle
     * @return void
     */
    public function editRole($id) {}
    
    /**
     * Supprime un rôle
     * 
     * @param int $id Identifiant du rôle
     * @return void
     */
    public function deleteRole($id) {}
    
    /* ==================== GESTION DES COMMUNES ==================== */
    
    /**
     * Liste toutes les communes
     * 
     * Affiche une table DataTable avec toutes les communes
     * 
     * @return void
     */
    public function communes() {}
    
    /* ==================== GESTION DES TYPES DE BIENS ==================== */
    
    /**
     * Liste tous les types de biens
     * 
     * Affiche les types (Appartement, Maison, Villa, etc.)
     * 
     * @return void
     */
    public function typesBiens() {}
    
    /**
     * Ajoute un nouveau type de bien
     * 
     * GET : Affiche le formulaire
     * POST : Crée le type de bien
     * 
     * @return void
     */
    public function addTypeBien() {}
    
    /**
     * Modifie un type de bien existant
     * 
     * @param int $id Identifiant du type de bien
     * @return void
     */
    public function editTypeBien($id) {}
    
    /**
     * Supprime un type de bien
     * 
     * @param int $id Identifiant du type de bien
     * @return void
     */
    public function deleteTypeBien($id) {}
    
    /* ==================== GESTION DES SAISONS ==================== */
    
    /**
     * Liste toutes les saisons tarifaires
     * 
     * Affiche les saisons (Haute, Basse, etc.) avec dates
     * 
     * @return void
     */
    public function saisons() {}
    
    /**
     * Ajoute une nouvelle saison
     * 
     * GET : Affiche le formulaire
     * POST : Crée la saison avec dates
     * 
     * @return void
     */
    public function addSaison() {}
    
    /**
     * Modifie une saison existante
     * 
     * @param int $id Identifiant de la saison
     * @return void
     */
    public function editSaison($id) {}
    
    /**
     * Supprime une saison
     * 
     * @param int $id Identifiant de la saison
     * @return void
     */
    public function deleteSaison($id) {}
    
    /* ==================== GESTION DES BIENS ==================== */
    
    /**
     * Liste tous les biens de l'application
     * 
     * Affiche une table DataTable avec tous les biens de tous les propriétaires
     * 
     * @return void
     */
    public function biens() {}
    
    /**
     * Ajoute un nouveau bien
     * 
     * GET : Affiche le formulaire avec upload de photos et tarifs
     * POST : Crée le bien avec ses photos et tarifs
     * 
     * Fonctionnalités :
     * - Upload multiple de photos
     * - Définition des tarifs par saison
     * - Sélection du propriétaire
     * 
     * @return void
     */
    public function addBien() {}
    
    /**
     * Modifie un bien existant
     * 
     * GET : Affiche le formulaire pré-rempli avec photos actuelles
     * POST : Met à jour le bien, ses tarifs et ajoute de nouvelles photos si nécessaire
     * 
     * Fonctionnalités :
     * - Modification des informations du bien
     * - Gestion des tarifs (création/mise à jour)
     * - Upload de nouvelles photos
     * - Suppression de photos existantes
     * 
     * @param int $id Identifiant du bien
     * @return void
     */
    public function editBien($id) {}
    
    /**
     * Supprime un bien
     * 
     * Supprime le bien et toutes ses données associées
     * 
     * @param int $id Identifiant du bien
     * @return void
     */
    public function deleteBien($id) {}
    
    /* ==================== GESTION DES UTILISATEURS ==================== */
    
    /**
     * Liste tous les utilisateurs
     * 
     * Affiche une table DataTable avec tous les utilisateurs et leurs rôles
     * 
     * @return void
     */
    public function users() {}
    
    /**
     * Ajoute un nouvel utilisateur
     * 
     * GET : Affiche le formulaire
     * POST : Crée l'utilisateur avec attribution de rôles
     * 
     * Support :
     * - Personne physique
     * - Personne morale (avec SIRET)
     * - Attribution de rôles (Propriétaire/Locataire)
     * 
     * @return void
     */
    public function addUser() {}
    
    /**
     * Modifie un utilisateur existant
     * 
     * GET : Affiche le formulaire pré-rempli
     * POST : Met à jour les informations
     * 
     * Fonctionnalités :
     * - Validation téléphone E.164
     * - Gestion des rôles
     * - Autocomplete propriétaire
     * 
     * @param int $id Identifiant de l'utilisateur
     * @return void
     */
    public function editUser($id) {}
    
    /**
     * Supprime un utilisateur
     * 
     * @param int $id Identifiant de l'utilisateur
     * @return void
     */
    public function deleteUser($id) {}
    
    /* ==================== GESTION DES RÉSERVATIONS ==================== */
    
    /**
     * Liste toutes les réservations
     * 
     * Affiche une table DataTable avec toutes les réservations
     * 
     * @return void
     */
    public function reservations() {}
    
    /**
     * Ajoute une nouvelle réservation
     * 
     * GET : Affiche le formulaire
     * POST : Crée la réservation
     * 
     * @return void
     */
    public function addReservation() {}
    
    /**
     * Modifie une réservation existante
     * 
     * @param int $id Identifiant de la réservation
     * @return void
     */
    public function editReservation($id) {}
    
    /**
     * Supprime une réservation
     * 
     * @param int $id Identifiant de la réservation
     * @return void
     */
    public function deleteReservation($id) {}
    
    /* ==================== GESTION DES PHOTOS ==================== */
    
    /**
     * Gère l'upload de photos pour un bien
     * 
     * Traite l'upload multiple de fichiers, génère des noms uniques
     * et enregistre les photos en base de données
     * 
     * @param int $bienId Identifiant du bien
     * @param array $files Tableau $_FILES['photos'] contenant les fichiers uploadés
     * @return void
     */
    private function handlePhotoUpload($bienId, $files) {}
    
    /**
     * Supprime une photo
     * 
     * Supprime le fichier physique et l'entrée en base de données
     * Redirige vers le formulaire d'édition du bien
     * 
     * @param int $photoId Identifiant de la photo
     * @return void
     */
    public function deletePhoto($photoId) {}
    
    /* ==================== API ENDPOINTS ==================== */
    
    /**
     * API : Recherche d'utilisateurs pour autocomplete
     * 
     * Retourne un JSON avec les utilisateurs correspondant au terme de recherche
     * Filtre optionnel par rôle
     * 
     * @return void (output JSON)
     */
    public function searchUsers() {}
    
    /**
     * API : Recherche de communes pour autocomplete
     * 
     * Retourne un JSON avec les communes correspondant au terme
     * 
     * @return void (output JSON)
     */
    public function searchCommunes() {}
}
```

---

## ProprietaireController

```php
/**
 * Contrôleur de l'espace propriétaire
 * 
 * Gère toutes les fonctionnalités pour les propriétaires :
 * - Dashboard avec roue 3D interactive
 * - Gestion de leurs biens
 * - Gestion des photos et tarifs
 * - Consultation des réservations
 * - Gestion des blocages de dates
 * - Calendrier FullCalendar
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class ProprietaireController extends BaseController {
    
    /**
     * Constructeur
     * 
     * Vérifie que l'utilisateur a le rôle "Propriétaire"
     * Initialise les modèles nécessaires
     * 
     * @throws Exception Si l'utilisateur n'a pas le rôle requis
     */
    public function __construct() {}
    
    /**
     * Affiche le tableau de bord propriétaire
     * 
     * Dashboard avec :
     * - Roue 3D affichant les biens en rotation
     * - Filtres par type de bien
     * - Boutons de sélection rapide
     * - Cartes des biens avec animations
     * - Particules animées en arrière-plan
     * 
     * @return void
     */
    public function index() {}
    
    /**
     * Liste les biens du propriétaire connecté
     * 
     * Affiche une grille avec tous les biens appartenant au propriétaire
     * 
     * @return void
     */
    public function myBiens() {}
    
    /**
     * Ajoute un nouveau bien
     * 
     * GET : Affiche le formulaire avec sections :
     *   - Informations générales
     *   - Localisation (avec autocomplete commune)
     *   - Description
     *   - Tarification par saison
     *   - Upload de photos (drag & drop)
     * 
     * POST : Crée le bien et :
     *   - Upload et enregistre les photos
     *   - Crée les tarifs pour chaque saison
     *   - Redirige vers la liste des biens
     * 
     * @return void
     */
    public function addBien() {}
    
    /**
     * Modifie un bien existant
     * 
     * Vérifie que le bien appartient au propriétaire connecté
     * 
     * GET : Affiche le formulaire pré-rempli avec :
     *   - Informations actuelles du bien
     *   - Photos existantes (avec option suppression)
     *   - Tarifs actuels pré-remplis
     *   - Possibilité d'ajouter de nouvelles photos
     * 
     * POST : Met à jour le bien et :
     *   - Modifie les informations
     *   - Crée ou met à jour les tarifs
     *   - Ajoute de nouvelles photos si fournies
     * 
     * @param int $id Identifiant du bien
     * @return void
     */
    public function editBien($id) {}
    
    /**
     * Supprime un bien
     * 
     * Vérifie que le bien appartient au propriétaire connecté
     * Supprime le bien et toutes ses données associées
     * 
     * @param int $id Identifiant du bien
     * @return void
     */
    public function deleteBien($id) {}
    
    /**
     * Supprime une photo d'un bien
     * 
     * Vérifie que le bien associé appartient au propriétaire connecté
     * Supprime le fichier physique et l'entrée en base de données
     * 
     * @param int $photoId Identifiant de la photo
     * @return void
     */
    public function deletePhoto($photoId) {}
    
    /**
     * Liste les réservations des biens du propriétaire
     * 
     * Affiche toutes les réservations pour tous les biens du propriétaire
     * 
     * @return void
     */
    public function myReservations() {}
    
    /* ==================== CALENDRIER ET BLOCAGES ==================== */
    
    /**
     * API : Retourne les événements du calendrier
     * 
     * Endpoint JSON pour FullCalendar
     * Retourne :
     * - Réservations (couleur verte)
     * - Blocages (couleur orange)
     * 
     * Format de sortie FullCalendar avec dates exclusives de fin
     * 
     * @return void (output JSON)
     */
    public function calendarEvents() {}
    
    /**
     * Crée un blocage de dates
     * 
     * POST : Reçoit date_debut, date_fin, motif, id_biens
     * 
     * Validations :
     * - Vérifie que le bien appartient au propriétaire
     * - Vérifie qu'il n'y a pas de chevauchement avec des réservations existantes
     *   (sauf si chevauchement d'un seul jour)
     * 
     * Retourne JSON :
     * - success: true si création réussie
     * - error: message d'erreur si échec
     * 
     * @return void (output JSON)
     */
    public function calendarBlock() {}
    
    /**
     * Supprime un blocage
     * 
     * POST : Reçoit l'id du blocage
     * 
     * Vérifie que le bien associé appartient au propriétaire
     * Supprime le blocage de la base de données
     * 
     * Retourne JSON :
     * - success: true si suppression réussie
     * - error: message d'erreur si échec
     * 
     * @return void (output JSON)
     */
    public function calendarUnblock() {}
    
    /**
     * Gère l'upload de photos pour un bien
     * 
     * Vérifie et crée le dossier d'upload si nécessaire
     * Pour chaque fichier :
     * - Génère un nom unique avec uniqid()
     * - Déplace le fichier dans le dossier uploads
     * - Enregistre les informations en base de données
     * 
     * @param int $bienId Identifiant du bien
     * @param array $files Tableau $_FILES['photos'] contenant les fichiers
     * @return void
     */
    private function handlePhotoUpload($bienId, $files) {}
}
```

---

## LoginController

```php
/**
 * Contrôleur de gestion de l'authentification
 * 
 * Gère :
 * - Connexion des utilisateurs et administrateurs
 * - Déconnexion
 * - Réinitialisation de mot de passe
 * - Redirections selon les rôles
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class LoginController extends BaseController {
    
    /**
     * Affiche le formulaire de connexion
     * 
     * Redirige automatiquement si l'utilisateur est déjà connecté
     * 
     * @return void
     */
    public function index() {}
    
    /**
     * Traite la connexion
     * 
     * POST : Reçoit email et password
     * 
     * Processus :
     * 1. Vérifie d'abord si c'est un administrateur
     * 2. Sinon vérifie si c'est un utilisateur normal
     * 3. Vérifie le mot de passe avec password_verify()
     * 4. Initialise la session avec toutes les informations
     * 5. Récupère les rôles de l'utilisateur
     * 6. Redirige selon le rôle principal
     * 
     * Variables de session créées :
     * - user_id : ID de l'utilisateur
     * - user_email : Email
     * - user_nom : Nom
     * - user_prenom : Prénom
     * - user_roles : Array de tous les rôles
     * - role : Rôle principal (premier dans la liste)
     * - is_admin : Boolean (true pour admin)
     * - user_pfp : Photo de profil (si existe)
     * 
     * @return void
     */
    public function login() {}
    
    /**
     * Déconnecte l'utilisateur
     * 
     * Détruit la session et redirige vers la page d'accueil
     * 
     * @return void
     */
    public function logout() {}
    
    /**
     * Affiche le formulaire de réinitialisation de mot de passe
     * 
     * @return void
     */
    public function showResetForm() {}
    
    /**
     * Traite la demande de réinitialisation de mot de passe
     * 
     * POST : Reçoit l'email
     * 
     * Génère un token de réinitialisation et envoie un email
     * (À implémenter)
     * 
     * @return void
     */
    public function requestPasswordReset() {}
    
    /**
     * Met à jour le mot de passe
     * 
     * POST : Reçoit token, nouveau mot de passe
     * 
     * Vérifie le token et met à jour le mot de passe
     * (À implémenter)
     * 
     * @return void
     */
    public function updatePassword() {}
    
    /**
     * Redirige l'utilisateur selon son rôle principal
     * 
     * Règles de redirection :
     * - Administrateur → /admin
     * - Propriétaire → /proprietaire
     * - Locataire → /locataire
     * - Par défaut → /home
     * 
     * @return void
     */
    private function redirectByRole() {}
}
```

---

## RegisterController

```php
/**
 * Contrôleur de gestion des inscriptions
 * 
 * Gère l'inscription de nouveaux utilisateurs :
 * - Personnes physiques
 * - Personnes morales (entreprises)
 * - Attribution des rôles (Propriétaire/Locataire)
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class RegisterController extends BaseController {
    
    /**
     * Affiche le formulaire d'inscription
     * 
     * Formulaire avec :
     * - Choix type de compte (physique/morale)
     * - Champs conditionnels selon le type
     * - Validation téléphone internationale (intl-tel-input)
     * - Autocomplete sur les communes
     * - Choix du rôle (propriétaire/locataire)
     * 
     * @return void
     */
    public function index() {}
    
    /**
     * Traite l'inscription d'un nouvel utilisateur
     * 
     * POST : Reçoit toutes les données du formulaire
     * 
     * Validations :
     * - Numéro de téléphone au format E.164 (si fourni)
     * - SIRET : exactement 14 chiffres (pour personnes morales)
     * - Vérification unicité de l'email
     * - Correspondance des mots de passe
     * 
     * Processus :
     * 1. Valide les données
     * 2. Hache le mot de passe avec password_hash()
     * 3. Crée l'utilisateur en base
     * 4. Assigne le rôle choisi :
     *    - role_choice = "proprietaire" → Rôle ID 2
     *    - role_choice = "locataire" → Rôle ID 3
     * 5. Redirige vers /login avec message de succès
     * 
     * Champs traités :
     * - Communs : nom, prénom, email, password, téléphone, adresse, commune
     * - Personne physique : date_naissance
     * - Personne morale : raison_sociale, siret
     * 
     * @return void
     */
    public function register() {}
}
```

---

## HomeController

```php
/**
 * Contrôleur de la page d'accueil publique
 * 
 * Gère :
 * - Affichage de la page d'accueil
 * - Recherche de biens disponibles
 * - Détails des biens
 * - Autocomplete des communes
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class HomeController extends BaseController {
    
    /**
     * Affiche la page d'accueil
     * 
     * Page avec :
     * - Formulaire de recherche
     * - Liste des biens disponibles
     * - Filtres (commune, dates, capacité, animaux)
     * 
     * @return void
     */
    public function index() {}
    
    /**
     * Recherche de biens selon des critères
     * 
     * POST : Reçoit les critères de recherche
     * 
     * Critères possibles :
     * - commune : ID de la commune
     * - date_debut : Date de début de séjour
     * - date_fin : Date de fin de séjour
     * - nb_personnes : Nombre de personnes
     * - animaux : Accepte les animaux (1/0)
     * - id_TypeBien : Type de bien
     * 
     * @return void
     */
    public function search() {}
    
    /**
     * Affiche les détails d'un bien
     * 
     * Page avec :
     * - Informations complètes du bien
     * - Galerie de photos
     * - Calendrier des disponibilités
     * - Formulaire de réservation
     * - Tarifs par saison
     * 
     * @param int $id Identifiant du bien
     * @return void
     */
    public function details($id) {}
    
    /**
     * API : Autocomplete pour les communes
     * 
     * Retourne un JSON avec les communes correspondant au terme
     * Utilisé par jQuery UI Autocomplete
     * 
     * @return void (output JSON)
     */
    public function autocompleteCommunes() {}
}
```

---

## ReservationController

```php
/**
 * Contrôleur de gestion des réservations
 * 
 * Gère :
 * - Création de réservations
 * - Consultation des réservations
 * - Annulation de réservations
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class ReservationController extends BaseController {
    
    /**
     * Crée une nouvelle réservation
     * 
     * POST : Reçoit les données de réservation
     * 
     * Données :
     * - id_biens : ID du bien à réserver
     * - date_debut : Date de début
     * - date_fin : Date de fin
     * - id_tarif : ID du tarif applicable
     * 
     * Validations :
     * - Vérifie la disponibilité du bien
     * - Vérifie qu'il n'y a pas de chevauchement avec d'autres réservations
     * - Vérifie qu'il n'y a pas de blocage sur ces dates
     * 
     * @return void
     */
    public function store() {}
    
    /**
     * Liste les réservations du locataire connecté
     * 
     * Affiche toutes les réservations de l'utilisateur
     * avec possibilité d'annulation
     * 
     * @return void
     */
    public function myReservations() {}
    
    /**
     * Annule une réservation
     * 
     * Vérifie que la réservation appartient au locataire connecté
     * Supprime la réservation de la base de données
     * 
     * @param int $id Identifiant de la réservation
     * @return void
     */
    public function cancel($id) {}
}
```

---

## LocataireController

```php
/**
 * Contrôleur de l'espace locataire
 * 
 * Gère le tableau de bord du locataire
 * Les fonctionnalités de réservation sont gérées par ReservationController
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class LocataireController extends BaseController {
    
    /**
     * Affiche le tableau de bord locataire
     * 
     * Dashboard avec :
     * - Résumé des réservations en cours
     * - Réservations à venir
     * - Historique des réservations
     * 
     * @return void
     */
    public function index() {}
}
```

---

## ProfileController

```php
/**
 * Contrôleur de gestion du profil utilisateur
 * 
 * Gère :
 * - Affichage du profil
 * - Modification des informations
 * - Gestion de la photo de profil
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class ProfileController extends BaseController {
    
    /**
     * Affiche le profil de l'utilisateur connecté
     * 
     * Page avec :
     * - Informations personnelles
     * - Photo de profil
     * - Possibilité de modification
     * 
     * @return void
     */
    public function index() {}
    
    /**
     * Upload d'une photo de profil
     * 
     * POST : Reçoit le fichier image
     * 
     * Traitement :
     * - Valide le type de fichier (image)
     * - Redimensionne si nécessaire
     * - Génère un nom unique
     * - Enregistre dans le dossier uploads
     * - Met à jour la base de données
     * 
     * @return void
     */
    public function uploadProfilePicture() {}
    
    /**
     * Supprime la photo de profil
     * 
     * Supprime le fichier physique et met à jour la base
     * Rétablit l'image par défaut
     * 
     * @return void
     */
    public function deleteProfilePicture() {}
}
```

---

## AuthMiddleware

```php
/**
 * Middleware de gestion de l'authentification et des autorisations
 * 
 * Fournit des méthodes statiques pour :
 * - Vérifier la connexion
 * - Contrôler les rôles et permissions
 * - Protéger les routes
 * 
 * @package Controllers
 * @author GlobeNight Team
 */
class AuthMiddleware {
    
    /**
     * Démarre la session si elle n'est pas déjà active
     * 
     * Méthode utilitaire privée utilisée par les autres méthodes
     * 
     * @return void
     */
    private static function ensureSession() {}
    
    /**
     * Vérifie que l'utilisateur est connecté
     * 
     * Redirige vers /login si l'utilisateur n'est pas authentifié
     * Vérifie la présence de $_SESSION['user_id']
     * 
     * Utilisation :
     * ```php
     * AuthMiddleware::requireLogin();
     * ```
     * 
     * @return void
     */
    public static function requireLogin() {}
    
    /**
     * Vérifie que l'utilisateur a un rôle spécifique
     * 
     * Utilise $_SESSION['role'] (rôle principal)
     * Les administrateurs ont accès à toutes les sections
     * 
     * Redirige vers /home avec message d'erreur si accès refusé
     * 
     * @param string|array $roles Rôle(s) autorisé(s) (ex: "Propriétaire" ou ["Propriétaire", "Locataire"])
     * @return void
     */
    public static function checkUserRole($roles) {}
    
    /**
     * Vérifie que l'utilisateur possède l'un des rôles requis
     * 
     * Vérifie dans $_SESSION['user_roles'] (tous les rôles)
     * Supporte les utilisateurs multi-rôles
     * Les administrateurs ont accès à tout
     * 
     * Affiche une erreur 403 si accès refusé
     * En mode debug, affiche les rôles requis et les rôles de l'utilisateur
     * 
     * Utilisation typique dans un constructeur :
     * ```php
     * public function __construct() {
     *     AuthMiddleware::requireRole("Propriétaire");
     *     // ...
     * }
     * ```
     * 
     * @param string|array $roles Rôle(s) requis
     * @return void
     * @throws Exception Affiche 403 Forbidden si l'utilisateur n'a pas les permissions
     */
    public static function requireRole($roles) {}
}
```

---

*Documentation PHPDoc générée le 27 novembre 2025*
