# 📚 Documentation Goodnight

Bienvenue dans la documentation complète de l'application **Goodnight** - Plateforme de location de biens immobiliers.

---

## 🗂️ Organisation de la documentation

### 📖 Documentation Principale

#### [DOCUMENTATION.md](./DOCUMENTATION.md)
Documentation technique complète de l'application
- Architecture MVC
- Contrôleurs et modèles
- Middlewares et routes
- Base de données

#### [ARCHITECTURE.md](./ARCHITECTURE.md)
Vue d'ensemble de l'architecture du projet
- Structure des dossiers
- Pattern MVC détaillé
- Flux de données
- Diagrammes

---

## 🎯 Documentation par Fonctionnalité

### Systèmes Utilisateurs

#### [SYSTEM_VALIDATION.md](./SYSTEM_VALIDATION.md)
Système de validation des biens par les administrateurs
- Workflow de validation
- Statuts des biens
- Notifications
- Tests

#### [SYSTEM_SIGNALEMENT.md](./SYSTEM_SIGNALEMENT.md)
Système de signalement de contenu inapproprié
- Types de signalements
- Modération
- Workflow de traitement

#### [SYSTEM_COMMENTAIRES.md](./SYSTEM_COMMENTAIRES.md)
Système de commentaires et notes sur les biens
- Évaluation des biens
- Likes sur commentaires
- Modération

---

### Fonctionnalités Avancées

#### [SYSTEM_EASTER_EGGS.md](./SYSTEM_EASTER_EGGS.md)
Système de cadres de profil déblocables (Easter Eggs)
- Gestion des cadres PNG
- Déblocage et activation
- Interface d'administration

#### [SYSTEM_CADRES.md](./SYSTEM_CADRES.md)
Gestion avancée des cadres de profil
- Upload et validation
- Prévisualisation
- Permissions

#### [SYSTEM_PROFILE_PICTURES.md](./SYSTEM_PROFILE_PICTURES.md)
Gestion des photos de profil
- Upload d'images
- Recadrage et redimensionnement
- Stockage

---

## 🎨 Documentation Design & Interface

#### [INTEGRATION_GUIDE.md](./INTEGRATION_GUIDE.md)
Guide d'intégration CSS et design
- Palettes de couleurs (jour/nuit)
- Composants réutilisables
- Thèmes et animations

#### [README_CSS.md](./README_CSS.md)
Documentation détaillée des styles CSS
- Variables CSS
- Classes utilitaires
- Responsive design
- Mode sombre

---

## 👨‍💻 Documentation Développeur

### API & Code

#### [PHPDOC_CONTROLLERS.md](./PHPDOC_CONTROLLERS.md)
Documentation PHPDoc des contrôleurs
- Méthodes publiques
- Paramètres et retours
- Exemples d'utilisation

#### [PHPDOC_MODELS.md](./PHPDOC_MODELS.md)
Documentation PHPDoc des modèles
- Accès aux données
- Requêtes SQL
- Relations entre tables

---

## 📅 Historique des Modifications

### [Modifications du 16 Décembre 2025](./changes-2025-12-16/)

Dernières fonctionnalités ajoutées :

#### [1. Notifications de validation](./changes-2025-12-16/NOTIFICATIONS_VALIDATION.md)
Les propriétaires reçoivent une notification quand leur bien est validé

#### [2. Tableau de bord propriétaire amélioré](./changes-2025-12-16/DASHBOARD_PROPRIETAIRE.md)
- KPI avec métriques en temps réel
- Badges de statut et filtres
- Actions rapides

#### [3. Système de statistiques avec graphiques](./changes-2025-12-16/STATISTIQUES_GRAPHIQUES.md)
- 4 graphiques interactifs (Chart.js)
- 4 périodes d'analyse (24h, 7j, mois, année)
- Comparaison avec période précédente

---

## 🚀 Guide de Démarrage Rapide

### Pour les développeurs

1. **Architecture** : Lisez [ARCHITECTURE.md](./ARCHITECTURE.md)
2. **Documentation technique** : Consultez [DOCUMENTATION.md](./DOCUMENTATION.md)
3. **Styles CSS** : Voir [INTEGRATION_GUIDE.md](./INTEGRATION_GUIDE.md)
4. **PHPDoc** : Référencez [PHPDOC_CONTROLLERS.md](./PHPDOC_CONTROLLERS.md) et [PHPDOC_MODELS.md](./PHPDOC_MODELS.md)

### Pour les administrateurs

1. **Validation des biens** : [SYSTEM_VALIDATION.md](./SYSTEM_VALIDATION.md)
2. **Modération** : [SYSTEM_SIGNALEMENT.md](./SYSTEM_SIGNALEMENT.md)
3. **Easter Eggs** : [SYSTEM_EASTER_EGGS.md](./SYSTEM_EASTER_EGGS.md)

### Pour comprendre une fonctionnalité

Recherchez dans la section **Documentation par Fonctionnalité** ci-dessus.

---

## 📊 Structure de la Documentation

```
docs/
├── README.md                              ← Vous êtes ici
│
├── 📘 Documentation Principale
│   ├── DOCUMENTATION.md
│   └── ARCHITECTURE.md
│
├── 🎯 Systèmes par Fonctionnalité
│   ├── SYSTEM_VALIDATION.md
│   ├── SYSTEM_SIGNALEMENT.md
│   ├── SYSTEM_COMMENTAIRES.md
│   ├── SYSTEM_EASTER_EGGS.md
│   ├── SYSTEM_CADRES.md
│   └── SYSTEM_PROFILE_PICTURES.md
│
├── 🎨 Design & Interface
│   ├── INTEGRATION_GUIDE.md
│   └── README_CSS.md
│
├── 👨‍💻 Documentation Code
│   ├── PHPDOC_CONTROLLERS.md
│   └── PHPDOC_MODELS.md
│
└── 📅 Historique
    └── changes-2025-12-16/
        ├── README_MODIFS_2025-12-16.md
        ├── NOTIFICATIONS_VALIDATION.md
        ├── DASHBOARD_PROPRIETAIRE.md
        └── STATISTIQUES_GRAPHIQUES.md
```

---

## 🔍 Recherche Rapide

**Je cherche...**

- **Comment fonctionne l'authentification ?** → [DOCUMENTATION.md](./DOCUMENTATION.md#middlewares)
- **Comment ajouter un nouveau contrôleur ?** → [ARCHITECTURE.md](./ARCHITECTURE.md)
- **Comment gérer les signalements ?** → [SYSTEM_SIGNALEMENT.md](./SYSTEM_SIGNALEMENT.md)
- **Quelles sont les couleurs du thème ?** → [INTEGRATION_GUIDE.md](./INTEGRATION_GUIDE.md)
- **Comment débloquer un easter egg ?** → [SYSTEM_EASTER_EGGS.md](./SYSTEM_EASTER_EGGS.md)
- **Quelles sont les dernières fonctionnalités ?** → [changes-2025-12-16/](./changes-2025-12-16/)

---

## 💡 Conventions de Documentation

### Icônes utilisées

- 📌 **Objectif** : But de la fonctionnalité
- 🔧 **Fichiers** : Fichiers concernés
- 🗄️ **Base de données** : Structure SQL
- 💻 **Utilisation** : Guide d'utilisation
- ✅ **Tests** : Comment tester
- 💡 **Notes** : Informations techniques
- 🚀 **Améliorations** : Idées futures
- ⚠️ **Important** : Points d'attention

### Format des liens de fichiers

Les chemins relatifs pointent vers les fichiers du projet :
- Depuis `docs/` : `../app/Controllers/ExempleController.php`
- Documentation interne : `./AUTRE_DOC.md`

---

## 🛠️ Maintenance de la Documentation

### Mise à jour

Lors de l'ajout d'une nouvelle fonctionnalité :

1. Créer un nouveau fichier `SYSTEM_NOM_FONCTIONNALITE.md`
2. L'ajouter dans ce README sous la section appropriée
3. Documenter les modifications dans `changes-AAAA-MM-JJ/`
4. Mettre à jour `DOCUMENTATION.md` si nécessaire

### Standards

- Utiliser le format Markdown
- Inclure des exemples de code
- Ajouter des captures d'écran si pertinent
- Documenter les tests
- Lister les dépendances

---

## 📞 Support

Pour toute question sur la documentation :
- Consultez d'abord l'index ci-dessus
- Utilisez la recherche de votre éditeur (Ctrl+F)
- Référez-vous aux exemples de code dans les fichiers concernés

---

*Documentation maintenue à jour - Dernière modification : 16 Décembre 2025*
