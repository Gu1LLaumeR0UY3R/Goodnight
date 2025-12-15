# Architecture du site Goodnight

## Vue d'ensemble
Goodnight est une plateforme de location de biens immobiliers développée en PHP suivant le pattern architectural **MVC (Model-View-Controller)**.

---

## 📁 Structure du projet

```
Goodnight/
├── app/                          # Cœur de l'application
│   ├── Controllers/              # Contrôleurs (logique métier)
│   ├── Models/                   # Modèles (accès aux données)
│   └── Views/                    # Vues (interface utilisateur)
├── config/                       # Configuration
├── lib/                          # Bibliothèques et utilitaires
├── public/                       # Point d'entrée et ressources publiques
├── sql_updates/                  # Scripts SQL de migration
└── Documentation (fichiers .md)  # Documentation technique
```

---

## 🎯 Architecture MVC

### Controllers (app/Controllers/)
Gestion de la logique métier et des interactions utilisateur :

#### **Authentification & Autorisation**
- `AuthMiddleware.php` - Middleware de vérification d'authentification
- `LoginController.php` - Gestion de la connexion
- `RegisterController.php` - Gestion de l'inscription
- `BaseController.php` - Contrôleur de base dont héritent les autres

#### **Gestion des utilisateurs**
- `ProfileController.php` - Profil utilisateur
- `LocataireController.php` - Dashboard et actions des locataires
- `ProprietaireController.php` - Dashboard et actions des propriétaires
- `AdminController.php` - Administration générale
- `AdminController_users_section.php` - Gestion des utilisateurs (admin)

#### **Gestion des biens**
- `HomeController.php` - Page d'accueil et recherche
- `ReservationController.php` - Gestion des réservations
- `CommentaireController.php` - Système de commentaires et notes
- `CadreController.php` - Gestion des cadres de profil

#### **Fonctionnalités spéciales**
- `EasterEggController.php` - Easter eggs et récompenses

### Models (app/Models/)
Accès et manipulation des données de la base :

#### **Modèles principaux**
- `Model.php` - Modèle de base (connexion DB, méthodes communes)
- `UserModel.php` - Gestion des utilisateurs (locataires)
- `AdminModel.php` - Gestion des administrateurs
- `BienModel.php` - Gestion des biens immobiliers

#### **Modèles de relation**
- `ReservationModel.php` - Réservations
- `CommentaireModel.php` - Commentaires et avis
- `FavoriModel.php` - Favoris des utilisateurs
- `BlocageModel.php` - Blocages de dates
- `SignalementModel.php` - Signalements

#### **Modèles de référence**
- `TypeBienModel.php` - Types de biens (maison, appartement, etc.)
- `CommuneModel.php` - Villes et communes
- `PhotoModel.php` - Photos des biens
- `PrestationModel.php` - Prestations disponibles
- `TarifModel.php` - Tarifs par saison
- `SaisonModel.php` - Saisons (haute/basse)
- `RoleModel.php` - Rôles utilisateurs
- `CadreModel.php` - Cadres de profil personnalisables

### Views (app/Views/)
Interface utilisateur organisée par section :

```
Views/
├── layout/              # Templates de base (header, footer, nav)
├── home/                # Pages d'accueil et recherche
├── login/               # Pages de connexion
├── register/            # Pages d'inscription
├── profile/             # Profil utilisateur
├── locataire/           # Dashboard locataire
├── proprietaire/        # Dashboard propriétaire
├── admin/               # Interface d'administration
├── bien/                # Détails et gestion des biens
└── reservation/         # Gestion des réservations
```

---

## 🔧 Configuration & Infrastructure

### Configuration (config/)
- `config.php` - Configuration globale (DB, constantes, chemins)

### Bibliothèques (lib/)
- `Database.php` - Classe de connexion et gestion de la base de données

### Public (public/)
Point d'entrée et ressources statiques :

#### **Scripts PHP**
- `index.php` - Point d'entrée principal (routeur)
- `get_reservations.php` - API pour récupérer les réservations
- `debug_session.php` - Débogage des sessions
- `info.php` - Informations PHP

#### **CSS (public/css/)**
Styles organisés par fonctionnalité :
- `style.css` - Styles globaux
- `navbar.css` - Navigation
- `home.css` - Page d'accueil
- `auth.css` - Pages d'authentification
- `profile.css` - Profil utilisateur
- `dark-mode.css` - Mode sombre
- `bien-details.css` - Détails des biens
- `dashboard-proprio.css` - Dashboard propriétaire
- `locataire-dashboard.css` - Dashboard locataire
- `admin.css` - Interface admin
- `admin-modal.css`, `admin-content.css`, `admin-box-title.css`
- `admin-notifications-badges.css` - Notifications
- `commentaires.css` - Système de commentaires
- `favoris.css` - Favoris
- `calendar_proprio.css` - Calendrier propriétaire
- `easter-eggs.css` - Easter eggs
- `sunset-background.css` - Arrière-plans
- `tables.css`, `utilities.css` - Composants réutilisables

#### **JavaScript (public/js/)**
Scripts côté client :
- `dark-mode.js` - Gestion du mode sombre
- `admin-modal.js` - Modales d'administration
- `autocomplete.js` - Autocomplétion
- `favoris.js` - Gestion des favoris
- `easter-eggs.js` - Easter eggs interactifs
- `jquery.magnify.min.js` - Zoom d'images

#### **Autres ressources**
- `public/pfp/` - Photos de profil
- `public/uploads/` - Uploads (photos de biens)
- `public/cadre/` - Système de cadres de profil
  - `frames.css` - Styles des cadres
  - `index.php` - Interface de sélection
  - `frames/` - Images des cadres
  - `images/` - Images associées

---

## 🗄️ Base de données

### Tables principales

#### **Utilisateurs**
- `locataire` - Utilisateurs (locataires et propriétaires)
- `admin` - Administrateurs
- `roles` - Rôles disponibles
- `user_role` - Association utilisateurs-rôles

#### **Biens immobiliers**
- `biens` - Biens disponibles à la location
- `type_bien` - Types de biens (maison, appartement, etc.)
- `photos` - Photos des biens
- `prestation` - Prestations (WiFi, parking, etc.)
- `se_compose` - Association biens-prestations

#### **Localisation**
- `commune` - Base de données des villes françaises

#### **Réservations & Tarification**
- `reservations` - Réservations effectuées
- `blocages` - Blocages de dates par les propriétaires
- `saison` - Saisons (haute, basse, moyenne)
- `tarifs` - Tarifs par saison pour chaque bien

#### **Interactions**
- `commentaires` - Avis et notes des locataires
- `commentaire_likes` - Likes sur les commentaires
- `favoris` - Biens favoris des utilisateurs
- `signalements` - Signalements de biens

#### **Fonctionnalités spéciales**
- `cadres` - Cadres de profil déblocables

---

## 🔐 Système d'authentification

### Rôles
1. **Administrateur** (`admin`)
   - Validation des biens
   - Gestion des utilisateurs
   - Traitement des signalements
   
2. **Propriétaire** (`proprietaire`)
   - Ajout et gestion de biens
   - Gestion des réservations
   - Blocages de dates
   - Consultation des avis
   
3. **Locataire** (`locataire`)
   - Recherche de biens
   - Réservations
   - Gestion des favoris
   - Commentaires et notes

### Middleware
- `AuthMiddleware.php` vérifie l'authentification avant l'accès aux pages protégées

---

## ⚡ Fonctionnalités principales

### 1. Gestion des biens
- Ajout de biens par les propriétaires
- Validation par les administrateurs
- Photos multiples
- Prestations configurables
- Tarification saisonnière
- Statuts : `en_attente`, `valide`, `refuse`

### 2. Système de réservation
- Calendrier interactif
- Vérification de disponibilité
- Blocages de dates par propriétaires
- Calcul automatique des tarifs selon la saison

### 3. Commentaires et notes
- Notation de 1 à 5 étoiles
- Commentaires textuels
- Système de likes
- Modération possible
- Un commentaire par utilisateur par bien

### 4. Favoris
- Ajout/retrait de favoris
- Liste personnalisée

### 5. Signalements
- Signalement de biens problématiques
- Traitement par les administrateurs
- Motifs prédéfinis

### 6. Profils personnalisables
- Photos de profil
- Cadres de profil déblocables
- Easter eggs pour débloquer des récompenses

### 7. Administration
- Dashboard administrateur
- Validation des biens
- Gestion des utilisateurs
- Traitement des signalements
- Statistiques

---

## 📊 Scripts SQL de migration

Les mises à jour de la base de données sont versionnées dans `sql_updates/` :
- `add_validation_system.sql` - Système de validation des biens
- `add_commentaires_system.sql` - Système de commentaires
- `add_signalement_system.sql` - Système de signalements
- `add_favoris_system.sql` - Système de favoris
- `add_likes_commentaires.sql` - Likes sur commentaires
- `add_profile_frames_system.sql` - Cadres de profil
- `add_cadres_management_system.sql` - Gestion des cadres
- `add_easter_eggs_system.sql` - Easter eggs

---

## 📚 Documentation technique

### Fichiers de documentation
- `DOCUMENTATION.md` - Documentation générale
- `DOCUMENTATION_VALIDATION.md` - Système de validation
- `DOCUMENTATION_SIGNALEMENT.md` - Système de signalements
- `DOCUMENTATION_EASTER_EGGS.md` - Easter eggs
- `PROFILE_PICTURE_SYSTEM.md` - Photos de profil
- `SYSTEM_CADRES_MANAGEMENT.md` - Gestion des cadres
- `README_CSS.md` - Organisation CSS
- `PHPDOC_CONTROLLERS.md` - Documentation des contrôleurs
- `PHPDOC_MODELS.md` - Documentation des modèles
- `INTEGRATION_GUIDE.md` - Guide d'intégration

---

## 🔄 Flux de données

### Flux typique d'une requête

```
1. Requête HTTP → public/index.php
2. Routage → Contrôleur approprié
3. Contrôleur → Appel du/des Modèle(s)
4. Modèle → Requête base de données (via Database.php)
5. Modèle → Retour des données au Contrôleur
6. Contrôleur → Passage des données à la Vue
7. Vue → Génération HTML
8. Réponse HTTP → Client
```

### Exemple : Recherche de biens

```
User recherche → HomeController
                    ↓
              BienModel::search()
                    ↓
              Database → Query SQL
                    ↓
              Résultats retournés
                    ↓
              Views/home/search.php
                    ↓
              Affichage des résultats
```

---

## 🎨 Architecture front-end

### Organisation CSS
- **Approche modulaire** : un fichier CSS par fonctionnalité
- **Utilities.css** : classes réutilisables
- **Dark mode** : support complet avec dark-mode.css

### JavaScript
- **jQuery** : pour les interactions DOM
- **AJAX** : pour les requêtes asynchrones (favoris, réservations)
- **Modules séparés** : un fichier JS par fonctionnalité

---

## 🔒 Sécurité

### Mesures implémentées
- Hachage des mots de passe
- Protection CSRF (à vérifier/implémenter)
- Validation des entrées utilisateur
- Prepared statements (SQL injection)
- Vérification des permissions (middleware)
- Validation côté serveur des données

---

## 🚀 Points d'amélioration potentiels

1. **Framework** : Migration vers Laravel/Symfony
2. **API REST** : Création d'une API structurée
3. **Tests** : Ajout de tests unitaires et d'intégration
4. **Cache** : Mise en cache des données fréquentes
5. **CDN** : Pour les ressources statiques
6. **Logging** : Système de logs structuré
7. **Autoloader PSR-4** : Pour le chargement automatique des classes
8. **Composer** : Gestion des dépendances
9. **Environment variables** : Configuration sensible

---

## 📝 Conventions de codage

### PHP
- PSR-1 et PSR-12 (à vérifier)
- Nommage CamelCase pour les classes
- Nommage snake_case pour les colonnes DB

### Base de données
- Préfixe `id_` pour les clés primaires
- Suffixe du nom de table pour les colonnes (`_locataire`, `_biens`)
- InnoDB pour toutes les tables
- UTF-8 (utf8mb4)

---

## 🔧 Configuration requise

### Serveur
- PHP 7.4+
- MySQL/MariaDB 10.4+
- Apache/Nginx avec mod_rewrite
- Extensions PHP : PDO, mysqli

### Développement
- phpMyAdmin (recommandé)
- Éditeur de code (VS Code, PHPStorm)

---

**Date de dernière mise à jour** : 11 décembre 2025
**Version du projet** : 1.0
