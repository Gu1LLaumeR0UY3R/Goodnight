# Guide d'installation

## Prérequis

- PHP 7.4 ou supérieur avec les extensions `pdo_mysql`, `gd`, `json`, `session`
- MySQL 5.7+ (ou MariaDB équivalent)
- Optionnel : un jeton public [Mapbox](https://account.mapbox.com) pour la carte des biens

## 1. Base de données

Un seul fichier crée la base `goodnight` et toutes les tables (structure, index, clés étrangères, données de référence) :

```bash
mysql -u root -p < database/goodnight.sql
```

Il est ré-exécutable sans risque : les tables existantes ne sont pas supprimées.

**Communes :** la table `commune` est créée vide. Pour la recherche par ville et la carte, importez la liste des communes de France (jeu « villes de France ») dans cette table.

**Données de démonstration (facultatif, base vide uniquement) :**

```bash
mysql -u root -p goodnight < database/seed_demo.sql
```

Elles ajoutent 10 utilisateurs, 9 biens (dont un en attente et un refusé), des tarifs, réservations, avis, likes, favoris, signalements et notifications. Mot de passe de tous les comptes : `Demo!2026` (`admin@example.com`, `claire.marchand@example.com`, `thomas.girard@example.com`, etc.).

**Sans les données de démo**, créez un administrateur à la main :

```bash
php -r "echo password_hash('VotreMotDePasse', PASSWORD_DEFAULT);"
```
```sql
INSERT INTO admin (nom_admin, prenom_admin, email_admin, mot_de_passe_admin)
VALUES ('Nom', 'Prénom', 'vous@exemple.fr', '<hash obtenu ci-dessus>');
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

Puis ouvrez <http://localhost:8000>. Ne mettez jamais les données de démo en production.

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
