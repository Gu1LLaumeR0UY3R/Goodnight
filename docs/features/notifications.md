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

Migration: `database/goodnight.sql`

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
SOURCE database/goodnight.sql;
```

2. Déployer le code PHP/JS mis à jour (les fichiers listés ci-dessous).

3. Vider le cache d'opcode PHP si applicable (opcache).

## Fichiers ajoutés/modifiés

- Ajout:
  - `database/goodnight.sql`
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


---

## Notifications de validation

## 📌 Objectif

Envoyer automatiquement une notification au propriétaire lorsqu'un administrateur valide son bien.

## 🔧 Fichier modifié

### [app/Controllers/AdminController.php](../../app/Controllers/AdminController.php)

**Méthode concernée :** `validerBien()`

**Modification :** Ajout d'une notification après la validation du bien

```php
// Notification au propriétaire
try {
    $notifModel = new NotificationModel();
    $notifModel->create([
        'user_id' => $bien['proprietaire_id'],
        'type' => 'bien_validated',
        'message' => "Votre bien \"{$bien['nom']}\" a été validé par un administrateur et est maintenant visible publiquement.",
        'bien_id' => $bienId
    ]);
} catch (Exception $e) {
    error_log("Erreur lors de la création de la notification : " . $e->getMessage());
}
```

## 📊 Flux de données

1. **Admin valide un bien** → Clique sur "Valider" dans le panneau admin
2. **Statut mis à jour** → `statut_validation = 'valide'` dans la base de données
3. **Notification créée** → Insérée dans la table `notifications`
4. **Propriétaire informé** → Voit la notification dans son interface

## ✅ Comment tester

### Étape 1 : Préparer un bien en attente
1. Connectez-vous en tant que propriétaire
2. Ajoutez un nouveau bien (il sera automatiquement en attente de validation)

### Étape 2 : Valider le bien
1. Déconnectez-vous
2. Connectez-vous en tant qu'administrateur
3. Allez dans le panneau admin → Section "Biens"
4. Trouvez le bien en attente
5. Cliquez sur le bouton "Valider"

### Étape 3 : Vérifier la notification
1. Déconnectez-vous
2. Reconnectez-vous en tant que propriétaire
3. Vérifiez l'icône de notifications (cloche)
4. Vous devriez voir : "Votre bien [nom] a été validé..."

## 🔍 Résultat attendu

**Dans l'interface propriétaire :**
- Badge rouge avec le nombre de nouvelles notifications
- Message clair avec le nom du bien validé
- Possibilité de cliquer pour voir le détail

**En base de données :**
```sql
SELECT * FROM notifications 
WHERE user_id = [proprietaire_id] 
AND type = 'bien_validated'
ORDER BY created_at DESC;
```

## 💡 Notes techniques

- **Gestion d'erreur :** Le `try/catch` empêche un échec de notification de bloquer la validation
- **Journalisation :** Les erreurs sont enregistrées dans le log PHP (`error_log`)
- **Type de notification :** `bien_validated` permet un filtrage spécifique si besoin
- **Référence :** Le `bien_id` est stocké pour lier notification et bien

## 🚀 Améliorations possibles

1. **Template de notification** : Externaliser le message dans un fichier de config
2. **Email complémentaire** : Envoyer aussi un email au propriétaire
3. **Notification de refus** : Créer une notification similaire si le bien est refusé
4. **Historique** : Afficher toutes les validations dans le profil propriétaire

---
[← Retour au sommaire](../README.md) | [← Documentation principale](../README.md)
