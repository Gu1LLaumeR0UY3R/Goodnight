# Système de Commentaires et Notes

## 📌 Objectif

Permettre aux locataires de laisser des commentaires et des notes sur les biens qu'ils ont réservés, avec un système de likes sur les commentaires pour mettre en avant les avis les plus utiles.

---

## 🗄️ Structure de la base de données

### Table `commentaires`

```sql
CREATE TABLE commentaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bien_id INT NOT NULL,
    locataire_id INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES bien(id) ON DELETE CASCADE,
    FOREIGN KEY (locataire_id) REFERENCES locataire(id) ON DELETE CASCADE
);
```

### Table `likes_commentaires` (optionnel)

```sql
CREATE TABLE likes_commentaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commentaire_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commentaire_id) REFERENCES commentaires(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (commentaire_id, user_id)
);
```

---

## 📁 Structure des fichiers

### Contrôleurs

**`app/Controllers/CommentaireController.php`**

Gestion des commentaires et likes :

```php
class CommentaireController extends BaseController {
    
    // Affiche les commentaires d'un bien
    public function index() {
        $bienId = $_GET['bien_id'] ?? null;
        $commentaires = $this->commentaireModel->getByBien($bienId);
        // ...
    }
    
    // Crée un nouveau commentaire
    public function create() {
        // Vérification : l'utilisateur a-t-il réservé ce bien ?
        // Insertion en base
    }
    
    // Like un commentaire
    public function like() {
        $commentaireId = $_POST['commentaire_id'];
        // Toggle like/unlike
    }
    
    // Supprime un commentaire (admin ou auteur)
    public function delete($id) {
        // Vérification des permissions
        // Suppression
    }
}
```

### Modèles

**`app/Models/CommentaireModel.php`**

Accès aux données des commentaires :

```php
class CommentaireModel extends Model {
    
    // Récupère tous les commentaires d'un bien
    public function getByBien($bienId) {
        $sql = "SELECT c.*, l.nom, l.prenom, l.pfp,
                       COUNT(lc.id) as like_count
                FROM commentaires c
                LEFT JOIN locataire l ON c.locataire_id = l.id
                LEFT JOIN likes_commentaires lc ON c.id = lc.commentaire_id
                WHERE c.bien_id = :bien_id
                GROUP BY c.id
                ORDER BY c.date_creation DESC";
        // ...
    }
    
    // Crée un commentaire
    public function create($data) {
        $sql = "INSERT INTO commentaires (bien_id, locataire_id, note, commentaire)
                VALUES (:bien_id, :locataire_id, :note, :commentaire)";
        // ...
    }
    
    // Calcule la note moyenne d'un bien
    public function getAverageNote($bienId) {
        $sql = "SELECT AVG(note) as moyenne, COUNT(*) as total
                FROM commentaires
                WHERE bien_id = :bien_id";
        // ...
    }
    
    // Vérifie si un utilisateur a déjà commenté
    public function hasCommented($bienId, $locataireId) {
        $sql = "SELECT COUNT(*) FROM commentaires
                WHERE bien_id = :bien_id AND locataire_id = :locataire_id";
        // ...
    }
    
    // Gère les likes
    public function toggleLike($commentaireId, $userId) {
        // Si like existe : supprimer
        // Sinon : ajouter
    }
}
```

### Vues

**`app/Views/bien/details.php`**

Affichage des commentaires sur la page de détail d'un bien :

```php
<div class="commentaires-section">
    <h3>Avis des voyageurs</h3>
    
    <?php if (!empty($commentaires)): ?>
        <div class="note-moyenne">
            <span class="note"><?= number_format($noteMoyenne, 1) ?></span>
            <span class="etoiles"><?= str_repeat('⭐', round($noteMoyenne)) ?></span>
            <span class="total">(<?= count($commentaires) ?> avis)</span>
        </div>
        
        <?php foreach ($commentaires as $comm): ?>
            <div class="commentaire-card">
                <div class="commentaire-header">
                    <img src="/pfp/<?= $comm['pfp'] ?>" alt="Avatar">
                    <div class="info">
                        <strong><?= htmlspecialchars($comm['prenom']) ?></strong>
                        <span class="date"><?= date('d/m/Y', strtotime($comm['date_creation'])) ?></span>
                    </div>
                    <div class="note-commentaire">
                        <?= str_repeat('⭐', $comm['note']) ?>
                    </div>
                </div>
                
                <p class="commentaire-texte">
                    <?= nl2br(htmlspecialchars($comm['commentaire'])) ?>
                </p>
                
                <div class="commentaire-actions">
                    <button class="btn-like" data-id="<?= $comm['id'] ?>">
                        👍 Utile (<?= $comm['like_count'] ?>)
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="no-comments">Aucun avis pour l'instant. Soyez le premier !</p>
    <?php endif; ?>
    
    <?php if ($userHasReserved && !$userHasCommented): ?>
        <div class="add-comment-section">
            <h4>Laisser un avis</h4>
            <form action="/commentaire/create" method="POST">
                <input type="hidden" name="bien_id" value="<?= $bien['id'] ?>">
                
                <div class="form-group">
                    <label>Note</label>
                    <div class="stars-input">
                        <input type="radio" name="note" value="5" id="star5">
                        <label for="star5">⭐</label>
                        <input type="radio" name="note" value="4" id="star4">
                        <label for="star4">⭐</label>
                        <input type="radio" name="note" value="3" id="star3">
                        <label for="star3">⭐</label>
                        <input type="radio" name="note" value="2" id="star2">
                        <label for="star2">⭐</label>
                        <input type="radio" name="note" value="1" id="star1">
                        <label for="star1">⭐</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="commentaire">Votre avis</label>
                    <textarea name="commentaire" id="commentaire" rows="5" required></textarea>
                </div>
                
                <button type="submit" class="btn-submit">Publier mon avis</button>
            </form>
        </div>
    <?php endif; ?>
</div>
```

### Styles CSS

**`public/css/commentaires.css`**

```css
.commentaires-section {
    margin-top: 3rem;
    padding: 2rem;
    background: var(--bg-card);
    border-radius: 12px;
}

.note-moyenne {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.note-moyenne .note {
    font-size: 3rem;
    font-weight: 700;
    color: var(--accent-primary);
}

.note-moyenne .etoiles {
    font-size: 1.5rem;
}

.note-moyenne .total {
    color: var(--text-secondary);
}

.commentaire-card {
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    transition: transform 0.2s;
}

.commentaire-card:hover {
    transform: translateX(4px);
}

.commentaire-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.commentaire-header img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.commentaire-header .info strong {
    display: block;
    color: var(--text-primary);
}

.commentaire-header .date {
    color: var(--text-secondary);
    font-size: 0.85rem;
}

.note-commentaire {
    margin-left: auto;
    font-size: 1.2rem;
}

.commentaire-texte {
    color: var(--text-primary);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.commentaire-actions {
    display: flex;
    gap: 1rem;
}

.btn-like {
    background: transparent;
    border: 1px solid var(--border-color);
    padding: 0.5rem 1rem;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-like:hover {
    background: var(--accent-primary);
    color: white;
    border-color: var(--accent-primary);
}

.btn-like.liked {
    background: var(--accent-primary);
    color: white;
}

/* Formulaire d'ajout de commentaire */
.add-comment-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid var(--border-color);
}

.stars-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.5rem;
}

.stars-input input[type="radio"] {
    display: none;
}

.stars-input label {
    font-size: 2rem;
    cursor: pointer;
    transition: color 0.2s;
    color: #ddd;
}

.stars-input input:checked ~ label,
.stars-input label:hover,
.stars-input label:hover ~ label {
    color: #f59e0b;
}
```

### JavaScript

**`public/js/commentaires.js`**

```javascript
// Gestion des likes sur les commentaires
document.querySelectorAll('.btn-like').forEach(btn => {
    btn.addEventListener('click', async function() {
        const commentaireId = this.dataset.id;
        
        try {
            const response = await fetch('/commentaire/like', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ commentaire_id: commentaireId })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Mise à jour du compteur
                const countSpan = this.textContent.match(/\d+/)[0];
                const newCount = data.liked ? parseInt(countSpan) + 1 : parseInt(countSpan) - 1;
                this.innerHTML = `👍 Utile (${newCount})`;
                
                // Toggle classe liked
                this.classList.toggle('liked', data.liked);
            }
        } catch (error) {
            console.error('Erreur lors du like:', error);
        }
    });
});

// Animation des étoiles au survol
const starsInput = document.querySelector('.stars-input');
if (starsInput) {
    const labels = starsInput.querySelectorAll('label');
    labels.forEach((label, index) => {
        label.addEventListener('mouseenter', () => {
            labels.forEach((l, i) => {
                if (i >= index) {
                    l.style.color = '#f59e0b';
                }
            });
        });
    });
    
    starsInput.addEventListener('mouseleave', () => {
        const checked = starsInput.querySelector('input:checked');
        if (!checked) {
            labels.forEach(l => l.style.color = '#ddd');
        }
    });
}
```

---

## 🚀 Installation

### Script SQL

```sql
-- Si vous n'avez pas encore la table
CREATE TABLE IF NOT EXISTS commentaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bien_id INT NOT NULL,
    locataire_id INT NOT NULL,
    note INT NOT NULL CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bien_id) REFERENCES bien(id) ON DELETE CASCADE,
    FOREIGN KEY (locataire_id) REFERENCES locataire(id) ON DELETE CASCADE
);

-- Table pour les likes (optionnel)
CREATE TABLE IF NOT EXISTS likes_commentaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commentaire_id INT NOT NULL,
    user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commentaire_id) REFERENCES commentaires(id) ON DELETE CASCADE,
    UNIQUE KEY unique_like (commentaire_id, user_id)
);
```

### Routes à ajouter

Dans `public/index.php` :

```php
case 'commentaire/create':
    require_once __DIR__ . '/../app/Controllers/CommentaireController.php';
    $controller = new CommentaireController();
    $controller->create();
    break;

case 'commentaire/like':
    require_once __DIR__ . '/../app/Controllers/CommentaireController.php';
    $controller = new CommentaireController();
    $controller->like();
    break;

case 'commentaire/delete':
    require_once __DIR__ . '/../app/Controllers/CommentaireController.php';
    $controller = new CommentaireController();
    $controller->delete($_POST['id'] ?? null);
    break;
```

---

## 💻 Utilisation

### Pour les locataires

1. **Consulter les avis** : Visibles sur chaque page de bien
2. **Laisser un avis** : Seulement si le locataire a réservé le bien
3. **Liker un avis** : Cliquer sur le bouton "Utile"

### Pour les propriétaires

1. **Voir les avis** : Sur la page de détail de leurs biens
2. **Répondre aux avis** : (Fonctionnalité à implémenter)

### Pour les administrateurs

1. **Modérer les avis** : Supprimer les avis inappropriés
2. **Voir les statistiques** : Moyennes, totaux, etc.

---

## ✅ Règles de gestion

### Qui peut commenter ?

- ✅ Locataires ayant **réservé et séjourné** dans le bien
- ❌ Locataires n'ayant jamais réservé
- ❌ Propriétaires (conflit d'intérêts)
- ❌ Utilisateurs non connectés

### Limites

- **Un seul commentaire par bien et par locataire**
- **Note obligatoire** entre 1 et 5 étoiles
- **Longueur minimale** du commentaire : 20 caractères
- **Modération** : Les administrateurs peuvent supprimer

### Calcul de la note moyenne

```php
// Moyenne pondérée simple
$noteMoyenne = AVG(note) FROM commentaires WHERE bien_id = X;

// Affichage avec 1 décimale
echo number_format($noteMoyenne, 1);
```

---

## 🎯 Fonctionnalités avancées

### 1. Réponses du propriétaire

Permettre au propriétaire de répondre aux commentaires :

```sql
ALTER TABLE commentaires ADD COLUMN reponse_proprietaire TEXT NULL;
ALTER TABLE commentaires ADD COLUMN date_reponse TIMESTAMP NULL;
```

### 2. Signalement d'avis

Permettre aux utilisateurs de signaler des avis inappropriés :

```sql
CREATE TABLE signalements_commentaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    commentaire_id INT NOT NULL,
    user_id INT NOT NULL,
    motif TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commentaire_id) REFERENCES commentaires(id) ON DELETE CASCADE
);
```

### 3. Tri et filtres

- Tri par date (plus récents / plus anciens)
- Tri par note (meilleures / moins bonnes)
- Tri par popularité (nombre de likes)
- Filtre par note (afficher seulement 5⭐, 4⭐+, etc.)

### 4. Images dans les commentaires

Permettre aux locataires d'ajouter des photos :

```sql
ALTER TABLE commentaires ADD COLUMN photos JSON NULL;
```

---

## 🔍 Vérifications de sécurité

### Validation côté serveur

```php
public function create() {
    // Vérifier que l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        return $this->redirect('/login');
    }
    
    // Vérifier que l'utilisateur a réservé ce bien
    $hasReserved = $this->reservationModel->hasReservedBien(
        $_POST['bien_id'],
        $_SESSION['user_id']
    );
    
    if (!$hasReserved) {
        $_SESSION['error'] = "Vous devez avoir réservé ce bien pour laisser un avis.";
        return $this->redirect('/bien/' . $_POST['bien_id']);
    }
    
    // Vérifier qu'il n'a pas déjà commenté
    $hasCommented = $this->commentaireModel->hasCommented(
        $_POST['bien_id'],
        $_SESSION['user_id']
    );
    
    if ($hasCommented) {
        $_SESSION['error'] = "Vous avez déjà laissé un avis pour ce bien.";
        return $this->redirect('/bien/' . $_POST['bien_id']);
    }
    
    // Valider la note
    $note = (int)$_POST['note'];
    if ($note < 1 || $note > 5) {
        $_SESSION['error'] = "La note doit être entre 1 et 5.";
        return $this->redirect('/bien/' . $_POST['bien_id']);
    }
    
    // Valider le commentaire
    $commentaire = trim($_POST['commentaire']);
    if (strlen($commentaire) < 20) {
        $_SESSION['error'] = "Le commentaire doit contenir au moins 20 caractères.";
        return $this->redirect('/bien/' . $_POST['bien_id']);
    }
    
    // Insérer le commentaire
    $this->commentaireModel->create([
        'bien_id' => $_POST['bien_id'],
        'locataire_id' => $_SESSION['user_id'],
        'note' => $note,
        'commentaire' => $commentaire
    ]);
    
    $_SESSION['success'] = "Votre avis a été publié avec succès !";
    return $this->redirect('/bien/' . $_POST['bien_id']);
}
```

### Protection XSS

```php
// Toujours échapper le HTML dans les vues
echo htmlspecialchars($commentaire['commentaire']);
echo nl2br(htmlspecialchars($commentaire['commentaire'])); // Avec sauts de ligne
```

---

## 💡 Notes techniques

### Performance

- **Index recommandés** :
  ```sql
  CREATE INDEX idx_bien_id ON commentaires(bien_id);
  CREATE INDEX idx_locataire_id ON commentaires(locataire_id);
  CREATE INDEX idx_date ON commentaires(date_creation);
  ```

- **Pagination** : Pour les biens avec beaucoup d'avis (LIMIT/OFFSET)

### Cache

Mettre en cache la note moyenne pour éviter de recalculer à chaque affichage :

```sql
ALTER TABLE bien ADD COLUMN note_moyenne DECIMAL(2,1) DEFAULT 0;
ALTER TABLE bien ADD COLUMN nb_commentaires INT DEFAULT 0;

-- Trigger pour mettre à jour automatiquement
CREATE TRIGGER update_note_moyenne AFTER INSERT ON commentaires
FOR EACH ROW
BEGIN
    UPDATE bien SET
        note_moyenne = (SELECT AVG(note) FROM commentaires WHERE bien_id = NEW.bien_id),
        nb_commentaires = (SELECT COUNT(*) FROM commentaires WHERE bien_id = NEW.bien_id)
    WHERE id = NEW.bien_id;
END;
```

---

## 🚀 Améliorations futures

1. **Notifications** : Alerter le propriétaire quand il reçoit un avis
2. **Modération automatique** : Détecter les contenus inappropriés
3. **Export** : Télécharger tous les avis en PDF
4. **Statistiques** : Dashboard des avis pour les propriétaires
5. **Badges** : "Voyageur vérifié", "Super hôte", etc.
6. **Traduction** : Traduire automatiquement les avis

---

[← Retour à la documentation](./README.md)
