# Système de statistiques avec graphiques

## 📌 Objectif

Fournir au propriétaire des statistiques détaillées sur ses réservations et revenus avec :
- **4 graphiques interactifs** (Chart.js)
- **4 périodes d'analyse** : 24 heures, 7 derniers jours, 12 mois, toutes les années
- **Comparaison avec période précédente** (différence et pourcentage)
- **Calcul automatique des revenus** avec tarifs et saisons

## 🏗️ Architecture

```
Vue (index.php)
    ↓ Sélecteur de période
JavaScript (updateCharts)
    ↓ Fetch API
ProprietaireStatsController
    ↓ Logique métier
Models (Reservation, Bien, Tarif)
    ↓ SQL
Base de données
```

## 🔧 Fichiers créés et modifiés

### 1. Nouveau contrôleur : [app/Controllers/ProprietaireStatsController.php](../app/Controllers/ProprietaireStatsController.php)

#### Méthode principale : `getStats()`

Point d'entrée qui dispatch vers la méthode de période appropriée :

```php
public function getStats() {
    $period = $_GET['period'] ?? 'day';
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    
    switch($period) {
        case 'hour':
            return $this->getHourlyStats();
        case 'day':
            return $this->getLast7DaysStats();
        case 'month':
            return $this->getMonthlyStats($year);
        case 'year':
            return $this->getYearlyStats();
        default:
            return $this->getLast7DaysStats();
    }
}
```

#### Période 1 : `getHourlyStats()` - Dernières 24 heures

**But :** Voir l'activité heure par heure

**Labels :** `["0h", "1h", "2h", ..., "23h"]`

**Logique :**
```php
for ($h = 0; $h < 24; $h++) {
    $startHour = (clone $now)->sub(new DateInterval("PT" . (23 - $h) . "H"));
    $endHour = (clone $startHour)->add(new DateInterval("PT1H"));
    
    // Compte les réservations actives pendant cette heure
    $count = $reservationModel->countActiveReservationsInPeriod(
        $proprietaireId,
        $startHour->format('Y-m-d H:i:s'),
        $endHour->format('Y-m-d H:i:s')
    );
}
```

**Comparaison :** Avec les 24 heures précédentes (J-1)

#### Période 2 : `getLast7DaysStats()` - 7 derniers jours

**But :** Vue d'ensemble de la semaine

**Labels :** `["12/12", "13/12", "14/12", ..., "18/12"]` (format dd/mm)

**Logique :**
```php
for ($d = 0; $d < 7; $d++) {
    $date = (clone $now)->sub(new DateInterval("P" . (6 - $d) . "D"));
    $dateStr = $date->format('Y-m-d');
    
    $count = $reservationModel->countActiveReservationsOnDate(
        $proprietaireId,
        $dateStr
    );
}
```

**Comparaison :** Avec les 7 jours précédents (S-1)

#### Période 3 : `getMonthlyStats($year)` - 12 mois

**But :** Analyser une année complète

**Labels :** `["Janv.", "Févr.", "Mars", ..., "Déc."]`

**Logique :**
```php
for ($m = 1; $m <= 12; $m++) {
    $startDate = "$year-$m-01";
    $endDate = date('Y-m-t', strtotime($startDate)); // Dernier jour du mois
    
    $count = $reservationModel->countReservationsBetween(
        $proprietaireId,
        $startDate,
        $endDate
    );
    
    $revenue = $reservationModel->calculateRevenueBetween(
        $proprietaireId,
        $startDate,
        $endDate
    );
}
```

**Comparaison :** Avec l'année précédente (Y-1)

#### Période 4 : `getYearlyStats()` - Toutes les années

**But :** Vue historique complète

**Labels :** `["2022", "2023", "2024", "2025"]` (années dynamiques)

**Logique :**
```php
// Récupère toutes les années avec des réservations
$years = $reservationModel->getYearsWithReservations($proprietaireId);

foreach ($years as $year) {
    $count = $reservationModel->countReservationsInYear(
        $proprietaireId,
        $year
    );
    
    $revenue = $reservationModel->calculateRevenueInYear(
        $proprietaireId,
        $year
    );
}
```

**Comparaison :** Avec toutes les années précédentes (somme totale)

#### Calcul des comparaisons : `getComparisonData()`

**Objectif :** Comparer période actuelle vs période précédente

```php
private function getComparisonData($currentData, $previousData) {
    $current = [
        'reservations' => array_sum($currentData['reservations']),
        'revenue' => array_sum($currentData['revenue'])
    ];
    
    $previous = [
        'reservations' => array_sum($previousData['reservations']),
        'revenue' => array_sum($previousData['revenue'])
    ];
    
    return [
        'reservations' => [
            'current' => $current['reservations'],
            'previous' => $previous['reservations'],
            'diff' => $current['reservations'] - $previous['reservations'],
            'percent' => $previous['reservations'] > 0 
                ? round((($current['reservations'] - $previous['reservations']) / $previous['reservations']) * 100, 1)
                : 0
        ],
        'revenue' => [
            'current' => $current['revenue'],
            'previous' => $previous['revenue'],
            'diff' => $current['revenue'] - $previous['revenue'],
            'percent' => $previous['revenue'] > 0
                ? round((($current['revenue'] - $previous['revenue']) / $previous['revenue']) * 100, 1)
                : 0
        ]
    ];
}
```

#### Statistiques avancées : `getAdvancedStats()`

Taux d'occupation et revenus par bien :

```php
public function getAdvancedStats() {
    $biens = $bienModel->getByProprietaire($proprietaireId);
    $stats = [];
    
    foreach ($biens as $bien) {
        $totalDays = // Nombre de jours depuis création
        $reservedDays = $reservationModel->countReservedDays($bien['id']);
        $occupancyRate = ($reservedDays / $totalDays) * 100;
        
        $revenue = $reservationModel->calculateRevenueForBien($bien['id']);
        
        $stats[] = [
            'bien_name' => $bien['nom'],
            'occupancy_rate' => round($occupancyRate, 1),
            'revenue' => $revenue
        ];
    }
    
    return $stats;
}
```

### 2. Routes ajoutées : [public/index.php](../public/index.php)

```php
case 'proprietaire/stats':
    require_once __DIR__ . '/../app/Controllers/ProprietaireStatsController.php';
    $controller = new ProprietaireStatsController();
    $controller->getStats();
    break;

case 'proprietaire/stats/advanced':
    require_once __DIR__ . '/../app/Controllers/ProprietaireStatsController.php';
    $controller = new ProprietaireStatsController();
    $controller->getAdvancedStats();
    break;
```

### 3. Vue avec graphiques : [app/Views/proprietaire/index.php](../app/Views/proprietaire/index.php)

#### A. Section statistiques (HTML)

```html
<div class="stats-section">
    <h3>📊 Statistiques</h3>
    
    <!-- Sélecteur de période -->
    <div class="period-selector">
        <button class="btn-period active" onclick="changePeriod('hour')">
            24 heures
        </button>
        <button class="btn-period" onclick="changePeriod('day')">
            7 jours
        </button>
        <button class="btn-period" onclick="changePeriod('month')">
            Mois
        </button>
        <button class="btn-period" onclick="changePeriod('year')">
            Année
        </button>
    </div>
    
    <!-- Sélecteur d'année (visible seulement pour mois/année) -->
    <div class="year-selector" style="display:none;">
        <label>Année :</label>
        <select id="year-select" onchange="updateCharts()">
            <?php for($y = 2020; $y <= date('Y'); $y++): ?>
                <option value="<?= $y ?>" <?= $y == date('Y') ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
        </select>
    </div>
    
    <!-- Barre de comparaison -->
    <div class="comparison-bar" id="comparison-bar" style="display:none;">
        <!-- Rempli dynamiquement par JS -->
    </div>
    
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
        <div class="chart-card">
            <h4>Taux d'occupation (%)</h4>
            <canvas id="chart-occupancy"></canvas>
        </div>
        <div class="chart-card">
            <h4>Répartition des revenus</h4>
            <canvas id="chart-revenue-pie"></canvas>
        </div>
    </div>
</div>

<!-- Chargement de Chart.js depuis CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
```

#### B. JavaScript pour les graphiques

**Initialisation des graphiques :**

```javascript
let chartInstances = {
    reservations: null,
    revenue: null,
    occupancy: null,
    revenuePie: null
};

let currentPeriod = 'day';
let currentYear = new Date().getFullYear();

function initCharts() {
    // Configuration commune pour tous les graphiques
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: getComputedStyle(document.documentElement)
                        .getPropertyValue('--text-primary')
                }
            },
            x: {
                ticks: {
                    color: getComputedStyle(document.documentElement)
                        .getPropertyValue('--text-primary')
                }
            }
        }
    };
    
    // Graphique 1 : Réservations (Bar chart)
    const ctx1 = document.getElementById('chart-reservations').getContext('2d');
    chartInstances.reservations = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Réservations',
                data: [],
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2
            }]
        },
        options: commonOptions
    });
    
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
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
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
    
    // Graphique 3 : Taux d'occupation (Bar chart horizontal)
    const ctx3 = document.getElementById('chart-occupancy').getContext('2d');
    chartInstances.occupancy = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Taux d\'occupation',
                data: [],
                backgroundColor: 'rgba(245, 158, 11, 0.7)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 2
            }]
        },
        options: {
            ...commonOptions,
            indexAxis: 'y' // Horizontal
        }
    });
    
    // Graphique 4 : Répartition revenus (Doughnut chart)
    const ctx4 = document.getElementById('chart-revenue-pie').getContext('2d');
    chartInstances.revenuePie = new Chart(ctx4, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(139, 92, 246, 0.7)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'right'
                }
            }
        }
    });
}
```

**Mise à jour des graphiques :**

```javascript
async function updateCharts() {
    const year = document.getElementById('year-select').value;
    currentYear = year;
    
    try {
        // Fetch des données depuis l'API
        const response = await fetch(
            `/proprietaire/stats?period=${currentPeriod}&year=${year}`
        );
        const data = await response.json();
        
        // Mise à jour des graphiques 1 et 2 (période)
        chartInstances.reservations.data.labels = data.labels;
        chartInstances.reservations.data.datasets[0].data = data.reservations;
        chartInstances.reservations.update();
        
        // Mise à jour du graphique 2 avec les 2 courbes superposées
        chartInstances.revenue.data.labels = data.labels;
        chartInstances.revenue.data.datasets[0].data = data.reservations; // Courbe 1 : Réservations
        chartInstances.revenue.data.datasets[1].data = data.revenue;       // Courbe 2 : Revenus
        chartInstances.revenue.update();
        
        // Affichage de la comparaison
        if (data.comparison) {
            displayComparison(data.comparison);
        }
        
        // Fetch des stats avancées pour graphiques 3 et 4
        const advancedResponse = await fetch('/proprietaire/stats/advanced');
        const advancedData = await advancedResponse.json();
        
        // Mise à jour graphique 3 (occupation)
        chartInstances.occupancy.data.labels = advancedData.map(b => b.bien_name);
        chartInstances.occupancy.data.datasets[0].data = advancedData.map(b => b.occupancy_rate);
        chartInstances.occupancy.update();
        
        // Mise à jour graphique 4 (répartition)
        chartInstances.revenuePie.data.labels = advancedData.map(b => b.bien_name);
        chartInstances.revenuePie.data.datasets[0].data = advancedData.map(b => b.revenue);
        chartInstances.revenuePie.update();
        
    } catch (error) {
        console.error('Erreur lors du chargement des stats:', error);
    }
}
```

**Affichage de la comparaison :**

```javascript
function displayComparison(comparison) {
    const compBar = document.getElementById('comparison-bar');
    compBar.style.display = 'flex';
    
    const reservationsDiff = comparison.reservations.diff;
    const reservationsPercent = comparison.reservations.percent;
    const revenueDiff = comparison.revenue.diff;
    const revenuePercent = comparison.revenue.percent;
    
    // Classe CSS selon positif/négatif
    const resClass = reservationsDiff >= 0 ? 'positive' : 'negative';
    const revClass = revenueDiff >= 0 ? 'positive' : 'negative';
    
    // Symbole ↑ ou ↓
    const resSymbol = reservationsDiff >= 0 ? '↑' : '↓';
    const revSymbol = revenueDiff >= 0 ? '↑' : '↓';
    
    compBar.innerHTML = `
        <div class="comparison-item">
            <span class="comparison-label">Réservations :</span>
            <span class="comparison-value ${resClass}">
                ${resSymbol} ${Math.abs(reservationsDiff)} 
                (${reservationsPercent > 0 ? '+' : ''}${reservationsPercent}%)
            </span>
        </div>
        <div class="comparison-item">
            <span class="comparison-label">Revenus :</span>
            <span class="comparison-value ${revClass}">
                ${revSymbol} ${Math.abs(revenueDiff).toFixed(2)}€ 
                (${revenuePercent > 0 ? '+' : ''}${revenuePercent}%)
            </span>
        </div>
    `;
}
```

**Changement de période :**

```javascript
function changePeriod(period) {
    currentPeriod = period;
    
    // Mise à jour des boutons (classe active)
    document.querySelectorAll('.btn-period').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Affichage du sélecteur d'année seulement pour mois/année
    const yearSelector = document.querySelector('.year-selector');
    if (period === 'month' || period === 'year') {
        yearSelector.style.display = 'block';
    } else {
        yearSelector.style.display = 'none';
    }
    
    // Rechargement des données
    updateCharts();
}
```

### 4. Styles CSS : [public/css/dashboard-proprio.css](../public/css/dashboard-proprio.css)

```css
/* Section statistiques */
.stats-section {
    background: var(--bg-card);
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-top: 2rem;
}

/* Sélecteur de période */
.period-selector {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.btn-period {
    padding: 0.5rem 1rem;
    border: 2px solid var(--border-color);
    background: var(--bg-card);
    color: var(--text-primary);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-period.active {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

.btn-period:hover:not(.active) {
    border-color: var(--accent-primary);
    transform: translateY(-2px);
}

/* Sélecteur d'année */
.year-selector {
    margin-bottom: 1rem;
}

.year-selector select {
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background: var(--bg-card);
    color: var(--text-primary);
}

/* Barre de comparaison */
.comparison-bar {
    display: flex;
    gap: 2rem;
    padding: 1rem;
    background: rgba(59, 130, 246, 0.1);
    border-left: 4px solid var(--accent-primary);
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.comparison-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.comparison-label {
    font-weight: 600;
    color: var(--text-primary);
}

.comparison-value {
    font-weight: 700;
    font-size: 1.1rem;
}

.comparison-value.positive {
    color: #10b981;
}

.comparison-value.negative {
    color: #ef4444;
}

/* Grille de graphiques */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
}

.chart-card {
    background: var(--bg-card);
    padding: 1.5rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

.chart-card h4 {
    margin-bottom: 1rem;
    color: var(--text-primary);
    font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .comparison-bar {
        flex-direction: column;
        gap: 0.5rem;
    }
}
```

## ✅ Comment tester

### Test 1 : Période 24 heures
1. Ouvrez le tableau de bord propriétaire
2. Cliquez sur "24 heures"
3. **Vérifiez :** 24 barres (0h à 23h) sur le graphique
4. **Comparaison :** Différence avec les 24h précédentes

### Test 2 : Période 7 jours
1. Cliquez sur "7 jours"
2. **Vérifiez :** 7 points (dates au format dd/mm)
3. **Comparaison :** Différence avec les 7 jours précédents

### Test 3 : Période Mois
1. Cliquez sur "Mois"
2. **Vérifiez :** Le sélecteur d'année apparaît
3. **Vérifiez :** 12 barres (Janv. à Déc.)
4. Changez l'année → Graphiques mis à jour
5. **Comparaison :** Différence avec l'année précédente

### Test 4 : Période Année
1. Cliquez sur "Année"
2. **Vérifiez :** Toutes les années avec réservations
3. **Comparaison :** Différence avec total historique précédent

### Test 5 : Graphiques avancés
1. Vérifiez le graphique "Taux d'occupation"
2. **Doit afficher :** Chaque bien avec son %
3. Vérifiez le graphique "Répartition des revenus"
4. **Doit afficher :** Camembert avec couleurs distinctes

### Test 6 : Comparaison visuelle
1. Sur chaque période, vérifiez la barre de comparaison
2. **Si augmentation :** Flèche ↑ en vert, pourcentage positif
3. **Si diminution :** Flèche ↓ en rouge, pourcentage négatif

### Test 7 : Responsive
1. Réduisez la fenêtre (mobile)
2. Les graphiques doivent s'empiler en colonne
3. Les boutons de période doivent s'adapter

## 💡 Notes techniques

### Performance
- **Cache conseillé** : Les calculs de revenus peuvent être lourds (jointures multiples)
- **Limitation** : Seulement les biens du propriétaire connecté
- **Optimisation** : Indexes sur `date_debut`, `date_fin`, `proprietaire_id`

### Calcul des revenus
Le calcul prend en compte :
1. **Tarifs de base** : Table `tarifs`
2. **Tarifs saisonniers** : Table `saisons` (priorité sur tarifs de base)
3. **Nombre de nuits** : `DATEDIFF(date_fin, date_debut)`
4. **Formule** : `somme(prix_par_nuit × nombre_de_nuits)`

### Gestion des fuseaux horaires
- Utilisation de `DateTime` PHP avec `DateInterval`
- Les dates sont stockées en UTC dans la BDD
- Affichage dans le fuseau local du serveur

### Compatibilité Chart.js
- Version 4.4.0 utilisée (stable)
- Types supportés : `bar`, `line`, `doughnut`
- Options responsive activées par défaut

## 🚀 Améliorations possibles

1. **Export des graphiques** : Télécharger en PNG/PDF
2. **Filtres avancés** : Par bien, par commune, par type
3. **Prévisions** : Tendances avec machine learning
4. **Alertes** : Notification si baisse de 20%+
5. **Benchmarking** : Comparaison avec moyennes du marché
6. **Graphiques supplémentaires** :
   - Taux d'annulation
   - Durée moyenne de séjour
   - Origine des clients (si tracking)
7. **Dashboard en temps réel** : WebSocket pour mise à jour auto

## 🐛 Dépannage

### Graphiques vides
- Vérifiez que le propriétaire a des réservations
- Ouvrez la console navigateur (F12) pour voir les erreurs
- Testez l'API directement : `/proprietaire/stats?period=day`

### Comparaison à 0%
- Normal si c'est la première période enregistrée
- Vérifiez qu'il y a des données dans la période précédente

### Graphiques ne se mettent pas à jour
- Vérifiez que Chart.js est bien chargé (CDN)
- Testez dans un autre navigateur
- Videz le cache navigateur (Ctrl+F5)

---
[← Retour au sommaire](./README.md) | [← Documentation principale](../README.md)
