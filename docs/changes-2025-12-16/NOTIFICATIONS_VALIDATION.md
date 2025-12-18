# Notifications de validation des biens

## 📌 Objectif

Envoyer automatiquement une notification au propriétaire lorsqu'un administrateur valide son bien.

## 🔧 Fichier modifié

### [app/Controllers/AdminController.php](../app/Controllers/AdminController.php)

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
[← Retour au sommaire](./README.md) | [← Documentation principale](../README.md)
