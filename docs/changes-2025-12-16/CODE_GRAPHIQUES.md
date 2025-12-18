# 📋 Code à Intégrer - Graphiques Courbes Superposées

## 🎯 Résumé des Modifications

Deux fichiers importants ont été modifiés :

1. **Controller** : `app/Controllers/ProprietaireStatsController.php` (pas de changement)
2. **Vue** : `app/Views/proprietaire/index.php` (JavaScript modifié)

---

## ✅ À Faire - Étape 1 : Mettre à Jour le HTML

**Fichier** : `app/Views/proprietaire/index.php`

Trouvez la section des graphiques et mettez à jour le titre du 2e graphique :

```html
<!-- Grille de graphiques -->
<div class="charts-grid">
    <div class="chart-card">
        <h4>Réservations</h4>
        <canvas id="chart-reservations"></canvas>
    </div>
    <div class="chart-card">
        <h4>📊 Réservations & Revenus (Superposés)</h4>
        <p style="font-size: 0.9rem; color: var(--text-secondary); margin: 0.5rem 0;">
            Deux courbes sur le même graphique avec axes séparés
        </p>
        <canvas id="chart-revenue"></canvas>
    </div>
    <!-- Autres graphiques... -->
</div>
```

---

## ✅ À Faire - Étape 2 : Modifier la Fonction initCharts()

**Fichier** : `app/Views/proprietaire/index.php`

Remplacez la création du graphique 2 par ce code :

```javascript
// Graphique 2 : Réservations & Revenus (Line chart avec 2 courbes superposées)
const ctx2 = document.getElementById('chart-revenue').getContext('2d');
chartInstances.revenue = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: [],
        datasets: [
            {
                label: 'Réservations',
                data: [],
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y' // Axe gauche
            },
            {
                label: 'Revenus (€)',
                data: [],
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1' // Axe droit
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Réservations',
                    color: 'rgba(59, 130, 246, 1)'
                },
                ticks: {
                    color: 'rgba(59, 130, 246, 1)'
                },
                grid: {
                    color: 'rgba(59, 130, 246, 0.1)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Revenus (€)',
                    color: 'rgba(16, 185, 129, 1)'
                },
                ticks: {
                    color: 'rgba(16, 185, 129, 1)'
                },
                grid: {
                    drawOnChartArea: false
                }
            },
            x: {
                ticks: {
                    color: getComputedStyle(document.documentElement)
                        .getPropertyValue('--text-primary')
                }
            }
        }
    }
});
```

---

## ✅ À Faire - Étape 3 : Modifier la Fonction updateCharts()

**Fichier** : `app/Views/proprietaire/index.php`

Dans la fonction `updateCharts()`, remplacez cette partie :

**❌ Avant** :
```javascript
chartInstances.revenue.data.labels = data.labels;
chartInstances.revenue.data.datasets[0].data = data.revenue;
chartInstances.revenue.update();
```

**✅ Après** :
```javascript
// Mise à jour du graphique 2 avec les 2 courbes superposées
chartInstances.revenue.data.labels = data.labels;
chartInstances.revenue.data.datasets[0].data = data.reservations; // Courbe 1 : Réservations
chartInstances.revenue.data.datasets[1].data = data.revenue;       // Courbe 2 : Revenus
chartInstances.revenue.update();
```

---

## 📋 Checklist

- [ ] ✅ Modifier le titre du graphique 2 dans le HTML
- [ ] ✅ Remplacer la fonction `initCharts()` - graphique 2
- [ ] ✅ Mettre à jour `updateCharts()` pour remplir les 2 datasets
- [ ] ✅ Tester en ouvrant `/proprietaire`
- [ ] ✅ Vérifier les 4 périodes (24h, 7j, mois, année)
- [ ] ✅ Vérifier en mode sombre

---

## 🧪 Tests Manuels

### Test 1 : Affichage des 2 Courbes

1. Ouvrez le dashboard propriétaire
2. Allez aux statistiques
3. Sélectionnez **"7 jours"**
4. Vous devriez voir :
   - ✅ Courbe bleue (Réservations)
   - ✅ Courbe verte (Revenus)
   - ✅ Axes séparés

### Test 2 : Légende

1. En haut du graphique
2. Vous devriez voir 2 entrées :
   - ✅ "Réservations" (bleu)
   - ✅ "Revenus (€)" (vert)

### Test 3 : Axes Colorés

1. Regardez à gauche : **"Réservations"** en bleu
2. Regardez à droite : **"Revenus (€)"** en vert

### Test 4 : Changement de Période

1. Cliquez sur "24 heures"
2. Les 2 courbes se mettent à jour
3. Cliquez sur "Mois"
4. Les 2 courbes se remettent à jour
5. ✅ Les données changent mais les 2 courbes restent

### Test 5 : Console Navigateur

1. Ouvrez F12 (Dev Tools)
2. Allez à l'onglet "Console"
3. Vérifiez qu'il n'y a pas d'erreurs rouges
4. ✅ Les courbes se dessinent sans erreur

---

## ⚠️ Points Importants

### 1. Ne Pas Supprimer les Données

Les 2 datasets partagent les mêmes `labels` (dates/heures) :

```javascript
chartInstances.revenue.data.datasets[0] // Réservations
chartInstances.revenue.data.datasets[1] // Revenus
// Les deux utilisent les mêmes labels
```

### 2. Axes Séparés

- **yAxisID: 'y'** = Axe gauche (Réservations)
- **yAxisID: 'y1'** = Axe droit (Revenus)

Ne pas confondre les IDs.

### 3. Couleurs Distinctes

- Bleu : Réservations (#3B82F6)
- Vert : Revenus (#10B981)

Les couleurs restent visibles en mode clair et sombre.

---

## 🔧 Dépannage

### Les courbes ne s'affichent pas

**Solution** :
1. Vérifiez que les données arrivent : `console.log(data);`
2. Vérifiez que les 2 datasets sont présents
3. Rechargez la page complètement (Ctrl+F5)

### Une seule courbe visible

**Cause** : Les 2 datasets n'ont pas les mêmes labels
**Solution** : Vérifiez que `data.reservations` et `data.revenue` ont la même longueur

### Les axes ne s'affichent pas correctement

**Solution** :
1. Vérifiez que `y` et `y1` sont bien définis dans `scales`
2. Vérifiez que `position: 'left'` et `position: 'right'` sont présents
3. Rechargez la page

### Les couleurs ne sont pas visibles

**Cause** : Conflit avec le thème
**Solution** : Utilisez des couleurs qui contrastent avec le fond du thème (déjà fait ✅)

---

## 📊 Résultat Attendu

Vous devriez voir quelque chose comme ceci :

```
┌─────────────────────────────────────────────┐
│  📊 Réservations & Revenus (Superposés)     │
│  Deux courbes sur le même graphique...       │
│                                              │
│  Revenus (€)        ╱╲         Réservations│
│      5000€ |        ╱  ╲                 50│
│            |       ╱    ╲                  │
│      2500€ |      ╱      ╲               25│
│            |     ╱        ╲╱              │
│         0€ |____╱________________          │
│            └─────────────────────         │
│              Lun Mar Mer Jeu...           │
│                                            │
│ ──── Réservations (Axe gauche, bleu)       │
│ ──── Revenus (Axe droit, vert)             │
└─────────────────────────────────────────────┘
```

---

## 💡 Tips

1. **Zoom sur le graphique** : Cliquez et drag pour zoomer
2. **Légende cliquable** : Cliquez sur "Réservations" pour la masquer
3. **Hover** : Survolez les courbes pour voir les valeurs exactes

---

**✨ Vos graphiques sont maintenant super informatifs !**

*Guide d'intégration du 16 Décembre 2025*
