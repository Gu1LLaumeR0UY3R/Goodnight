# Multi-suppression dans l'administration

Les pages de gestion de l'administration permettent de cocher plusieurs lignes puis de les supprimer en une seule action.

## Pages concernées

| Page | Route AJAX | Règles |
|---|---|---|
| Utilisateurs | `POST /admin/deleteMultipleUsers` | Supprime aussi leurs rôles, réservations, avis et favoris (cascade) |
| Rôles | `POST /admin/deleteMultipleRoles` | Les rôles système 1 (Propriétaire) et 2 (Locataire) sont protégés |
| Biens | `POST /admin/deleteMultipleBiens` | Prévient les locataires avec une réservation à venir, supprime réservations et tarifs |
| Types de biens | `POST /admin/deleteMultipleTypesBiens` | Refusé si un bien utilise encore le type |
| Saisons | `POST /admin/deleteMultipleSaisons` | Supprime aussi les tarifs liés |
| Réservations | `POST /admin/deleteMultipleReservations` | Notifie le locataire et le propriétaire |

La page **Communes** n'a volontairement pas de suppression : les communes sont des données de référence (liste officielle) et `CommuneModel::delete()` est désactivé.

## Fonctionnement

- Côté navigateur : `public/js/admin-multi-delete.js` ajoute une case à cocher par ligne, une case « tout sélectionner » et un bouton « Supprimer la sélection », puis envoie la liste des identifiants.
- Côté serveur : chaque méthode de `AdminController` délègue à `deleteMultiple()`, qui supprime les éléments un par un. Un élément protégé ou encore utilisé est compté comme « non supprimé » sans bloquer les autres.

## Contrat de l'API

Requête : `POST` en `application/json`, corps `{"ids": [1, 2, 3]}` (500 identifiants maximum).
Les autres types de contenu sont refusés (HTTP 415), ce qui empêche une requête envoyée depuis un autre site (CSRF).

Réponse :

```json
{ "success": true, "deleted": 2, "failed": 1, "message": "2 réservation(s) supprimé(s) avec succès ; 1 non supprimé(s) (protégé ou encore utilisé)" }
```

`success` vaut `false` si aucun élément n'a pu être supprimé. L'accès est réservé au rôle Administrateur.
