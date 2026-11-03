# 🌙 Goodnight

Plateforme de location de logements entre particuliers : recherche, fiches détaillées, réservation, espace propriétaire et modération. Projet en **PHP (architecture MVC maison)** et **MySQL**.

> **État du projet** : application fonctionnelle en local, en cours de refonte (nouvelle interface, front Vue 3 sur Cloudflare Pages et API sur Cloudflare Workers). Voir la [feuille de route](#feuille-de-route).

## Fonctionnalités

**Locataires** : recherche par commune, type et dates · carte des biens · fiche détaillée avec photos · réservation · favoris · avis et notes · historique des réservations · cadres de profil à débloquer (easter eggs).

**Propriétaires** : gestion des biens et des photos · calendrier des réservations (FullCalendar) · blocages de dates · tarifs par saison · tableau de bord avec indicateurs et graphiques (Chart.js) · notifications.

**Administrateurs** : validation des biens avant publication · gestion des utilisateurs, rôles, types de biens et saisons · modération (signalements, commentaires) · gestion des cadres et easter eggs.

## Stack

| Couche | Technologies |
|---|---|
| Backend | PHP 7.4+, PDO, routeur et MVC écrits à la main |
| Base de données | MySQL 5.7+ |
| Frontend | HTML, CSS (variables), JavaScript |
| Bibliothèques | FullCalendar, Chart.js, DataTables, intl-tel-input, Mapbox GL JS |

## Installation rapide

Prérequis : PHP 7.4+ (extensions `pdo_mysql`, `gd`), MySQL 5.7+.

```bash
git clone https://github.com/Gu1LLaumeR0UY3R/Goodnight.git
cd Goodnight

# 1. Base de données
mysql -u root -p -e "CREATE DATABASE goodnight CHARACTER SET utf8mb4;"
mysql -u root -p goodnight < database/schema.sql
for f in database/migrations/*.sql; do mysql -u root -p goodnight < "$f"; done

# 2. Configuration (fichier ignoré par Git)
cp config/config.example.php config/config.php   # puis renseigner DB_* et MAPBOX_TOKEN

# 3. Lancement
php -S localhost:8000 -t public
```

Le détail (permissions, dépannage, déploiement) est dans le [guide d'installation](docs/guides/installation.md).

## Structure

```
app/            Controllers, Models, Views (MVC)
config/         config.example.php (copier vers config.php)
database/       schema.sql + migrations ordonnées
docs/           documentation technique et fonctionnelle
lib/            connexion PDO
public/         point d'entrée (index.php), css, js, images
```

## Documentation

Tout est indexé dans [`docs/README.md`](docs/README.md) : [architecture](docs/architecture.md), [vue d'ensemble technique](docs/technical-overview.md), [référence des contrôleurs](docs/reference/controllers.md) et [des modèles](docs/reference/models.md), et une page par fonctionnalité dans [`docs/features/`](docs/features/).

## Sécurité : état actuel

- Mots de passe hachés (`password_hash` / `password_verify`), rôles contrôlés par middleware.
- Requêtes préparées PDO pour les données utilisateur ; les requêtes restantes construites par concaténation sont à auditer.
- Aucune protection CSRF pour l'instant (voir feuille de route).
- Secrets hors dépôt : `config/config.php` est ignoré, le jeton Mapbox se configure via `MAPBOX_TOKEN`.
- Le compte administrateur de `database/schema.sql` est réservé au développement local.

## Feuille de route

- [ ] Nouvelle interface (palette crème / sable / charbon / olive, Playfair Display + DM Sans)
- [ ] Front Vue 3 (Cloudflare Pages) + API Workers et base D1, avec une démo interactive
- [ ] Protection CSRF et audit des requêtes SQL
- [ ] Finalisation du multi-suppression côté admin
- [ ] Tests automatisés (PHPUnit)

Historique des changements : [CHANGELOG.md](CHANGELOG.md).

## Crédits

Les images de démonstration sont des visuels de test ou générés par IA. Bibliothèques tierces : FullCalendar, Chart.js, DataTables, intl-tel-input, jQuery Magnify, Mapbox GL JS (voir leurs licences respectives).

## Licence

À définir.
