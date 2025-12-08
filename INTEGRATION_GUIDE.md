# 🎨 Guide d'Intégration CSS - Goodnight

## 📁 Fichiers CSS créés

```
public/css/
├── style.css         → CSS principal + variables thèmes + animations
├── navbar.css        → Navigation
├── utilities.css     → Messages, boutons, utilitaires (TOUJOURS inclure)
├── tables.css        → Tableaux modernes avec tri, pagination, filtres
├── bien-details.css  → Page détails bien
├── auth.css          → Pages login/register
└── dashboard.css     → Dashboards admin/propriétaire/locataire
```

**⚠️ IMPORTANT : `utilities.css` doit être inclus dans TOUTES les pages**
**⚠️ IMPORTANT : `tables.css` doit être inclus dans toutes les pages avec des tableaux**

## 🌈 Palettes de couleurs

### 🌅 Thème Jour "Crépuscule"
- `#FF8C42` - Orange soleil
- `#FF6B9D` - Rose poudré
- `#FFF8F0` - Crème chaude
- `#FF5A5F` - Corail (CTA)
- `#2C3E50` - Bleu-gris textes

### 🌌 Thème Nuit "Nuit étoilée"
- `#0B0C10` - Noir velouté
- `#1F2833` - Bleu-gris profond
- `#45A29E` - Cyan-turquoise
- `#66FCF1` - Cyan lumineux (CTA)
- `#C5C6C7` - Argent textes

## 📝 Intégration par type de page

### 1. Page Home (`app/Views/home/index.php`)
```php
<head>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/utilities.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>
    <!-- Le CSS existant stylise déjà .hero, .search-bar, .biens-grid, .bien-card -->
    <script src="/js/dark-mode.js"></script>
</body>
```

### 2. Page Détails Bien (`app/Views/bien/details.php`)
```php
<head>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/utilities.css">
    <link rel="stylesheet" href="/css/bien-details.css">
</head>
```

### 3. Pages Auth (`app/Views/login/index.php`, `app/Views/register/index.php`)
```php
<head>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/utilities.css">
    <link rel="stylesheet" href="/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h1>Connexion</h1>
            <form class="auth-form">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email">
                </div>
                <button type="submit">Se connecter</button>
            </form>
            <div class="auth-links">
                <a href="/register">Créer un compte</a>
            </div>
        </div>
    </div>
    <script src="/js/dark-mode.js"></script>
</body>
```

### 4. Pages Dashboard (`app/Views/proprietaire/index.php`, etc.)
```php
<head>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/utilities.css">
    <link rel="stylesheet" href="/css/tables.css">
    <link rel="stylesheet" href="/css/dashboard.css">
</head>
<body>
    <?php include __DIR__ . '/../layout/navbar.php'; ?>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Tableau de bord</h1>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-value">12</div>
                <div class="stat-card-label">Réservations</div>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-header">
                <h2 class="table-title">Mes biens</h2>
            </div>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Appartement Centre</td>
                            <td>Appartement</td>
                            <td class="table-price">89€</td>
                            <td><span class="table-badge success">Actif</span></td>
                            <td>
                                <div class="table-actions">
                                    <a href="#" class="btn-view">Voir</a>
                                    <a href="#" class="btn-edit">Modifier</a>
                                    <a href="#" class="btn-delete">Supprimer</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="/js/dark-mode.js"></script>
</body>
```

## 🔧 Dark Mode

Le fichier `dark-mode.js` est déjà parfait. Il :
- Crée automatiquement le bouton toggle
- Ajoute la classe `.dark-mode` sur `<body>`
- Sauvegarde la préférence
- Génère des étoiles filantes

**Il faut juste ajouter avant `</body>` :**
```html
<script src="/js/dark-mode.js"></script>
```

## 🎯 Classes CSS principales à utiliser

### Layout
- `.hero` - Section hero avec gradient
- `.search-bar` - Barre de recherche stylée
- `.biens-grid` - Grille responsive de biens
- `.bien-card` - Carte d'annonce
- `.btn-reserver` - Bouton d'action principal

### Auth
- `.auth-container` - Conteneur page auth
- `.auth-card` - Carte de formulaire
- `.auth-form` - Formulaire
- `.form-group` - Groupe de champ

### Dashboard
- `.dashboard-container` - Conteneur principal
- `.stats-grid` - Grille de statistiques
- `.stat-card` - Carte statistique
- `.dashboard-section` - Section de contenu
- `.data-table` - Tableau de données
- `.action-btn` - Bouton d'action
- `.badge-*` - Badges de statut

### Messages
- `.alert` - Message générique
- `.error-message` - Message d'erreur
- `.success-message` - Message de succès

## 📊 Exemple de tableau complet

```php
<div class="table-container">
    <!-- Header avec titre et actions -->
    <div class="table-header">
        <h2 class="table-title">Mes réservations</h2>
        <div class="table-actions-top">
            <a href="/reservation/add" class="action-btn">
                ➕ Nouvelle réservation
            </a>
        </div>
    </div>
    
    <!-- Filtres optionnels -->
    <div class="table-filters">
        <div class="table-search">
            <input type="text" placeholder="Rechercher...">
        </div>
        <div class="table-filter-group">
            <label>Statut</label>
            <select>
                <option>Tous</option>
                <option>Confirmé</option>
                <option>En attente</option>
            </select>
        </div>
    </div>
    
    <!-- Tableau -->
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="sortable">Client</th>
                    <th class="sortable">Bien</th>
                    <th class="sortable">Date</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="table-user">
                            <img src="/pfp/user.jpg" class="table-avatar" alt="">
                            <div class="table-user-info">
                                <div class="table-user-name">Jean Dupont</div>
                                <div class="table-user-email">jean@example.com</div>
                            </div>
                        </div>
                    </td>
                    <td>Appartement Centre</td>
                    <td class="table-date">15-20 Dec 2024</td>
                    <td class="table-price">445€</td>
                    <td><span class="table-badge success">Confirmé</span></td>
                    <td>
                        <div class="table-actions">
                            <a href="#" class="btn-view">👁️</a>
                            <a href="#" class="btn-edit">✏️</a>
                            <a href="#" class="btn-delete">🗑️</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="table-pagination">
        <div class="pagination-info">
            Affichage 1-10 sur 45 résultats
        </div>
        <div class="pagination-controls">
            <a href="#">‹</a>
            <span class="active">1</span>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">›</a>
        </div>
    </div>
</div>
```

## ✅ TODO pour vous

1. Ajouter `<script src="/js/dark-mode.js"></script>` avant `</body>` dans toutes les pages
2. Vérifier que les liens CSS pointent bien vers `/css/...`
3. Remplacer vos `<main>` par les structures indiquées ci-dessus
4. Pour les tableaux, utiliser la structure `.table-container` avec `.table-wrapper` et `.data-table`
5. Tester le dark mode toggle (devrait apparaître en bas à droite)

## 🐛 Si ça ne marche pas

1. Vérifier la console (F12) pour les erreurs 404
2. Vérifier que les fichiers CSS sont bien dans `public/css/`
3. Vérifier la configuration de votre serveur web pour servir `/css/` depuis `public/css/`
