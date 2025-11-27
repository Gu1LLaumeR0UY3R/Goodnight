# Documentation PHPDoc - Modèles

Ce document contient la documentation détaillée de tous les modèles avec les annotations PHPDoc.

---

## Model (Classe de Base)

```php
/**
 * Classe abstraite de base pour tous les modèles
 * 
 * Fournit les opérations CRUD de base et la connexion à la base de données
 * Tous les modèles de l'application héritent de cette classe
 * 
 * @package Models
 * @author GlobeNight Team
 */
abstract class Model {
    
    /**
     * Instance de connexion PDO à la base de données
     * @var PDO
     */
    protected $db;
    
    /**
     * Nom de la table en base de données
     * @var string
     */
    protected $table;
    
    /**
     * Nom de la clé primaire de la table
     * @var string
     */
    protected $primaryKey;
    
    /**
     * Constructeur
     * 
     * Initialise la connexion à la base de données via la classe Database
     * 
     * @throws PDOException Si la connexion échoue
     */
    public function __construct() {}
    
    /**
     * Retourne tous les enregistrements de la table
     * 
     * @return array Tableau d'enregistrements (tableau associatif)
     */
    public function getAll() {}
    
    /**
     * Retourne un enregistrement par son ID
     * 
     * @param int $id Identifiant de l'enregistrement
     * @return array|false Enregistrement trouvé ou false si non trouvé
     */
    public function getById($id) {}
    
    /**
     * Crée un nouvel enregistrement
     * 
     * Les clés du tableau $data doivent correspondre aux noms des colonnes
     * 
     * @param array $data Données à insérer (tableau associatif)
     * @return int|false ID du dernier enregistrement inséré ou false en cas d'erreur
     */
    public function create($data) {}
    
    /**
     * Met à jour un enregistrement existant
     * 
     * @param int $id Identifiant de l'enregistrement à mettre à jour
     * @param array $data Nouvelles données (tableau associatif)
     * @return int Nombre de lignes affectées
     */
    public function update($id, $data) {}
    
    /**
     * Supprime un enregistrement
     * 
     * @param int $id Identifiant de l'enregistrement à supprimer
     * @return int Nombre de lignes supprimées
     */
    public function delete($id) {}
}
```

---

## AdminModel

```php
/**
 * Modèle pour la table admin
 * 
 * Gère les comptes administrateurs de l'application
 * 
 * @package Models
 * @author GlobeNight Team
 */
class AdminModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'admin';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_admin';
    
    /**
     * Recherche un administrateur par son email
     * 
     * Utilisé lors de l'authentification
     * 
     * @param string $email Adresse email de l'administrateur
     * @return array|false Enregistrement de l'admin ou false si non trouvé
     */
    public function getAdminByEmail($email) {}
}
```

**Colonnes de la table admin** :
- `id_admin` (INT, PK) : Identifiant unique
- `nom_admin` (VARCHAR) : Nom de famille
- `prenom_admin` (VARCHAR) : Prénom
- `email_admin` (VARCHAR) : Adresse email (unique)
- `mot_de_passe_admin` (VARCHAR) : Mot de passe haché
- `is_admin` (TINYINT) : Indicateur admin (toujours 1)

---

## UserModel

```php
/**
 * Modèle pour la table locataire
 * 
 * Gère les utilisateurs de l'application (locataires et propriétaires)
 * Support des personnes physiques et morales
 * Gestion des rôles via la table de liaison User_role
 * 
 * @package Models
 * @author GlobeNight Team
 */
class UserModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'locataire';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_locataire';
    
    /* ==================== AUTHENTIFICATION ==================== */
    
    /**
     * Recherche un utilisateur par son email
     * 
     * Utilisé lors de l'authentification
     * 
     * @param string $email Adresse email de l'utilisateur
     * @return array|false Enregistrement de l'utilisateur ou false si non trouvé
     */
    public function getUserByEmail($email) {}
    
    /* ==================== GESTION DES RÔLES ==================== */
    
    /**
     * Assigne un rôle à un utilisateur
     * 
     * Crée une entrée dans la table User_role
     * Un utilisateur peut avoir plusieurs rôles
     * 
     * @param int $userId Identifiant de l'utilisateur
     * @param int $roleId Identifiant du rôle (1=Admin, 2=Propriétaire, 3=Locataire)
     * @return int Nombre de lignes insérées
     */
    public function assignRole($userId, $roleId) {}
    
    /**
     * Retire un rôle à un utilisateur
     * 
     * Supprime l'entrée correspondante dans User_role
     * 
     * @param int $userId Identifiant de l'utilisateur
     * @param int $roleId Identifiant du rôle
     * @return int Nombre de lignes supprimées
     */
    public function removeRole($userId, $roleId) {}
    
    /**
     * Retourne tous les rôles d'un utilisateur
     * 
     * Effectue une jointure avec la table Roles
     * 
     * @param int $userId Identifiant de l'utilisateur
     * @return array Tableau des rôles avec toutes leurs informations
     */
    public function getUserRoles($userId) {}
    
    /* ==================== RECHERCHE ET FILTRAGE ==================== */
    
    /**
     * Recherche d'utilisateurs par nom et rôle
     * 
     * Utilisé pour les autocomplete dans les formulaires admin
     * Recherche dans nom_locataire, prenom_locataire et RaisonSociale
     * 
     * @param string $term Terme de recherche
     * @param int|null $id_roles Filtre optionnel par rôle
     * @return array Liste d'utilisateurs correspondants
     */
    public function searchUsersByIdRoleAndName($term, $id_roles = null) {}
    
    /**
     * Retourne les utilisateurs d'un rôle spécifique
     * 
     * Peut filtrer par type de personne (physique/morale)
     * 
     * @param int $roleId Identifiant du rôle
     * @param string|null $type Type de personne : 'physique' ou 'morale' ou null pour tous
     * @return array Liste d'utilisateurs du rôle spécifié
     */
    public function getUsersByRole($roleId, $type = null) {}
    
    /**
     * Retourne tous les utilisateurs avec leurs rôles
     * 
     * Effectue des jointures pour inclure les informations de rôles
     * Utilisé dans l'interface admin pour afficher la liste complète
     * 
     * @return array Liste de tous les utilisateurs avec leurs rôles
     */
    public function getAllUsersWithRoles() {}
}
```

**Colonnes de la table locataire** :
- `id_locataire` (INT, PK) : Identifiant unique
- `nom_locataire` (VARCHAR) : Nom de famille
- `prenom_locataire` (VARCHAR) : Prénom
- `dateNaissance_locataire` (DATE) : Date de naissance (personne physique)
- `email_locataire` (VARCHAR) : Adresse email (unique)
- `password_locataire` (VARCHAR) : Mot de passe haché
- `tel_locataire` (VARCHAR) : Numéro de téléphone (format E.164)
- `rue_locataire` (VARCHAR) : Adresse
- `complement_locataire` (VARCHAR) : Complément d'adresse
- `RaisonSociale` (VARCHAR) : Raison sociale (personne morale)
- `Siret` (VARCHAR) : Numéro SIRET (personne morale)
- `id_commune` (INT, FK) : Référence à la commune
- `pfp_loca` (VARCHAR) : Chemin de la photo de profil

---

## BienModel

```php
/**
 * Modèle pour la table biens
 * 
 * Gère les propriétés/biens immobiliers à louer
 * 
 * @package Models
 * @author GlobeNight Team
 */
class BienModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'biens';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_biens';
    
    /**
     * Retourne tous les biens d'un propriétaire
     * 
     * Filtre par id_locataire (qui est en fait le propriétaire du bien)
     * Inclut les informations de la première photo si disponible
     * 
     * @param int $proprietaireId Identifiant du propriétaire
     * @return array Liste des biens du propriétaire avec leurs informations
     */
    public function getBiensByProprietaire($proprietaireId) {}
    
    /**
     * Recherche de biens selon des critères multiples
     * 
     * Critères possibles :
     * - commune : Recherche par commune
     * - date_debut/date_fin : Vérification de disponibilité
     * - nb_personnes : Capacité minimale
     * - animaux : Acceptation des animaux
     * - id_TypeBien : Type de bien
     * 
     * @param array $criteria Tableau associatif des critères de recherche
     * @return array Liste des biens correspondant aux critères
     */
    public function search($criteria) {}
    
    /**
     * Retourne un bien avec toutes ses informations détaillées
     * 
     * Inclut :
     * - Informations du bien
     * - Type de bien
     * - Commune
     * - Propriétaire
     * - Photos
     * - Tarifs
     * 
     * @param int $id Identifiant du bien
     * @return array|false Informations complètes du bien ou false si non trouvé
     */
    public function getBienWithDetails($id) {}
}
```

**Colonnes de la table biens** :
- `id_biens` (INT, PK) : Identifiant unique
- `designation_bien` (VARCHAR) : Nom du bien
- `rue_biens` (VARCHAR) : Adresse - rue
- `complement_biens` (VARCHAR) : Complément d'adresse
- `superficie_biens` (DECIMAL) : Superficie en m²
- `description_biens` (TEXT) : Description détaillée
- `animaux_biens` (TINYINT) : Accepte les animaux (0/1)
- `nb_couchage` (INT) : Nombre de couchages
- `id_TypeBien` (INT, FK) : Type de bien
- `id_commune` (INT, FK) : Commune
- `id_locataire` (INT, FK) : Propriétaire du bien

---

## PhotoModel

```php
/**
 * Modèle pour la table Photos
 * 
 * Gère les photos des biens immobiliers
 * 
 * @package Models
 * @author GlobeNight Team
 */
class PhotoModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'Photos';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_photo';
    
    /**
     * Retourne toutes les photos d'un bien
     * 
     * @param int $bienId Identifiant du bien
     * @return array Liste des photos du bien
     */
    public function getPhotosByBien($bienId) {}
}
```

**Colonnes de la table Photos** :
- `id_photo` (INT, PK) : Identifiant unique
- `nom_photo` (VARCHAR) : Nom original du fichier
- `lien_photo` (VARCHAR) : URL/chemin d'accès à la photo
- `id_biens` (INT, FK) : Bien associé

---

## ReservationModel

```php
/**
 * Modèle pour la table reservation
 * 
 * Gère les réservations de biens par les locataires
 * 
 * @package Models
 * @author GlobeNight Team
 */
class ReservationModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'reservation';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_reservation';
    
    /**
     * Retourne les réservations d'un locataire
     * 
     * Inclut les informations des biens réservés
     * 
     * @param int $locataireId Identifiant du locataire
     * @return array Liste des réservations du locataire
     */
    public function getReservationsByLocataire($locataireId) {}
    
    /**
     * Retourne les réservations des biens d'un propriétaire
     * 
     * Effectue des jointures pour obtenir :
     * - Informations du bien
     * - Informations du locataire
     * - Dates de réservation
     * 
     * @param int $proprietaireId Identifiant du propriétaire
     * @return array Liste des réservations pour les biens du propriétaire
     */
    public function getReservationsByProprietaire($proprietaireId) {}
    
    /**
     * Retourne les réservations d'un bien spécifique
     * 
     * Utilisé pour vérifier les disponibilités
     * 
     * @param int $bienId Identifiant du bien
     * @return array Liste des réservations du bien
     */
    public function getReservationsByBien($bienId) {}
}
```

**Colonnes de la table reservation** :
- `id_reservation` (INT, PK) : Identifiant unique
- `date_debut` (DATE) : Date de début de la réservation
- `date_fin` (DATE) : Date de fin de la réservation
- `id_biens` (INT, FK) : Bien réservé
- `id_locataire` (INT, FK) : Locataire ayant réservé
- `id_tarif` (INT, FK) : Tarif appliqué

---

## BlocageModel

```php
/**
 * Modèle pour la table blocages
 * 
 * Gère les périodes d'indisponibilité définies par les propriétaires
 * Les blocages empêchent les réservations sur les dates spécifiées
 * 
 * @package Models
 * @author GlobeNight Team
 */
class BlocageModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'blocages';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_blocage';
    
    /**
     * Retourne les blocages des biens d'un propriétaire
     * 
     * Effectue une jointure avec la table biens pour filtrer par propriétaire
     * 
     * @param int $proprietaireId Identifiant du propriétaire
     * @return array Liste des blocages des biens du propriétaire
     */
    public function getBlocagesByProprietaire($proprietaireId) {}
    
    /**
     * Retourne les blocages d'un bien spécifique
     * 
     * @param int $bienId Identifiant du bien
     * @return array Liste des blocages du bien
     */
    public function getBlocagesByBien($bienId) {}
    
    /**
     * Vérifie les chevauchements avec des réservations existantes
     * 
     * Utilisé avant de créer un blocage pour éviter les conflits
     * Retourne les réservations qui chevauchent la période demandée
     * 
     * Un chevauchement est considéré comme problématique si :
     * - Les dates se chevauchent sur plus d'un jour
     * - (Un chevauchement d'un seul jour est toléré)
     * 
     * @param int $bienId Identifiant du bien
     * @param string $dateDebut Date de début du blocage (format Y-m-d)
     * @param string $dateFin Date de fin du blocage (format Y-m-d)
     * @return array Liste des réservations en conflit
     */
    public function checkOverlapWithReservations($bienId, $dateDebut, $dateFin) {}
}
```

**Colonnes de la table blocages** :
- `id_blocage` (INT, PK) : Identifiant unique
- `date_debut` (DATE) : Date de début du blocage
- `date_fin` (DATE) : Date de fin du blocage
- `motif` (TEXT) : Raison du blocage
- `id_biens` (INT, FK) : Bien concerné

---

## TarifModel

```php
/**
 * Modèle pour la table tarif
 * 
 * Gère les prix des biens par saison et par année
 * Un bien peut avoir plusieurs tarifs (un par saison et par année)
 * 
 * @package Models
 * @author GlobeNight Team
 */
class TarifModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'tarif';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_tarif';
    
    /**
     * Retourne tous les tarifs d'un bien
     * 
     * Inclut les informations de la saison associée
     * 
     * @param int $bienId Identifiant du bien
     * @return array Liste des tarifs du bien avec les libellés de saisons
     */
    public function getTarifsByBien($bienId) {}
    
    /**
     * Retourne un tarif spécifique
     * 
     * Recherche un tarif pour un bien, une saison et une année donnés
     * Utilisé pour vérifier l'existence d'un tarif avant création ou mise à jour
     * 
     * @param int $bienId Identifiant du bien
     * @param int $saisonId Identifiant de la saison
     * @param int $annee Année du tarif
     * @return array|false Tarif trouvé ou false si inexistant
     */
    public function getTarifByBienSaisonAnnee($bienId, $saisonId, $annee) {}
}
```

**Colonnes de la table tarif** :
- `id_tarif` (INT, PK) : Identifiant unique
- `prix_semaine` (DECIMAL) : Prix par semaine en euros
- `annee` (INT) : Année du tarif
- `id_biens` (INT, FK) : Bien concerné
- `id_saison` (INT, FK) : Saison tarifaire

---

## SaisonModel

```php
/**
 * Modèle pour la table saison
 * 
 * Gère les saisons tarifaires (Haute saison, Basse saison, etc.)
 * 
 * @package Models
 * @author GlobeNight Team
 */
class SaisonModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'saison';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_saison';
}
```

**Colonnes de la table saison** :
- `id_saison` (INT, PK) : Identifiant unique
- `lib_saison` (VARCHAR) : Libellé de la saison (ex: "Haute saison")
- `date_debut` (DATE) : Date de début de la saison
- `date_fin` (DATE) : Date de fin de la saison

---

## TypeBienModel

```php
/**
 * Modèle pour la table typebien
 * 
 * Gère les types de biens (Appartement, Maison, Villa, etc.)
 * 
 * @package Models
 * @author GlobeNight Team
 */
class TypeBienModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'typebien';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_typebien';
    
    /**
     * Retourne tous les types de biens ordonnés
     * 
     * Tri par description de type de bien
     * 
     * @return array Liste des types de biens triés alphabétiquement
     */
    public function getAllTypesBiens() {}
}
```

**Colonnes de la table typebien** :
- `id_typebien` (INT, PK) : Identifiant unique
- `desc_type_bien` (VARCHAR) : Description du type (ex: "Appartement")

---

## RoleModel

```php
/**
 * Modèle pour la table roles
 * 
 * Gère les rôles utilisateurs de l'application
 * 
 * @package Models
 * @author GlobeNight Team
 */
class RoleModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'roles';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_roles';
}
```

**Colonnes de la table roles** :
- `id_roles` (INT, PK) : Identifiant unique
- `nom_roles` (VARCHAR) : Nom du rôle

**Rôles standards** :
- ID 1 : Administrateur
- ID 2 : Propriétaire  
- ID 3 : Locataire

---

## CommuneModel

```php
/**
 * Modèle pour la table commune
 * 
 * Gère les villes et codes postaux
 * 
 * @package Models
 * @author GlobeNight Team
 */
class CommuneModel extends Model {
    
    /**
     * Nom de la table
     * @var string
     */
    protected $table = 'commune';
    
    /**
     * Clé primaire
     * @var string
     */
    protected $primaryKey = 'id_commune';
    
    /**
     * Recherche de communes par nom ou code postal
     * 
     * Utilisé pour l'autocomplete dans les formulaires
     * Recherche dans ville_nom et ville_code_postal
     * 
     * @param string $term Terme de recherche
     * @return array Liste des communes correspondantes
     */
    public function search($term) {}
}
```

**Colonnes de la table commune** :
- `id_commune` (INT, PK) : Identifiant unique
- `ville_nom` (VARCHAR) : Nom de la ville
- `ville_code_postal` (VARCHAR) : Code postal

---

## Relations entre Tables

### Diagramme des Relations Principales

```
┌────────────┐         ┌────────────┐
│  locataire │◄───────┤ User_role  │
└────────────┘         └────────────┘
      │                      │
      │                      ▼
      │                ┌────────────┐
      │                │   roles    │
      │                └────────────┘
      │
      │ (propriétaire)
      ▼
┌────────────┐
│   biens    │
└────────────┘
      │
      ├─────► Photos (1:N)
      │
      ├─────► tarif (1:N) ───► saison
      │
      ├─────► blocages (1:N)
      │
      └─────► reservation (1:N)
               │
               └───► locataire (client)
```

### Cardinalités

- **locataire ↔ User_role** : Many-to-Many (un utilisateur peut avoir plusieurs rôles)
- **locataire → biens** : One-to-Many (un propriétaire peut avoir plusieurs biens)
- **biens → Photos** : One-to-Many (un bien peut avoir plusieurs photos)
- **biens → tarif** : One-to-Many (un bien peut avoir plusieurs tarifs)
- **biens → blocages** : One-to-Many (un bien peut avoir plusieurs blocages)
- **biens → reservation** : One-to-Many (un bien peut avoir plusieurs réservations)
- **locataire → reservation** : One-to-Many (un locataire peut avoir plusieurs réservations)

---

## Bonnes Pratiques d'Utilisation

### Transactions

Pour des opérations complexes impliquant plusieurs tables :

```php
try {
    $this->db->beginTransaction();
    
    // Opérations multiples
    $bienId = $this->bienModel->create($dataBien);
    $this->tarifModel->create($dataTarif);
    $this->photoModel->create($dataPhoto);
    
    $this->db->commit();
} catch (Exception $e) {
    $this->db->rollback();
    throw $e;
}
```

### Requêtes Préparées

Toutes les requêtes utilisent PDO avec des paramètres bindés :

```php
$stmt = $this->db->prepare("SELECT * FROM table WHERE id = :id");
$stmt->execute(['id' => $id]);
```

### Gestion des Erreurs

Les modèles peuvent lever des exceptions PDOException.
Gérer les erreurs dans les contrôleurs :

```php
try {
    $result = $this->model->create($data);
} catch (PDOException $e) {
    error_log("Erreur BD: " . $e->getMessage());
    $_SESSION['error'] = "Une erreur est survenue";
}
```

---

*Documentation PHPDoc Modèles générée le 27 novembre 2025*
