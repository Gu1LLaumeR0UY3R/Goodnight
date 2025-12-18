# ✨ Graphiques Superposés - Résumé Technique

## 🎯 Ce qui a été Modifié

**Un seul graphique a été changé** : Le graphique 2 (Revenus)

**Avant** : 1 courbe = Revenus uniquement
**Après** : 2 courbes = Réservations + Revenus ensemble

---

## 📊 Architecture du Graphique

```
┌──────────────────────────────────────────────────────┐
│  Graphique 2 : Réservations & Revenus                │
├──────────────────────────────────────────────────────┤
│                                                       │
│  Revenus (€) |                                       │
│              |   ╱╲    ╱╲    ╱╲                      │
│         5000€|  ╱  ╲  ╱  ╲  ╱  ╲                     │
│              | ╱    ╲╱    ╲╱     ╲                    │
│         2500€|╱                   ╲╱╲                 │
│              |                       ╲                │
│            0€|_________________________╲___           │
│              │                                         │
│              └────────────────────────────────────→   │
│                   Lun  Mar  Mer  Jeu  Ven            │
│                                                       │
│  Réservations (Axe Gauche, Bleu) ──────             │
│  Revenus      (Axe Droit, Vert)  ──────             │
│                                                       │
│  Légende : [Réservations ▢] [Revenus (€) ▢]        │
└──────────────────────────────────────────────────────┘
```

---

## 🔄 Flux de Données

```
┌──────────────────────────────────────┐
│  Backend (Controller)                 │
│  ProprietaireStatsController         │
│                                      │
│  getStats() retourne :               │
│  {                                   │
│    labels: ["lun", "mar", ...],     │
│    reservations: [10, 15, ...],     │
│    revenue: [500, 750, ...],        │
│    comparison: {...}                │
│  }                                   │
└──────────────────────────────────────┘
                 ↓
        (JSON API /proprietaire/stats)
                 ↓
┌──────────────────────────────────────┐
│  Frontend (JavaScript)                │
│  updateCharts()                      │
│                                      │
│  Dataset 1 (Réservations)            │
│  → chartInstances.revenue.data       │
│     .datasets[0].data = data.        │
│     reservations                      │
│                                      │
│  Dataset 2 (Revenus)                 │
│  → chartInstances.revenue.data       │
│     .datasets[1].data = data.        │
│     revenue                           │
└──────────────────────────────────────┘
                 ↓
┌──────────────────────────────────────┐
│  Chart.js                            │
│  Affiche 2 courbes                   │
│  sur 2 axes séparés                  │
└──────────────────────────────────────┘
```

---

## 📐 Configuration Chart.js

### Structure du Graphique

```javascript
type: 'line'                           // Type : Courbe (line chart)

datasets: [
    {                                  // Dataset 1 : Réservations
        label: 'Réservations',
        data: [...],                   // Données : nombre de réservations
        borderColor: 'bleu',           // Couleur : bleu
        yAxisID: 'y'                   // Axe : gauche
    },
    {                                  // Dataset 2 : Revenus
        label: 'Revenus (€)',
        data: [...],                   // Données : montant en euros
        borderColor: 'vert',           // Couleur : vert
        yAxisID: 'y1'                  // Axe : droit
    }
]

scales: {
    y: {                               // Axe gauche
        position: 'left',
        title: 'Réservations'
    },
    y1: {                              // Axe droit
        position: 'right',
        title: 'Revenus (€)'
    }
}
```

---

## 🎨 Couleurs et Styles

### Dataset 1 - Réservations

```javascript
{
    borderColor: 'rgba(59, 130, 246, 1)',      // Bleu solide
    backgroundColor: 'rgba(59, 130, 246, 0.1)', // Bleu transparent (fill)
    borderWidth: 3,                             // Ligne épaisse
    fill: true,                                 // Remplir sous la courbe
    tension: 0.4                                // Courbe lisse
}
```

**Résultat** : Courbe bleue lisse avec zone ombrée légère

### Dataset 2 - Revenus

```javascript
{
    borderColor: 'rgba(16, 185, 129, 1)',      // Vert solide
    backgroundColor: 'rgba(16, 185, 129, 0.1)', // Vert transparent (fill)
    borderWidth: 3,                             // Ligne épaisse
    fill: true,                                 // Remplir sous la courbe
    tension: 0.4                                // Courbe lisse
}
```

**Résultat** : Courbe verte lisse avec zone ombrée légère

---

## 🎯 Cas d'Utilisation

### Analyse 1 : Corrélation Réservations/Revenus

**Situation** : Les deux courbes montent ensemble
```
  50 réservations → 5000€ de revenus
```

**Interprétation** : ✅ Corrélation normale - plus de réservations = plus de revenus

### Analyse 2 : Anomalie Détectée

**Situation** : Les courbes divergent
```
  50 réservations → 1000€ de revenus (au lieu de 5000€)
```

**Interprétation** : ⚠️ Anomalie - tarifs trop bas ou biens moins chers

### Analyse 3 : Tendance Positive

**Situation** : Les deux courbes remontent
```
  Semaine 1 : 20 réservations, 2000€
  Semaine 2 : 35 réservations, 3500€
```

**Interprétation** : 📈 Croissance positive

### Analyse 4 : Tendance Négative

**Situation** : Les deux courbes baissent
```
  Juillet : 100 réservations, 10000€
  Août : 40 réservations, 4000€
```

**Interprétation** : 📉 Baisse saisonnière

---

## 🔧 Code Minimum pour Tester

Si vous voulez juste voir comment ça marche, voici le code minimal :

```javascript
// Initialiser le graphique avec 2 datasets
const ctx = document.getElementById('chart-revenue').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
        datasets: [
            {
                label: 'Réservations',
                data: [10, 15, 12, 20, 18, 25, 22],
                borderColor: 'rgba(59, 130, 246, 1)',
                yAxisID: 'y'
            },
            {
                label: 'Revenus (€)',
                data: [1000, 1500, 1200, 2000, 1800, 2500, 2200],
                borderColor: 'rgba(16, 185, 129, 1)',
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        scales: {
            y: { position: 'left' },
            y1: { position: 'right' }
        }
    }
});
```

**Résultat** : 2 courbes superposées !

---

## 📊 Périodes Supportées

Le graphique fonctionne avec les 4 périodes :

| Période | Labels | Données | Utilité |
|---------|--------|---------|---------|
| **24 heures** | "0h", "1h", ..., "23h" | Réservations/revenus par heure | Tendances court terme |
| **7 jours** | "12/12", "13/12", ... | Réservations/revenus par jour | Analyse hebdomadaire |
| **12 mois** | "Janv.", "Févr.", ... | Réservations/revenus par mois | Analyse annuelle |
| **Années** | "2022", "2023", ... | Réservations/revenus par année | Historique complet |

---

## ✅ Points Clés à Retenir

1. **2 Datasets** : Réservations et Revenus
2. **2 Axes** : Axe gauche (nombre) et axe droit (€)
3. **2 Couleurs** : Bleu et vert pour distinction
4. **4 Périodes** : Fonctionne partout
5. **Dynamique** : Se met à jour en temps réel

---

## 🚀 Prochaines Étapes (Optionnel)

### Amélioration 1 : Moyenne Mobile

Ajouter une 3ème courbe avec la moyenne mobile (lisse les pics) :

```javascript
// Moyenne sur 3 jours
const movingAvg = data.reservations.map((val, i) => {
    const start = Math.max(0, i - 1);
    const end = i + 2;
    return data.reservations.slice(start, end).reduce((a, b) => a + b) / 3;
});
```

### Amélioration 2 : Zones de Confiance

Ajouter des bandes de confiance autour des courbes :

```javascript
// Bande à ±10% des données
const band = data.map(val => val * 0.1);
```

### Amélioration 3 : Interactivité

Cliquer sur un point pour voir les détails :

```javascript
onClick: (context) => {
    console.log('Données du point cliqué:', context);
}
```

### Amélioration 4 : Export

Télécharger le graphique en PNG :

```javascript
const image = chart.toBase64Image();
// Créer un lien de téléchargement
```

---

## 💡 Tips Chart.js

### Désactiver une Courbe

```javascript
// Cliquer sur "Réservations" dans la légende pour la masquer
// (fonctionne nativement avec Chart.js)
```

### Zoomer sur le Graphique

```javascript
// Plugin zoom (à installer séparément)
// npm install chartjs-plugin-zoom
```

### Agrandir le Graphique

```css
.chart-card {
    height: 400px;  /* Au lieu de auto */
}
```

---

## 🎓 Ressources

- **Chart.js Docs** : https://www.chartjs.org/docs/latest/
- **Multi-Axis** : https://www.chartjs.org/docs/latest/axes/
- **Plugin List** : https://www.chartjs.org/docs/latest/api/plugins_html.html

---

**✨ Votre système de statistiques est maintenant complet !**

*Documentation technique du 16 Décembre 2025*
