# Documentation Goodnight

Point d'entrée de la documentation. Pour une vue d'ensemble du projet, voir le [README](../README.md).

## Démarrer

- [Guide d'installation](guides/installation.md) : base de données, configuration, lancement, dépannage
- [Thèmes et CSS](guides/theming.md) : intégration des feuilles de style et classes disponibles

## Comprendre le code

- [Architecture](architecture.md) : structure des dossiers, pattern MVC, flux de données
- [Vue d'ensemble technique](technical-overview.md) : contrôleurs, modèles, middlewares, routes, base de données
- [Référence des contrôleurs](reference/controllers.md) et [des modèles](reference/models.md) (PHPDoc)

## Fonctionnalités

| Fonctionnalité | Document |
|---|---|
| Validation des biens par l'admin | [validation](features/validation.md) |
| Signalements et modération | [signalements](features/signalements.md) |
| Avis, notes et likes | [commentaires](features/commentaires.md) |
| Notifications (réservations, validation) | [notifications](features/notifications.md) |
| Tableau de bord propriétaire | [owner-dashboard](features/owner-dashboard.md) |
| Statistiques et graphiques | [statistics](features/statistics.md) |
| Cadres de profil et easter eggs | [easter-eggs](features/easter-eggs.md), [cadres](features/cadres.md) |
| Photos de profil | [profile-pictures](features/profile-pictures.md) |
| Multi-suppression admin (en cours) | [admin-multi-delete](features/admin-multi-delete.md) |

## Base de données

Le schéma est dans [`database/schema.sql`](../database/schema.sql) et les évolutions dans [`database/migrations/`](../database/migrations/), à appliquer dans l'ordre numérique.

## Historique

Voir le [CHANGELOG](../CHANGELOG.md).

## Écrire de la documentation

- Une page par fonctionnalité dans `features/`, nommée en minuscules avec des tirets.
- Pas de copie d'un document à plusieurs endroits : on lie vers la page unique.
- Mettre à jour l'index ci-dessus à chaque nouvelle page.
