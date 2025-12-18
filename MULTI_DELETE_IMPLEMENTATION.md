# Système de Multi-Suppression Admin - Endpoints à Implémenter

## Vue d'ensemble
Le système de multi-suppression a été ajouté à toutes les pages admin concernées. Voici les endpoints qui doivent être créés dans le contrôleur Admin.

## Endpoints requis

### 1. Utilisateurs
**Endpoint**: `POST /admin/deleteMultipleUsers`
**Payload**: `{"ids": [1, 2, 3, ...]}`
**Action**: Supprimer les utilisateurs avec les IDs fournis

### 2. Rôles
**Endpoint**: `POST /admin/deleteMultipleRoles`
**Payload**: `{"ids": [1, 2, 3, ...]}`
**Action**: Supprimer les rôles avec les IDs fournis

### 3. Biens
**Endpoint**: `POST /admin/deleteMultipleBiens`
**Payload**: `{"ids": [1, 2, 3, ...]}`
**Action**: Supprimer les biens avec les IDs fournis

### 4. Types de Biens
**Endpoint**: `POST /admin/deleteMultipleTypesBiens`
**Payload**: `{"ids**: [1, 2, 3, ...]}`
**Action**: Supprimer les types de biens avec les IDs fournis

### 5. Saisons
**Endpoint**: `POST /admin/deleteMultipleSaisons`
**Payload**: `{"ids": [1, 2, 3, ...]}`
**Action**: Supprimer les saisons avec les IDs fournis

### 6. Réservations
**Endpoint**: `POST /admin/deleteMultipleReservations`
**Payload**: `{"ids": [1, 2, 3, ...]}`
**Action**: Supprimer les réservations avec les IDs fournis

### 7. Communes
**Endpoint**: `POST /admin/deleteMultipleCommunes`
**Payload**: `{"ids": [1, 2, 3, ...]}`
**Action**: Supprimer les communes avec les IDs fournis

## Format de réponse attendu

### Succès
```json
{
  "success": true,
  "deleted": 5,
  "message": "5 éléments supprimés avec succès"
}
```

### Erreur
```json
{
  "success": false,
  "message": "Erreur lors de la suppression: détails de l'erreur"
}
```

## Exemple d'implémentation dans AdminController.php

```php
public function deleteMultipleUsers() {
    header('Content-Type: application/json');
    
    try {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        if (!isset($data['ids']) || !is_array($data['ids'])) {
            echo json_encode([
                'success' => false,
                'message' => 'IDs invalides'
            ]);
            return;
        }
        
        $ids = array_filter($data['ids'], 'is_numeric');
        $deleted = 0;
        
        foreach ($ids as $id) {
            if ($this->model->deleteUser($id)) {
                $deleted++;
            }
        }
        
        echo json_encode([
            'success' => true,
            'deleted' => $deleted,
            'message' => "$deleted utilisateur(s) supprimé(s) avec succès"
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ]);
    }
}
```

## Fichiers modifiés

### Frontend (déjà fait)
- ✅ `/public/js/admin-multi-delete.js` - Script générique de multi-suppression
- ✅ `/public/css/admin-content.css` - Styles pour les checkboxes et bouton
- ✅ `/app/Views/admin/users.php` - Gestion utilisateurs
- ✅ `/app/Views/admin/roles.php` - Gestion rôles
- ✅ `/app/Views/admin/biens.php` - Gestion biens
- ✅ `/app/Views/admin/types_biens.php` - Gestion types de biens
- ✅ `/app/Views/admin/saisons.php` - Gestion saisons
- ✅ `/app/Views/admin/reservations.php` - Gestion réservations
- ✅ `/app/Views/admin/communes.php` - Gestion communes

### Backend (à implémenter)
- ⏳ Ajouter les 7 méthodes dans `AdminController.php`
- ⏳ Ajouter les routes correspondantes dans le routeur

## Fonctionnalités incluses

1. **Sélection multiple**: Checkbox sur chaque ligne + "Tout sélectionner"
2. **Bouton dynamique**: Apparaît seulement quand des éléments sont sélectionnés
3. **Compteur**: Affiche le nombre d'éléments sélectionnés
4. **Confirmation**: Demande de confirmation avant suppression
5. **Feedback**: Toast de notification après l'action
6. **Rechargement auto**: La page se recharge après succès
7. **Gestion d'erreurs**: Messages d'erreur clairs en cas de problème
8. **Visual feedback**: Les lignes sélectionnées sont highlightées en rouge

## Notes importantes

- Les checkboxes sont ajoutées dynamiquement par JavaScript
- Le système utilise les toasts existants définis dans `components.css`
- Compatible avec DataTables (les checkboxes s'ajoutent après l'initialisation)
- Le bouton utilise un style rouge vif pour attirer l'attention
