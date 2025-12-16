# Système de notifications

Ce document explique l'architecture, l'installation, et le fonctionnement du système de notifications ajouté à l'application.

## Objectifs

- Alerter tous les utilisateurs impactés par des changements liés à leurs réservations (ex. suppression d'un bien, modification d'une réservation).
- Alerter le propriétaire lorsqu'une nouvelle réservation est effectuée sur l'un de ses biens.

## Modèle de données

Table SQL: `notifications`

- `id_notification` (PK, auto)
- `user_id` (FK logique vers `locataire.id_locataire`)
- `type` (varchar 64) — code fonctionnel: `reservation_created`, `reservation_updated`, `reservation_deleted`, `reservation_cancelled`, `bien_change`, etc.
- `title` (varchar 255)
- `message` (text)
- `link` (varchar 255, nullable) — URL interne pour consulter l'élément concerné
- `is_read` (tinyint, 0/1) — statut de lecture
- `created_at` (datetime, défaut NOW)

Migration: `sql_updates/add_notifications_system.sql`

## Couches applicatives

### Modèle PHP: `app/Models/NotificationModel.php`

Fonctionnalités principales:

- `create(array $data)` — crée une notification
- `listForUser(int $userId, int $limit=20, int $offset=0)` — liste paginée
- `countUnread(int $userId)` — compteur non lues
- `markAsRead(int $userId, int $id)` — marquer une notification comme lue
- `markAllAsRead(int $userId)` — marquer toutes comme lues
- `notifyUsersWithFutureReservationsForBien(int $id_biens, string $title, string $message, ?string $link)` — utilitaire pour notifier tous les locataires avec une réservation à venir sur un bien donné

### API: `app/Controllers/NotificationController.php`

Routes (déclarées dans `public/index.php`):

- `GET /api/notifications` — liste des notifications de l'utilisateur courant (auth requis)
- `GET /api/notifications/count` — compteur de non lues
- `GET /api/notifications/mark-read/{id}` — marquer comme lue
- `GET /api/notifications/mark-all-read` — marquer toutes comme lues

Ces endpoints retournent du JSON. L'accès requiert un utilisateur connecté (Locataire ou Propriétaire).

### Intégration UI

Fichier: `app/Views/layout/navbar.php`

- Ajout d'une icône cloche avec badge et d'un panneau déroulant.
- Le script client `public/js/notifications.js` gère:
  - le polling du compteur (toutes les 30s)
  - l'ouverture/fermeture du panneau
  - le rendu des notifications
  - le marquage en lu (clic sur une notification ou sur "Tout marquer comme lu")

Aucun framework JS requis; du JS vanilla suffit.

## Déclencheurs (hooks) et scénarios couverts

1) Nouvelle réservation (Locataire ou Admin)

- Contrôleurs: `ReservationController::store`, `AdminController::addReservation`
- Action: créer une notification pour le propriétaire du bien (`type=reservation_created`).

2) Modification d'une réservation (Admin)

- Contrôleur: `AdminController::editReservation`
- Détection: si dates ou bien changent
- Action: créer notifications pour le locataire et pour le propriétaire (`type=reservation_updated`).

3) Suppression/Annulation d'une réservation (Admin ou Locataire)

- `AdminController::deleteReservation`: notifie le locataire et le propriétaire (`type=reservation_deleted`).
- `ReservationController::cancel`: notifie le propriétaire qu'un locataire a annulé (`type=reservation_cancelled`).

4) Suppression d'un bien (Admin ou Propriétaire)

- `AdminController::deleteBien` et `ProprietaireController::deleteBien`:
  - Notifier tous les locataires ayant des réservations à venir sur ce bien (`type=bien_change`).
  - Option: suppression des réservations pour éviter des enregistrements orphelins.

5) Modification critique d'un bien (Propriétaire)

- `ProprietaireController::editBien`:
  - Si des champs critiques changent (gérés par `BienModel::update`), notifier les locataires avec réservations à venir (`type=bien_change`).

## Méthodes utilitaires ajoutées

- `ReservationModel::deleteByBien(int $id_biens)` — pour supprimer les réservations d'un bien (utilisé lors de la suppression d'un bien).

## Installation et déploiement

1. Appliquer la migration SQL:

```
-- Sur votre base de données cible
SOURCE sql_updates/add_notifications_system.sql;
```

2. Déployer le code PHP/JS mis à jour (les fichiers listés ci-dessous).

3. Vider le cache d'opcode PHP si applicable (opcache).

## Fichiers ajoutés/modifiés

- Ajout:
  - `sql_updates/add_notifications_system.sql`
  - `app/Models/NotificationModel.php`
  - `app/Controllers/NotificationController.php`
  - `public/js/notifications.js`
- Modif:
  - `public/index.php` (routes API)
  - `app/Controllers/ReservationController.php` (notif sur création + annulation)
  - `app/Controllers/AdminController.php` (notif sur création/modification/suppression réservation + suppression bien)
  - `app/Controllers/ProprietaireController.php` (notif sur modification critique/suppression bien)
  - `app/Models/ReservationModel.php` (ajout `deleteByBien`)
  - `app/Views/layout/navbar.php` (UI cloche + panneau)

## Sécurité et performances

- Les endpoints API exigent un utilisateur connecté via `AuthMiddleware`.
- Les réponses sont limitées (`limit=20` par défaut) pour le listing.
- Index composés sur (`user_id`, `is_read`, `created_at`) pour des compteurs rapides.

## Évolutions possibles

- Pagination/chargement progressif dans le panneau.
- Types de notifications supplémentaires (validation/refus d'un bien, commentaires, etc.).
- Envoi d'emails en plus des notifications in‑app.
- WebSocket/SSE pour du temps réel (remplacer le polling).

## Tests manuels rapides

1. Créer une réservation en tant que locataire → le propriétaire voit une notification (badge augmente).
2. Annuler une réservation en tant que locataire → le propriétaire reçoit une notification.
3. Modifier/supprimer une réservation via l'Admin → locataire et propriétaire reçoivent des notifications.
4. Supprimer un bien (Admin ou Proprio) → tous les locataires ayant des réservations à venir sont notifiés; badge côté locataire > 0.
5. Ouvrir le panneau et cliquer sur "Tout marquer comme lu" → le badge revient à 0.
