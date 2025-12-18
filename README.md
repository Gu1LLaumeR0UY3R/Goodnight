# 🌙 Goodnight - Plateforme de Location Immobilière

Bienvenue sur **Goodnight**, une plateforme moderne de location de biens immobiliers développée en PHP avec une architecture MVC.

---

## 🚀 Démarrage Rapide

### Installation

1. **Cloner le projet**
   ```bash
   git clone [url-du-repo]
   cd Goodnight-main
   ```

2. **Configurer la base de données**
   ```bash
   # Importer le schéma principal
   mysql -u root -p < goodnight.sql
   
   # Importer les mises à jour SQL (dans l'ordre)
   mysql -u root -p goodnight < sql_updates/add_validation_system.sql
   mysql -u root -p goodnight < sql_updates/add_signalement_system.sql
   mysql -u root -p goodnight < sql_updates/add_commentaires_system.sql
   # ... (voir docs/README.md pour la liste complète)
   ```

3. **Configurer l'application**
   ```php
   // config/config.php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'goodnight');
   define('DB_USER', 'root');
   define('DB_PASS', 'votre_mot_de_passe');
   ```

4. **Permissions des dossiers**
   ```bash
   chmod 755 public/uploads
   chmod 755 public/pfp
   chmod 755 public/cadre/frames
   ```

5. **Lancer le serveur**
   ```bash
   cd public
   php -S localhost:8000
   ```

6. **Accéder à l'application**
   ```
   http://localhost:8000
   ```

---

## 📚 Documentation

**Toute la documentation est disponible dans le dossier [`docs/`](./docs/README.md)**

### Liens Rapides

- **[📖 Documentation Complète](./docs/README.md)** - Index de toute la documentation
- **[🏗️ Architecture](./docs/ARCHITECTURE.md)** - Structure du projet MVC
- **[📘 Documentation Technique](./docs/DOCUMENTATION.md)** - Contrôleurs, modèles, routes
- **[🎨 Guide d'Intégration CSS](./docs/INTEGRATION_GUIDE.md)** - Thèmes et styles

### Documentation par Système

- **[Validation des biens](./docs/SYSTEM_VALIDATION.md)** - Workflow de validation admin
- **[Signalements](./docs/SYSTEM_SIGNALEMENT.md)** - Modération de contenu
- **[Commentaires & Notes](./docs/SYSTEM_COMMENTAIRES.md)** - Avis des locataires
- **[Easter Eggs](./docs/SYSTEM_EASTER_EGGS.md)** - Cadres de profil déblocables
- **[Photos de profil](./docs/SYSTEM_PROFILE_PICTURES.md)** - Upload et gestion

### Dernières Modifications

**[📅 Modifications du 16 Décembre 2025](./docs/changes-2025-12-16/)**
- Notifications de validation
- Tableau de bord propriétaire amélioré (KPI, filtres, badges)
- Système de statistiques avec graphiques interactifs

---

## 🎯 Fonctionnalités Principales

### Pour les Locataires 🏠
- ✅ Recherche de biens (par commune, type, dates)
- ✅ Réservation en ligne
- ✅ Gestion des favoris
- ✅ Commentaires et notes sur les biens
- ✅ Historique des réservations
- ✅ Système d'Easter Eggs (cadres de profil)

### Pour les Propriétaires 🏘️
- ✅ Ajout et gestion de biens
- ✅ Calendrier des réservations (FullCalendar)
- ✅ Blocages de dates
- ✅ Tarifs par saison
- ✅ **Nouveau !** Dashboard avec KPI et statistiques
- ✅ **Nouveau !** Graphiques de revenus et réservations

### Pour les Administrateurs 👮
- ✅ Validation des biens
- ✅ Gestion des utilisateurs et rôles
- ✅ Modération (signalements, commentaires)
- ✅ Gestion des types de biens et saisons
- ✅ Configuration des Easter Eggs

---

## 🛠️ Technologies Utilisées

- **Backend** : PHP 7.4+ (Architecture MVC custom)
- **Base de données** : MySQL
- **Frontend** : HTML5, CSS3 (Variables CSS), JavaScript ES6+
- **Bibliothèques** :
  - [FullCalendar 5.11.3](https://fullcalendar.io/) - Calendrier de réservations
  - [Chart.js 4.4.0](https://www.chartjs.org/) - Graphiques statistiques
- **Design** : Responsive, thème jour/nuit

---

## 📁 Structure du Projet

```
Goodnight/
├── app/                          # Application MVC
│   ├── Controllers/              # Logique métier
│   ├── Models/                   # Accès aux données
│   └── Views/                    # Templates PHP
│       ├── admin/                # Interface admin
│       ├── proprietaire/         # Dashboard propriétaire
│       ├── locataire/            # Interface locataire
│       └── ...
├── config/                       # Configuration
│   └── config.php                # Paramètres BDD
├── docs/                         # 📚 Documentation complète
│   ├── README.md                 # Index documentation
│   ├── ARCHITECTURE.md
│   ├── DOCUMENTATION.md
│   ├── SYSTEM_*.md               # Docs par fonctionnalité
│   └── changes-2025-12-16/       # Modifications récentes
├── lib/                          # Bibliothèques
│   └── Database.php              # Connexion PDO
├── public/                       # Point d'entrée web
│   ├── index.php                 # Router principal
│   ├── css/                      # Styles
│   ├── js/                       # Scripts
│   ├── uploads/                  # Photos de biens
│   ├── pfp/                      # Photos de profil
│   └── cadre/                    # Cadres easter eggs
├── sql_updates/                  # Scripts de migration SQL
├── goodnight.sql                 # Schéma de base
└── README.md                     # ← Vous êtes ici
```

---

## 🎨 Thèmes

L'application propose deux thèmes :

### 🌅 Thème Jour "Crépuscule"
- Orange soleil (#FF8C42)
- Rose poudré (#FF6B9D)
- Crème chaude (#FFF8F0)

### 🌌 Thème Nuit "Nuit étoilée"
- Noir velouté (#0B0C10)
- Bleu-gris profond (#1F2833)
- Bleu électrique (#66FCF1)

**Changement de thème** : Bouton 🌙/☀️ dans la navbar

---

## ⚙️ Configuration Recommandée

### Prérequis Système

- **PHP** : 7.4 ou supérieur
- **MySQL** : 5.7 ou supérieur
- **Extensions PHP** :
  - `pdo_mysql`
  - `gd` (pour le traitement d'images)
  - `json`
  - `session`

### Serveur de Production

Pour un déploiement en production, configurez :

1. **Virtual Host Apache/Nginx** pointant vers `public/`
2. **HTTPS** (Let's Encrypt recommandé)
3. **PHP-FPM** pour de meilleures performances
4. **Permissions** strictes sur les dossiers uploads

Exemple Apache :
```apache
<VirtualHost *:80>
    ServerName goodnight.example.com
    DocumentRoot /var/www/goodnight/public
    
    <Directory /var/www/goodnight/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

## 🔒 Sécurité

- ✅ **Authentification** : Sessions PHP sécurisées
- ✅ **Middleware** : Vérification des rôles (AuthMiddleware)
- ✅ **SQL Injection** : PDO avec requêtes préparées
- ✅ **XSS** : `htmlspecialchars()` sur toutes les sorties
- ✅ **CSRF** : Tokens de session (à implémenter pour les formulaires critiques)
- ✅ **Upload** : Validation des types MIME et extensions

---

## 🧪 Tests

### Tests Manuels

Consultez les guides de test dans chaque documentation système :
- [Tests de validation](./docs/SYSTEM_VALIDATION.md#comment-tester)
- [Tests des statistiques](./docs/changes-2025-12-16/STATISTIQUES_GRAPHIQUES.md#comment-tester)
- [Tests Easter Eggs](./docs/SYSTEM_EASTER_EGGS.md#installation)

### Comptes de Test

```
Administrateur :
  Email: admin@goodnight.com
  Mot de passe: admin123

Propriétaire :
  Email: proprio@goodnight.com
  Mot de passe: proprio123

Locataire :
  Email: locataire@goodnight.com
  Mot de passe: locataire123
```

*(À configurer dans votre base de données)*

---

## 🤝 Contribution

### Ajouter une Nouvelle Fonctionnalité

1. **Créer la branche** : `git checkout -b feature/nom-fonctionnalite`
2. **Développer** : Suivre l'architecture MVC existante
3. **Documenter** : Créer `docs/SYSTEM_NOM.md`
4. **Tester** : Vérifier tous les scénarios
5. **Commit** : Messages clairs et descriptifs
6. **Pull Request** : Avec description détaillée

### Standards de Code

- **Indentation** : 4 espaces
- **Nommage** :
  - Classes : `PascalCase`
  - Méthodes : `camelCase`
  - Variables : `$snake_case`
  - Constantes : `UPPER_CASE`
- **Commentaires** : PHPDoc pour toutes les méthodes publiques

---

## 📜 Licence

*À définir - Mentionnez ici la licence de votre projet*

---

## 📞 Support & Contact

- **Documentation** : [docs/README.md](./docs/README.md)
- **Issues** : [Créer une issue sur GitHub](#)
- **Email** : support@goodnight.com *(à configurer)*

---

## 🗓️ Roadmap

### En Cours
- [x] Système de statistiques avec graphiques
- [x] Dashboard propriétaire amélioré
- [x] Notifications de validation

### Prochaines Fonctionnalités
- [ ] API REST pour application mobile
- [ ] Export PDF des réservations
- [ ] Messagerie entre locataires et propriétaires
- [ ] Système de parrainage
- [ ] Multi-langues (FR/EN/ES)
- [ ] Paiement en ligne (Stripe/PayPal)

### Améliorations Techniques
- [ ] Tests unitaires (PHPUnit)
- [ ] Tokens CSRF sur tous les formulaires
- [ ] Cache Redis pour les performances
- [ ] CDN pour les assets statiques
- [ ] Migration vers un framework moderne (Laravel/Symfony)

---

**🌙 Bonne nuit et bonnes locations avec Goodnight !**

*Dernière mise à jour : 16 Décembre 2025*
