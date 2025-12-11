<?php
// Vérifier si l'utilisateur est admin (même logique que les autres vues admin)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: /');
    exit;
}
?>

<link rel="stylesheet" href="/css/admin-content.css">
<link rel="stylesheet" href="/css/easter-eggs.css">

<div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h2>Gestion des Easter Eggs</h2>
            <p class="subtitle">Centralisez tous les easter eggs et leur contenu.</p>
        </div>
    </div>

    <div class="admin-content-section">
        <div class="tabs-container">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="cadres-tab">🎨 Cadres de profil</button>
                <button class="tab-btn" data-tab="other-tab">🔮 Autres easter eggs</button>
            </div>

            <!-- Cadres de profil -->
            <div id="cadres-tab" class="tab-content active">
                <div class="section-header">
                    <div>
                        <h3>Cadres de profil</h3>
                        <p class="text-muted">PNG applicables aux avatars, déverrouillés par l'easter egg.</p>
                    </div>
                    <a href="/admin/cadres/create" class="btn btn-primary">➕ Ajouter un cadre</a>
                </div>

                <div class="cards-grid">
                    <?php if (isset($cadres) && count($cadres) > 0): ?>
                        <?php foreach ($cadres as $cadre): ?>
                            <div class="card">
                                <div class="card-media">
                                    <?php if ($cadre['chemin_fichier']): ?>
                                        <img src="<?php echo htmlspecialchars($cadre['chemin_fichier']); ?>" alt="<?php echo htmlspecialchars($cadre['nom']); ?>">
                                    <?php else: ?>
                                        <div class="placeholder">Cadre par défaut</div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h4><?php echo htmlspecialchars($cadre['nom']); ?></h4>
                                    <?php if (!empty($cadre['description'])): ?>
                                        <p class="text-muted"><?php echo htmlspecialchars($cadre['description']); ?></p>
                                    <?php endif; ?>
                                    <p class="small text-muted">Chemin : <?php echo $cadre['chemin_fichier'] ? htmlspecialchars($cadre['chemin_fichier']) : 'Aucun fichier'; ?></p>
                                    <p class="small text-muted">Créé : <?php echo isset($cadre['date_creation']) ? date('d/m/Y', strtotime($cadre['date_creation'])) : 'N/A'; ?></p>
                                </div>
                                <div class="card-actions">
                                    <button class="btn btn-danger btn-ghost btn-small btn-delete-cadre" data-cadre-id="<?php echo $cadre['id']; ?>" data-cadre-nom="<?php echo htmlspecialchars($cadre['nom']); ?>">🗑️ Supprimer</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <p>Aucun cadre disponible.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Autres easter eggs -->
            <div id="other-tab" class="tab-content">
                <div class="section-header">
                    <div>
                        <h3>Autres easter eggs</h3>
                        <p class="text-muted">Documentation interne pour suivre les easter eggs existants.</p>
                    </div>
                </div>

                <div class="egg-list">
                    <div class="egg-card card">
                        <div class="egg-icon">🔐</div>
                        <div class="egg-body">
                            <h4>Cadres de profil</h4>
                            <p class="text-muted">Commentaire HTML caché dans la page profil. Découverte ➜ accès aux cadres PNG.</p>
                            <div class="badge">Localisation : /app/Views/profile/index.php</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-page {
    background: radial-gradient(circle at 20% 20%, rgba(255, 94, 98, 0.12), transparent 35%),
                radial-gradient(circle at 80% 0%, rgba(255, 153, 102, 0.18), transparent 30%),
                #0b1021;
    padding: 24px;
    min-height: 100%;
    color: #e7ecf5;
}

.admin-page-header h2 { margin: 0; color: #ffffff; }
.admin-page-header .subtitle { margin: 6px 0 0; color: #c9d1e1; }

.admin-content-section {
    margin-top: 24px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(12px);
}

.tabs-nav {
    display: flex;
    gap: 10px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.08);
    padding-bottom: 6px;
    flex-wrap: wrap;
}

.tab-btn {
    border: none;
    background: transparent;
    padding: 10px 14px;
    font-weight: 700;
    color: #9fb0ce;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.2s ease;
    letter-spacing: 0.2px;
}

.tab-btn:hover { color: #ff9a7a; }
.tab-btn.active { color: #ff9a7a; border-bottom-color: #ff9a7a; }

.tab-content { display: none; }
.tab-content.active { display: block; }

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin: 18px 0 14px;
}

.section-header h3 { margin: 0; color: #ffffff; }
.text-muted { color: #aab7d3; }
.small { font-size: 0.9rem; }

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: none;
    border-radius: 10px;
    padding: 11px 16px;
    cursor: pointer;
    font-weight: 700;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #ff5e62 0%, #ff9966 100%);
    color: #fff;
    box-shadow: 0 10px 24px rgba(255, 94, 98, 0.35);
}

.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 28px rgba(255, 94, 98, 0.45); }

.btn-ghost {
    background: rgba(255, 255, 255, 0.04);
    color: #ff9a7a;
    border: 1px solid rgba(255, 154, 122, 0.4);
}

.btn-danger { color: #ffb3a1; }
.btn-small { padding: 9px 12px; font-size: 0.9rem; }

.cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px;
}

.card {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 12px;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.35);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.card-media {
    height: 160px;
    background: rgba(255, 255, 255, 0.03);
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-media img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.placeholder { color: #aab7d3; font-size: 0.95rem; }

.card-body {
    padding: 14px 16px 8px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.card-body h4 { margin: 0; color: #ffffff; }
.card-body p { margin: 0; }

.card-actions {
    padding: 0 16px 14px;
    display: flex;
    justify-content: flex-end;
}

.empty-state {
    text-align: center;
    padding: 30px;
    color: #aab7d3;
    grid-column: 1 / -1;
}

.egg-list { display: flex; flex-direction: column; gap: 14px; margin-top: 10px; }

.egg-card {
    display: grid;
    grid-template-columns: 60px 1fr;
    align-items: center;
    gap: 12px;
}

.egg-icon { font-size: 2rem; text-align: center; }

.badge {
    display: inline-block;
    margin-top: 6px;
    padding: 6px 10px;
    background: rgba(255, 94, 98, 0.15);
    color: #ffb3a1;
    border-radius: 6px;
    font-size: 0.9rem;
    border: 1px solid rgba(255, 94, 98, 0.35);
}

@media (max-width: 700px) {
    .section-header { flex-direction: column; align-items: flex-start; }
    .card-media { height: 140px; }
}
</style>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

document.querySelectorAll('.btn-delete-cadre').forEach(btn => {
    btn.addEventListener('click', async () => {
        const cadreId = btn.dataset.cadreId;
        const cadreNom = btn.dataset.cadreNom;
        if (!confirm(`Êtes-vous sûr de vouloir supprimer le cadre "${cadreNom}" ?`)) return;

        try {
            const response = await fetch('/cadre/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: cadreId })
            });
            const data = await response.json();
            if (data.success) {
                alert('Cadre supprimé avec succès');
                location.reload();
            } else {
                alert('Erreur : ' + (data.error || 'Erreur inconnue'));
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        }
    });
});
</script>
<script src="/js/easter-eggs.js"></script>