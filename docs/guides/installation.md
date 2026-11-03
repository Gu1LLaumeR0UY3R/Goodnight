# Guide d'installation

## Prérequis

- PHP 7.4 ou supérieur avec les extensions `pdo_mysql`, `gd`, `json`, `session`
- MySQL 5.7+ (ou MariaDB équivalent)
- Optionnel : un jeton public [Mapbox](https://account.mapbox.com) pour la carte des biens

## 1. Base de données

Le schéma ne crée pas la base : créez-la d'abord.

```bash
mysql -u root -p -e "CREATE DATABASE goodnight CHARACTER SET utf8mb4;"
mysql -u root -p goodnight < database/schema.sql
```

Appliquez ensuite les migrations **dans l'ordre numérique** :

| # | Fichier | Contenu |
|---|---|---|
| 01 | `01_add_validation_system.sql` | Validation des biens par l'admin |
| 02 | `02_add_signalement_system.sql` | Signalements de biens |
| 03 | `03_add_commentaires_system.sql` | Avis et notes |
| 04 | `04_add_likes_commentaires.sql` | Likes sur les avis |
| 05 | `05_add_favoris_system.sql` | Favoris |
| 06 | `06_add_notifications_system.sql` | Notifications |
| 07 | `07_add_profile_frames_system.sql` | Cadres de profil |
| 08 | `08_add_easter_eggs_system.sql` | Easter eggs |
| 09 | `09_add_cadres_management_system.sql` | Gestion des cadres par l'admin |

```bash
for f in database/migrations/*.sql; do mysql -u root -p goodnight < "$f"; done
```

## 2. Configuration

```bash
cp config/config.example.php config/config.php
```

Renseignez `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` et `MAPBOX_TOKEN`. Chaque valeur peut aussi venir d'une variable d'environnement du même nom. `config/config.php` est ignoré par Git.

## 3. Permissions

Les dossiers suivants doivent être accessibles en écriture par PHP :

```bash
chmod 755 public/uploads public/pfp public/cadre/frames
```

## 4. Lancement

```bash
php -S localhost:8000 -t public
```

Puis ouvrez <http://localhost:8000>. Le compte administrateur de `database/schema.sql` est réservé au développement local ; changez son mot de passe ou supprimez-le avant toute mise en ligne.

## Déploiement sur un serveur

1. Pointez le *document root* du virtual host sur `public/` et activez `AllowOverride All` (Apache) pour que `.htaccess` redirige vers `index.php`.
2. Activez HTTPS et utilisez PHP-FPM.
3. Fournissez la configuration par variables d'environnement ou par un `config/config.php` hors dépôt.

## Dépannage

| Symptôme | Cause probable |
|---|---|
| « Configuration manquante » | `config/config.php` n'existe pas : copiez `config.example.php` |
| Erreur de connexion PDO | Identifiants `DB_*` incorrects ou base non créée |
| Carte vide | `MAPBOX_TOKEN` non renseigné |
| Upload de photo refusé | Permissions sur `public/uploads` ou `public/pfp` |
