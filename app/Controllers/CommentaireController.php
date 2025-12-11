<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/CommentaireModel.php";

/**
 * Contrôleur pour gérer les commentaires sur les biens
 * 
 * Routes principales :
 * - POST /commentaire/add : Ajouter un commentaire
 * - POST /commentaire/edit/{id} : Modifier son commentaire
 * - POST /commentaire/delete/{id} : Supprimer son commentaire
 * - POST /commentaire/signaler/{id} : Signaler un commentaire
 * 
 * @author GoodNight Team
 * @date 11/12/2025
 */
class CommentaireController extends BaseController {
    
    private $commentaireModel;
    
    public function __construct() {
        $this->commentaireModel = new CommentaireModel();
    }

    /**
     * Ajoute un nouveau commentaire sur un bien
     * 
     * @return void Redirige vers la page du bien
     */
    public function add() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour laisser un commentaire.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Récupérer les données du formulaire
        $idBien = $_POST['id_bien'] ?? null;
        $note = $_POST['note'] ?? null;
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');
        $idLocataire = $_SESSION['user_id'];

        // Validation des données
        if (!$idBien || !$contenu) {
            $_SESSION['error'] = "Tous les champs obligatoires doivent être remplis.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Validation de la note
        if ($note !== null && ($note < 1 || $note > 5)) {
            $_SESSION['error'] = "La note doit être comprise entre 1 et 5.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Vérifier que l'utilisateur n'a pas déjà commenté ce bien
        if ($this->commentaireModel->hasUserCommented($idBien, $idLocataire)) {
            $_SESSION['error'] = "Vous avez déjà commenté ce bien. Vous pouvez modifier votre commentaire existant.";
            $this->redirect("/bien/{$idBien}");
            return;
        }

        // Vérifier que l'utilisateur peut commenter (a réservé le bien)
        if (!$this->commentaireModel->canUserComment($idBien, $idLocataire)) {
            $_SESSION['error'] = "Vous devez avoir réservé ce bien pour pouvoir laisser un commentaire.";
            $this->redirect("/bien/{$idBien}");
            return;
        }

        // Créer le commentaire
        $data = [
            'id_biens' => $idBien,
            'id_locataire' => $idLocataire,
            'note' => $note,
            'titre' => $titre ?: null,
            'contenu' => $contenu,
            'statut' => 'publie' // Publié directement (peut être modifié pour une modération)
        ];

        $result = $this->commentaireModel->create($data);

        if ($result) {
            $_SESSION['success'] = "Votre commentaire a été publié avec succès !";
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de la publication de votre commentaire.";
        }

        $this->redirect("/bien/{$idBien}");
    }

    /**
     * Modifie un commentaire existant
     * 
     * @param int $id ID du commentaire
     * @return void Redirige vers la page du bien
     */
    public function edit($id) {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour modifier un commentaire.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Récupérer le commentaire
        $commentaire = $this->commentaireModel->getById($id);
        
        if (!$commentaire) {
            $_SESSION['error'] = "Commentaire introuvable.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Vérifier que l'utilisateur est bien l'auteur du commentaire
        if ($commentaire['id_locataire'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à modifier ce commentaire.";
            $this->redirect("/bien/{$commentaire['id_biens']}");
            return;
        }

        // Récupérer les nouvelles données
        $note = $_POST['note'] ?? null;
        $titre = trim($_POST['titre'] ?? '');
        $contenu = trim($_POST['contenu'] ?? '');

        // Validation
        if (!$contenu) {
            $_SESSION['error'] = "Le contenu du commentaire ne peut pas être vide.";
            $this->redirect("/bien/{$commentaire['id_biens']}");
            return;
        }

        if ($note !== null && ($note < 1 || $note > 5)) {
            $_SESSION['error'] = "La note doit être comprise entre 1 et 5.";
            $this->redirect("/bien/{$commentaire['id_biens']}");
            return;
        }

        // Mettre à jour le commentaire
        $data = [
            'note' => $note,
            'titre' => $titre ?: null,
            'contenu' => $contenu
        ];

        $result = $this->commentaireModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = "Votre commentaire a été modifié avec succès !";
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de la modification de votre commentaire.";
        }

        $this->redirect("/bien/{$commentaire['id_biens']}");
    }

    /**
     * Supprime un commentaire
     * 
     * @param int $id ID du commentaire
     * @return void Redirige vers la page du bien
     */
    public function delete($id) {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour supprimer un commentaire.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Récupérer le commentaire
        $commentaire = $this->commentaireModel->getById($id);
        
        if (!$commentaire) {
            $_SESSION['error'] = "Commentaire introuvable.";
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            return;
        }

        // Vérifier que l'utilisateur est bien l'auteur ou admin
        $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
        $isAuthor = $commentaire['id_locataire'] == $_SESSION['user_id'];

        if (!$isAuthor && !$isAdmin) {
            $_SESSION['error'] = "Vous n'êtes pas autorisé à supprimer ce commentaire.";
            $this->redirect("/bien/{$commentaire['id_biens']}");
            return;
        }

        // Supprimer le commentaire
        $result = $this->commentaireModel->delete($id);

        if ($result) {
            $_SESSION['success'] = "Le commentaire a été supprimé avec succès.";
        } else {
            $_SESSION['error'] = "Une erreur est survenue lors de la suppression du commentaire.";
        }

        // Rediriger vers la page du bien ou page admin si admin
        if ($isAdmin && isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'admin') !== false) {
            $this->redirect($_SERVER['HTTP_REFERER']);
        } else {
            $this->redirect("/bien/{$commentaire['id_biens']}");
        }
    }

    /**
     * Signale un commentaire comme inapproprié
     * 
     * @param int $id ID du commentaire
     * @return void Retourne un JSON pour requête AJAX
     */
    public function signaler($id) {
        header('Content-Type: application/json');

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vous devez être connecté pour signaler un commentaire.'
            ]);
            return;
        }

        // Récupérer le commentaire
        $commentaire = $this->commentaireModel->getById($id);
        
        if (!$commentaire) {
            echo json_encode([
                'success' => false,
                'message' => 'Commentaire introuvable.'
            ]);
            return;
        }

        // Empêcher un utilisateur de signaler son propre commentaire
        if ($commentaire['id_locataire'] == $_SESSION['user_id']) {
            echo json_encode([
                'success' => false,
                'message' => 'Vous ne pouvez pas signaler votre propre commentaire.'
            ]);
            return;
        }

        // Signaler le commentaire
        $result = $this->commentaireModel->signaler($id);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Le commentaire a été signalé. Notre équipe va l\'examiner.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue lors du signalement.'
            ]);
        }
    }

    /**
     * Récupère les commentaires d'un bien (pour AJAX)
     * 
     * @param int $idBien ID du bien
     * @return void Retourne un JSON
     */
    public function getCommentaires($idBien) {
        header('Content-Type: application/json');

        $orderBy = $_GET['order_by'] ?? 'date_desc';
        $commentaires = $this->commentaireModel->getCommentairesByBien($idBien, 'publie', $orderBy);
        $noteMoyenne = $this->commentaireModel->getNoteMoyenne($idBien);
        $nombreCommentaires = $this->commentaireModel->getNombreCommentaires($idBien);
        $repartition = $this->commentaireModel->getRepartitionNotes($idBien);

        echo json_encode([
            'success' => true,
            'commentaires' => $commentaires,
            'note_moyenne' => $noteMoyenne,
            'nombre_commentaires' => $nombreCommentaires,
            'repartition_notes' => $repartition
        ]);
    }

    /**
     * Like/Unlike un commentaire (toggle)
     * 
     * @param int $id ID du commentaire
     * @return void Retourne un JSON pour requête AJAX
     */
    public function toggleLike($id) {
        header('Content-Type: application/json');

        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Vous devez être connecté pour liker un commentaire.'
            ]);
            return;
        }

        $idLocataire = $_SESSION['user_id'];

        // Vérifier que le commentaire existe
        $commentaire = $this->commentaireModel->getById($id);
        
        if (!$commentaire) {
            echo json_encode([
                'success' => false,
                'message' => 'Commentaire introuvable.'
            ]);
            return;
        }

        // Toggle : si déjà liké, on retire le like, sinon on ajoute
        if ($this->commentaireModel->hasUserLiked($id, $idLocataire)) {
            $result = $this->commentaireModel->removeLike($id, $idLocataire);
            $action = 'unlike';
        } else {
            $result = $this->commentaireModel->addLike($id, $idLocataire);
            $action = 'like';
        }

        if ($result) {
            $likesCount = $this->commentaireModel->countLikes($id);
            echo json_encode([
                'success' => true,
                'action' => $action,
                'likes_count' => $likesCount,
                'message' => $action === 'like' ? 'Commentaire liké !' : 'Like retiré'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Une erreur est survenue.'
            ]);
        }
    }

    /**
     * Récupère le TOP 3 des commentaires les plus likés
     * 
     * @param int $idBien ID du bien
     * @return void Retourne un JSON pour requête AJAX
     */
    public function getTop3($idBien) {
        header('Content-Type: application/json');

        $top3 = $this->commentaireModel->getTop3MostLiked($idBien);

        echo json_encode([
            'success' => true,
            'top3' => $top3
        ]);
    }
}
