<?php

require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../Models/UserModel.php";
require_once __DIR__ . "/../Models/RoleModel.php";
require_once __DIR__ . "/../Models/CommuneModel.php";
require_once __DIR__ . "/../Models/TypeBienModel.php";
require_once __DIR__ . "/../Models/SaisonModel.php";
require_once __DIR__ . "/../Models/BienModel.php";
require_once __DIR__ . "/../Models/TarifModel.php";
require_once __DIR__ . "/../Models/AdminModel.php";
require_once __DIR__ . "/../Models/ReservationModel.php";
require_once __DIR__ . "/../Models/PhotoModel.php";
require_once __DIR__ . "/../Models/CommentaireModel.php";
require_once __DIR__ . "/../Models/NotificationModel.php";
require_once __DIR__ . "/../../lib/Database.php";

class AdminController extends BaseController {
    private $userModel;
    private $roleModel;
    private $communeModel;
    private $typeBienModel;
    private $saisonModel;
    private $bienModel;
    private $tarifModel;
    private $adminModel;
    private $reservationModel;
    private $photoModel;
    private $commentaireModel;
    private $notificationModel;

    public function __construct() {
        AuthMiddleware::requireRole("Administrateur");

        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->communeModel = new CommuneModel();
        $this->typeBienModel = new TypeBienModel();
        $this->saisonModel = new SaisonModel();
        $this->bienModel = new BienModel();
        $this->tarifModel = new TarifModel();
        $this->adminModel = new AdminModel();
        $this->reservationModel = new ReservationModel();
        $this->photoModel = new PhotoModel();
        $this->commentaireModel = new CommentaireModel();
        $this->notificationModel = new NotificationModel();
    }

    public function index() {
        // Récupérer les compteurs pour les notifications
        $pendingValidations = 0;
        $pendingSignalements = 0;
        
        // Compter les validations en attente
        try {
            $validations = $this->bienModel->getAll();
            if (is_array($validations)) {
                $pendingValidations = count(array_filter($validations, function($bien) {
                    return isset($bien['statut_validation']) && $bien['statut_validation'] === 'en_attente';
                }));
            }
        } catch (Exception $e) {
            $pendingValidations = 0;
        }

        // Compter les signalements en attente
        try {
            $sql = "SELECT COUNT(*) as count FROM signalements WHERE statut = 'en_attente'";
            $db = Database::getInstance();
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $pendingSignalements = $result['count'] ?? 0;
        } catch (Exception $e) {
            $pendingSignalements = 0;
        }

        $data = [
            'pendingValidations' => $pendingValidations,
            'pendingSignalements' => $pendingSignalements
        ];

        $this->render("admin/index", $data, ["style.css"]);
    }

    // --- Gestion des Administrateurs ---
    public function admins() {
        $admins = $this->adminModel->getAll();
        $this->render("admin/admins", ["admins" => $admins], ["style.css"]);
    }

    public function addAdmin() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nom_admin" => $_POST["nom_admin"],
                "email_admin" => $_POST["email_admin"],
                "mot_de_passe_admin" => password_hash($_POST["mot_de_passe_admin"], PASSWORD_DEFAULT),
                "is_admin" => isset($_POST["is_admin"]) ? 1 : 0
            ];
            $this->adminModel->create($data);
            $this->redirect("/admin/admins");
        }
        $this->render("admin/add_admin", [], ["style.css"]);
    }

    public function editAdmin($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                "nom_admin" => $_POST["nom_admin"],
                "email_admin" => $_POST["email_admin"],
                "is_admin" => isset($_POST["is_admin"]) ? 1 : 0
            ];
            if (!empty($_POST["mot_de_passe"])) {
                $data["mot_de_passe"] = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
            }
            $this->adminModel->update($id, $data);
            $this->redirect("/admin/admins");
        }
        $admin = $this->adminModel->getById($id);
        $this->render("admin/edit_admin", ["admin" => $admin], ["style.css"]);
    }

    public function deleteAdmin($id) {
        $this->adminModel->delete($id);
        $this->redirect("/admin/admins");
    }

    // --- Gestion des Rôles ---
    public function roles() {
        $roles = $this->roleModel->getAll();
        $this->render("admin/roles", ["roles" => $roles], ["style.css"]);
    }

    public function addRole() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->roleModel->create(["nom_roles" => $_POST["nom_roles"]]);
            $this->redirect("/admin/roles");
        }
        $this->render("admin/add_role", [], ["style.css"]);
    }

    public function editRole($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->roleModel->update($id, ["nom_roles" => $_POST["nom_roles"]]);
            $this->redirect("/admin/roles");
        }
        $role = $this->roleModel->getById($id);
        $this->render("admin/edit_role", ["role" => $role], ["style.css"]);
    }

    public function deleteRole($id) {
        $this->roleModel->delete($id);
        $this->redirect("/admin/roles");
    }

    // --- Gestion des Communes ---
    public function communes() {
        $communes = $this->communeModel->getAll();
        $this->render("admin/communes", ["communes" => $communes], ["style.css"]);
    }

    // --- Gestion des Types de Biens ---
    public function typesBiens() {
        $typesBiens = $this->typeBienModel->getAll();
        $this->render("admin/types_biens", ["typesBiens" => $typesBiens], ["style.css"]);
    }

    public function addTypeBien() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->typeBienModel->create(["desc_type_bien" => $_POST["desc_type_bien"]]);
            $this->redirect("/admin/typesBiens");
        }
        $this->render("admin/add_type_bien", [], ["style.css"]);
    }

    public function editTypeBien($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->typeBienModel->update($id, ["desc_type_bien" => $_POST["desc_type_bien"]]);
            $this->redirect("/admin/typesBiens");
        }
        $typeBien = $this->typeBienModel->getById($id);
        $this->render("admin/edit_type_bien", ["typeBien" => $typeBien], ["style.css"]);
    }

    public function deleteTypeBien($id) {
        $this->typeBienModel->delete($id);
        $this->redirect("/admin/typesBiens");
    }

    // --- Gestion des Saisons ---
    public function saisons() {
        $saisons = $this->saisonModel->getAll();
        $this->render("admin/saisons", ["saisons" => $saisons], ["style.css"]);
    }

    public function addSaison() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->saisonModel->create([
                "lib_saison" => $_POST["lib_saison"],
                "date_debut" => $_POST["date_debut"],
                "date_fin" => $_POST["date_fin"]
            ]);
            $this->redirect("/admin/saisons");
        }
        $this->render("admin/add_saison", [], ["style.css"]);
    }

    public function editSaison($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->saisonModel->update($id, [
                "lib_saison" => $_POST["lib_saison"],
                "date_debut" => $_POST["date_debut"],
                "date_fin" => $_POST["date_fin"]
            ]);
            $this->redirect("/admin/saisons");
        }
        $saison = $this->saisonModel->getById($id);
        $this->render("admin/edit_saison", ["saison" => $saison], ["style.css"]);
    }

    public function deleteSaison($id) {
        $this->saisonModel->delete($id);
        $this->redirect("/admin/saisons");
    }

    // --- Gestion des Biens ---
    public function biens() {
        $biens = $this->bienModel->getBiensWithProprietaireDetails();
        $this->render("admin/biens", ["biens" => $biens], ["style.css"]);
    }

    public function addBien() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'designation_bien' => $_POST["designation_bien"],
                'rue_biens' => $_POST["rue_biens"],
                'complement_biens' => $_POST["complement_biens"] ?? null,
                'superficie_biens' => $_POST["superficie_biens"],
                'description_biens' => $_POST["description_biens"] ?? null,
                'animaux_biens' => isset($_POST["animaux_biens"]) ? 1 : 0,
                'nb_couchage' => $_POST["nb_couchage"],
                'id_TypeBien' => $_POST["id_TypeBien"],
                'id_commune' => $_POST["id_commune"],
                'id_locataire' => $_POST["id_locataire"] // Le propriétaire
            ];
            $bienId = $this->bienModel->create($data);
            
            // Gérer l'upload de photos si nécessaire
            if ($bienId) {
                // Gérer l'upload de photos
                if (isset($_FILES["photos"]) && !empty($_FILES["photos"]["name"][0])) {
                    $this->handlePhotoUpload($bienId, $_FILES["photos"]);
                }

                // Gérer l'ajout des tarifs
                if (isset($_POST["tarifs"]) && is_array($_POST["tarifs"])) {
                    foreach ($_POST["tarifs"] as $tarif) {
                        if (!empty($tarif["prix_semaine"]) && !empty($tarif["annee"]) && !empty($tarif["id_saison"])) {
                            $this->tarifModel->create([
                                "prix_semaine" => $tarif["prix_semaine"],
                                "annee" => $tarif["annee"],
                                "id_biens" => $bienId,
                                "id_saison" => $tarif["id_saison"]
                            ]);
                        }
                    }
                }
            }
            
            $this->redirect("/admin/biens");
        }

        // Récupérer les données nécessaires pour le formulaire
        $typesBiens = $this->typeBienModel->getAll();
        $communes = $this->communeModel->getAll();
        $personnesPhysiques = $this->userModel->getUsersByRole(2, 'physique');
        $personnesMorales = $this->userModel->getUsersByRole(2, 'morale');
        $saisons = $this->saisonModel->getAll();

        // Passer les données à la vue
        $this->render("admin/add_bien", [
            "typesBiens" => $typesBiens,
            "communes" => $communes,
            "personnesPhysiques" => $personnesPhysiques,
            "personnesMorales" => $personnesMorales,
            "saisons" => $saisons
        ], ["style.css"]);
    }

    public function editBien($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'designation_bien' => $_POST["designation_bien"],
                'rue_biens' => $_POST["rue_biens"],
                'complement_biens' => $_POST["complement_biens"] ?? null,
                'superficie_biens' => $_POST["superficie_biens"],
                'description_biens' => $_POST["description_biens"] ?? null,
                'animaux_biens' => isset($_POST["animaux_biens"]) ? 1 : 0, // Gestion de la case à cocher
                'nb_couchage' => $_POST["nb_couchage"],
                'id_TypeBien' => $_POST["id_TypeBien"],
                'id_commune' => $_POST["id_commune"],
                'id_locataire' => $_POST["id_locataire"] ?? null // S'assurer que id_locataire est toujours défini, même si vide
            ];
            $this->bienModel->update($id, $data);

            // Gérer la mise à jour des tarifs
            if (isset($_POST["tarifs"]) && is_array($_POST["tarifs"])) {
                foreach ($_POST["tarifs"] as $tarif) {
                    if (!empty($tarif["prix_semaine"]) && !empty($tarif["annee"]) && !empty($tarif["id_saison"])) {
                        // Vérifier si un tarif existe déjà pour ce bien, cette saison et cette année
                        $existingTarif = $this->tarifModel->getTarifByBienSaisonAnnee($id, $tarif["id_saison"], $tarif["annee"]);
                        if ($existingTarif) {
                            // Mettre à jour le tarif existant
                            $this->tarifModel->update($existingTarif["id_tarif"], [
                                "prix_semaine" => $tarif["prix_semaine"],
                                "annee" => $tarif["annee"],
                                "id_saison" => $tarif["id_saison"]
                            ]);
                        } else {
                            // Créer un nouveau tarif
                            $this->tarifModel->create([
                                "prix_semaine" => $tarif["prix_semaine"],
                                "annee" => $tarif["annee"],
                                "id_biens" => $id,
                                "id_saison" => $tarif["id_saison"]
                            ]);
                        }
                    }
                }
            }
            
            // Gérer l'upload de photos si nécessaire
            if (isset($_FILES["photos"]) && !empty($_FILES["photos"]["name"][0])) {
                $this->handlePhotoUpload($id, $_FILES["photos"]);
            }

            $this->redirect("/admin/biens");
        }

        // Récupérer les données nécessaires pour la vue
        $bien = $this->bienModel->getById($id);
        $typesBiens = $this->typeBienModel->getAll();

        // Récupérer la commune associée au bien pour le pré-remplissage
        $communeBien = $this->communeModel->getById($bien['id_commune']);
        $communeNom = '';
        if ($communeBien) {
            $nomCommune = $communeBien['ville_nom'] ?? '';
            $codePostal = $communeBien['ville_code_postal'] ?? '';
            $communeNom = $nomCommune . (!empty($codePostal) ? ' (' . $codePostal . ')' : '');
        }

        // Récupérer le propriétaire associé au bien pour le pré-remplissage
        $proprietaireBien = $this->userModel->getById($bien['id_locataire']);
        $proprietaireNom = '';
        if ($proprietaireBien) {
            if (!empty($proprietaireBien['RaisonSociale'])) {
                $proprietaireNom = $proprietaireBien['RaisonSociale'];
            } else {
                $proprietaireNom = $proprietaireBien['prenom_locataire'] . ' ' . $proprietaireBien['nom_locataire'];
            }
        }

        $personnesPhysiques = $this->userModel->getUsersByRole(2, 'physique');
        $saisons = $this->saisonModel->getAll();
        $tarifs = $this->tarifModel->getTarifsByBien($id);

        $tarifsMapped = [];
        foreach ($tarifs as $tarif) {
            $tarifsMapped[$tarif['id_saison'] . '_' . $tarif['annee']] = $tarif['prix_semaine'];
        }

        // Récupérer les photos du bien
        $photos = $this->photoModel->getPhotosByBien($id);

        // Passer les données à la vue
        $this->render("admin/edit_bien", [
            "bien" => $bien,
            "typesBiens" => $typesBiens,
            "communeNom" => $communeNom,
            "proprietaireNom" => $proprietaireNom,
            "saisons" => $saisons,
            "tarifsMapped" => $tarifsMapped,
            "photos" => $photos
        ], ["style.css"]);
    }

    public function deleteBien($id) {
        // Notify users with future reservations then delete related reservations
        try {
            $bien = $this->bienModel->getById($id);
            $bienName = $bien['designation_bien'] ?? ("Bien #" . $id);
            $title = 'Bien supprimé';
            $message = 'Le bien "' . $bienName . '" sur lequel vous aviez une réservation a été supprimé. Votre réservation peut être affectée.';
            $this->notificationModel->notifyUsersWithFutureReservationsForBien((int)$id, $title, $message, '/locataire/myReservations');
        } catch (\Throwable $e) { }
        // Optionally, clean reservations for this bien to avoid orphans
        try {
            $this->reservationModel->deleteByBien((int)$id);
        } catch (\Throwable $e) { }
        $this->bienModel->delete($id);
        $this->redirect("/admin/biens");
    }






    

    // --- Gestion des Utilisateurs ---
    public function users() {
        $users = $this->userModel->getAllUsersWithRoles();
        $this->render("admin/users", ["users" => $users], ["style.css"]);
    }

    public function addUser() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Validation des champs Siret et RaisonSociale
            $siret = $_POST["Siret"] ?? "";
            $fullTel = $_POST["full_tel_locataire"] ?? "";

            // Validation du numéro de téléphone (si fourni)
            if (!empty($fullTel) && !preg_match("/^\\+[1-9]\\d{1,14}$/", $fullTel)) {
                $_SESSION["error"] = "Le numéro de téléphone n'est pas valide (format E.164 requis).";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/admin/add_user");
                return;
            }
            if (!empty($siret) && (!ctype_digit($siret) || strlen($siret) !== 14)) {
                $_SESSION["error"] = "Le numéro SIRET doit contenir exactement 14 chiffres.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/admin/add_user");
                return;
            }

            $raisonSociale = $_POST["RaisonSociale"] ?? "";
            if (!empty($raisonSociale) && strlen($raisonSociale) > 255) {
                $_SESSION["error"] = "La raison sociale ne peut pas dépasser 255 caractères.";
                $_SESSION["old_data"] = $_POST;
                $this->redirect("/admin/add_user");
                return;
            }

            // Préparer les données
            $data = [
                'nom_locataire' => $_POST["nom_locataire"],
                'prenom_locataire' => $_POST["prenom_locataire"],
                'dateNaissance_locataire' => $_POST["dateNaissance_locataire"] ?? null,
                'email_locataire' => $_POST["email_locataire"],
                'password_locataire' => password_hash($_POST["password_locataire"], PASSWORD_DEFAULT),
                'tel_locataire' => $fullTel ?? null,
                'rue_locataire' => $_POST["rue_locataire"] ?? null,
                'complement_locataire' => $_POST["complement_locataire"] ?? null,
                'RaisonSociale' => empty($_POST["RaisonSociale"]) ? null : $_POST["RaisonSociale"],
                'Siret' => empty($_POST["Siret"]) ? null : $_POST["Siret"],
                'id_commune' => $_POST["id_commune"] ?? null
            ];

            // Créer l'utilisateur
            $userId = $this->userModel->create($data);

            // Assigner les rôles sélectionnés seulement si l'utilisateur a été créé avec succès
            if ($userId && isset($_POST["roles"]) && is_array($_POST["roles"])) {
                foreach ($_POST["roles"] as $roleId) {
                    $this->userModel->assignRole($userId, $roleId);
                }
            }

            $this->redirect("/admin/users");
        }

        $roles = $this->roleModel->getAll();
        $communes = $this->communeModel->getAll();
        $this->render("admin/add_user", ["roles" => $roles, "communes" => $communes], ["style.css"]);
    }

    public function editUser($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $fullTel = $_POST["full_tel_locataire"] ?? "";

            // Validation du numéro de téléphone (si fourni)
            if (!empty($fullTel) && !preg_match("/^\\+[1-9]\\d{1,14}$/", $fullTel)) {
                $_SESSION["error"] = "Le numéro de téléphone n'est pas valide (format E.164 requis).";
                // Note : Pour editUser, il faudrait idéalement rediriger vers la page d'édition avec les données postées,
                // mais comme le code original ne le fait pas, je vais simplement rediriger vers la liste des utilisateurs.
                $this->redirect("/admin/users");
                return;
            }
            $data = [
                'nom_locataire' => $_POST["nom_locataire"],
                'prenom_locataire' => $_POST["prenom_locataire"],
                'dateNaissance_locataire' => $_POST["dateNaissance_locataire"] ?? null,
                'email_locataire' => $_POST["email_locataire"],
                'tel_locataire' => $fullTel ?? null,
                'complement_locataire' => $_POST["complement_locataire"] ?? null,
                'RaisonSociale' => $_POST["RaisonSociale"] ?? null,
                'Siret' => $_POST["Siret"] ?? null,
                'id_commune' => $_POST["id_commune"] ?? null
            ];
            $this->userModel->update($id, $data);
            
            // Gérer les rôles : supprimer tous les anciens et assigner les nouveaux
            // Note : Cette approche simple supprime et recrée. Pour une approche plus fine, 
            // comparer les rôles existants avec les nouveaux.
            $currentRoles = $this->userModel->getUserRoles($id);
            foreach ($currentRoles as $role) {
                $this->userModel->removeRole($id, $role["id_roles"]);
            }
            
            if (isset($_POST["roles"]) && is_array($_POST["roles"])) {
                foreach ($_POST["roles"] as $roleId) {
                    $this->userModel->assignRole($id, $roleId);
                }
            }
            
            $this->redirect("/admin/users");
        }
        $user = $this->userModel->getById($id);
        $roles = $this->roleModel->getAll();
        $communes = $this->communeModel->getAll();
        $userRoles = $this->userModel->getUserRoles($id);
        $userRoleIds = array_column($userRoles, 'id_roles');
        $this->render(
            "admin/edit_user",
            [
                "user" => $user,
                "roles" => $roles,
                "userRoles" => $userRoles,
                "userRoleIds" => $userRoleIds,
                "communes" => $communes
            ],
            ["style.css"]
        );
    }

    public function deleteUser($id) {
        $this->userModel->delete($id);
        $this->redirect("/admin/users");
    }









    // --- Gestion des Réservations ---
    public function reservations() {
        $reservations = $this->reservationModel->getAllReservations();
        $this->render("admin/reservations", ["reservations" => $reservations], ["style.css"]);
    }

    public function addReservation() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = [
                'id_biens' => $_POST["id_biens"],
                'id_locataire' => $_POST["id_locataire"],
                'date_debut' => $_POST["date_debut"],
                'date_fin' => $_POST["date_fin"],
                'id_tarif' => $_POST["id_tarif"]
            ];
            $this->reservationModel->createReservation($data);
            // Notify proprietaire about new reservation
            $bien = $this->bienModel->getById($data['id_biens']);
            if ($bien && !empty($bien['id_locataire'])) {
                $title = "Nouvelle réservation";
                $dd = date('d/m/Y', strtotime($data['date_debut']));
                $df = date('d/m/Y', strtotime($data['date_fin']));
                $bienName = $bien['designation_bien'] ?? ("Bien #" . $data['id_biens']);
                $message = "Une nouvelle réservation a été créée sur votre bien \"" . $bienName . "\" du " . $dd . " au " . $df . ".";
                try { $this->notificationModel->create([
                    'user_id' => (int)$bien['id_locataire'],
                    'type' => 'reservation_created',
                    'title' => $title,
                    'message' => $message,
                    'link' => '/proprietaire/myReservations',
                ]); } catch (\Throwable $e) { }
            }
            $this->redirect("/admin/reservations");
        }

        // Récupérer les données nécessaires pour le formulaire
        $biens = $this->bienModel->getAll();
        $users = $this->userModel->getAllUsersWithRoles();
        $tarifs = $this->tarifModel->getAll();

        $this->render("admin/add_reservation", [
            "biens" => $biens,
            "users" => $users,
            "tarifs" => $tarifs
        ], ["style.css"]);
    }

    public function editReservation($id) {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $before = $this->reservationModel->getById($id);
            $data = [
                'id_biens' => $_POST["id_biens"],
                'id_locataire' => $_POST["id_locataire"],
                'date_debut' => $_POST["date_debut"],
                'date_fin' => $_POST["date_fin"],
                'id_tarif' => $_POST["id_tarif"]
            ];
            $this->reservationModel->update($id, $data);
            // Notify changes if dates or bien changed
            if ($before) {
                $changed = ($before['date_debut'] !== $data['date_debut']) || ($before['date_fin'] !== $data['date_fin']) || ((int)$before['id_biens'] !== (int)$data['id_biens']);
                if ($changed) {
                    // Notify locataire
                    $bienNow = $this->bienModel->getById($data['id_biens']);
                    $bienName = $bienNow['designation_bien'] ?? ("Bien #" . $data['id_biens']);
                    try { $this->notificationModel->create([
                        'user_id' => (int)$before['id_locataire'],
                        'type' => 'reservation_updated',
                        'title' => 'Réservation modifiée',
                        'message' => 'Votre réservation #' . $id . ' ("' . $bienName . '") a été modifiée par un administrateur.',
                        'link' => '/locataire/myReservations',
                    ]); } catch (\Throwable $e) { }
                    // Notify proprietaire
                    if ($bienNow && !empty($bienNow['id_locataire'])) {
                        try { $this->notificationModel->create([
                            'user_id' => (int)$bienNow['id_locataire'],
                            'type' => 'reservation_updated',
                            'title' => 'Réservation modifiée',
                            'message' => 'Une réservation (#' . $id . ') sur votre bien "' . $bienName . '" a été modifiée.',
                            'link' => '/proprietaire/myReservations',
                        ]); } catch (\Throwable $e) { }
                    }
                }
            }
            $this->redirect("/admin/reservations");
        }
        $reservation = $this->reservationModel->getById($id);

        // Récupérer les données nécessaires pour le formulaire
        $biens = $this->bienModel->getAll();
        $users = $this->userModel->getAllUsersWithRoles();
        $tarifs = $this->tarifModel->getAll();

        $this->render("admin/edit_reservation", [
            "reservation" => $reservation,
            "biens" => $biens,
            "users" => $users,
            "tarifs" => $tarifs
        ], ["style.css"]);
    }

    public function deleteReservation($id) {
        $before = $this->reservationModel->getById($id);
        $this->reservationModel->delete($id);
        if ($before) {
            // Notify locataire and proprietaire about deletion
            $bien = $this->bienModel->getById($before['id_biens']);
            $bienName = $bien['designation_bien'] ?? ("Bien #" . $before['id_biens']);
            try { $this->notificationModel->create([
                'user_id' => (int)$before['id_locataire'],
                'type' => 'reservation_deleted',
                'title' => 'Réservation annulée',
                'message' => 'Votre réservation #' . $id . ' ("' . $bienName . '") a été annulée par un administrateur.',
                'link' => '/locataire/myReservations',
            ]); } catch (\Throwable $e) { }
            if ($bien && !empty($bien['id_locataire'])) {
                try { $this->notificationModel->create([
                    'user_id' => (int)$bien['id_locataire'],
                    'type' => 'reservation_deleted',
                    'title' => 'Réservation annulée',
                    'message' => 'Une réservation (#' . $id . ') sur votre bien "' . $bienName . '" a été annulée.',
                    'link' => '/proprietaire/myReservations',
                ]); } catch (\Throwable $e) { }
            }
        }
        $this->redirect("/admin/reservations");
    }

    // --- Gestion des Photos ---
    private function handlePhotoUpload($bienId, $files) {
        if (!is_dir(UPLOAD_DIR)) {
            mkdir(UPLOAD_DIR, 0777, true);
        }

        foreach ($files["name"] as $key => $name) {
            if ($files["error"][$key] == UPLOAD_ERR_OK) {
                $tmp_name = $files["tmp_name"][$key];
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $newFileName = uniqid("photo_") . "." . $extension;
                $targetFile = UPLOAD_DIR . $newFileName;

                if (move_uploaded_file($tmp_name, $targetFile)) {
                    $this->photoModel->create([
                        "nom_photo" => $name,
                        "lien_photo" => UPLOAD_URL . $newFileName,
                        "id_biens" => $bienId
                    ]);
                }
            }
        }
    }

    public function deletePhoto($photoId) {
        $photo = $this->photoModel->getById($photoId);
        if ($photo) {
            // Supprimer le fichier physique
            $filePath = str_replace(UPLOAD_URL, UPLOAD_DIR, $photo["lien_photo"]);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $this->photoModel->delete($photoId);
            $this->redirect("/admin/editBien/" . $photo["id_biens"]);
            return;
        }
        $this->redirect("/admin/biens");
    }

    // --- API Endpoints for Autocomplete ---
    public function searchUsers() {
        header("Content-Type: application/json");
        $term = $_GET["term"] ?? "";
        $id_roles = $_GET["id_roles"] ?? null;
        $users = $this->userModel->searchUsersByIdRoleAndName($term, $id_roles);
        echo json_encode($users);
    }

    public function searchCommunes() {
        header("Content-Type: application/json");
        $term = $_GET["term"] ?? "";
        $communes = $this->communeModel->search($term);
        echo json_encode($communes);
    }

    // --- Gestion de la validation des biens ---
    public function validations() {
        $biensEnAttente = $this->bienModel->getBiensEnAttente();
        $this->render("admin/validations", ["biensEnAttente" => $biensEnAttente]);
    }

    public function validerBien($id) {
        $adminId = $_SESSION['admin_id'] ?? null;
        
        if ($this->bienModel->validerBien($id, $adminId)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Le bien a été validé avec succès et est maintenant visible publiquement.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors de la validation du bien.'
            ];
        }
        
        header('Location: /admin/validations');
        exit;
    }

    public function refuserBien($id) {
        $adminId = $_SESSION['admin_id'] ?? null;
        $motif = $_GET['motif'] ?? null;
        
        if ($this->bienModel->refuserBien($id, $adminId, $motif)) {
            $_SESSION['flash'] = [
                'type' => 'warning',
                'message' => 'Le bien a été refusé. Le propriétaire peut le modifier et le soumettre à nouveau.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors du refus du bien.'
            ];
        }
        
        header('Location: /admin/validations');
        exit;
    }

    // --- Gestion des signalements ---
    public function signalements() {
        require_once __DIR__ . '/../Models/SignalementModel.php';
        $signalementModel = new SignalementModel();
        $signalements = $signalementModel->getSignalementsEnAttente();
        $this->render("admin/signalements", ["signalements" => $signalements]);
    }

    public function traiterSignalement($id) {
        require_once __DIR__ . '/../Models/SignalementModel.php';
        $signalementModel = new SignalementModel();
        $adminId = $_SESSION['admin_id'] ?? null;
        
        if ($signalementModel->traiterSignalement($id, $adminId, null)) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Le signalement a été marqué comme traité.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors du traitement du signalement.'
            ];
        }
        
        header('Location: /admin/signalements');
        exit;
    }

    public function rejeterSignalement($id) {
        require_once __DIR__ . '/../Models/SignalementModel.php';
        $signalementModel = new SignalementModel();
        $adminId = $_SESSION['admin_id'] ?? null;
        
        if ($signalementModel->rejeterSignalement($id, $adminId, null)) {
            $_SESSION['flash'] = [
                'type' => 'warning',
                'message' => 'Le signalement a été rejeté.'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Une erreur est survenue lors du rejet du signalement.'
            ];
        }
        
        header('Location: /admin/signalements');
        exit;
    }

    // Mettre à jour le statut de validation d'un bien
    public function updateStatutBien($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            return;
        }

        $statut = $_POST['statut'] ?? null;
        $adminId = $_SESSION['admin_id'] ?? null;

        if (!$statut) {
            echo json_encode(['success' => false, 'message' => 'Statut manquant']);
            return;
        }

        if ($this->bienModel->updateStatutValidation($id, $statut, $adminId)) {
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
        }
    }

    /**
     * ==================================================
     * GESTION DES COMMENTAIRES SIGNALÉS
     * ==================================================
     */

    /**
     * Affiche la liste des commentaires signalés
     */
    public function commentairesSignales() {
        $commentairesSignales = $this->commentaireModel->getCommentairesSignales();
        
        $this->render('admin/commentaires_signales', [
            'commentairesSignales' => $commentairesSignales
        ]);
    }

    /**
     * Approuve un commentaire signalé (retire le signalement)
     * 
     * @param int $id ID du commentaire
     */
    public function approuverCommentaire($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Méthode non autorisée";
            $this->redirect('/admin/commentaires-signales');
            return;
        }

        // Récupérer le commentaire
        $commentaire = $this->commentaireModel->getById($id);
        
        if (!$commentaire) {
            $_SESSION['error'] = "Commentaire introuvable";
            $this->redirect('/admin/commentaires-signales');
            return;
        }

        // Retirer le signalement (remettre signale à 0)
        $sql = "UPDATE commentaires SET signale = 0 WHERE id_commentaire = :id";
        $stmt = $this->commentaireModel->getDb()->prepare($sql);
        
        if ($stmt->execute(['id' => $id])) {
            $_SESSION['success'] = "Le commentaire a été approuvé et le signalement retiré.";
        } else {
            $_SESSION['error'] = "Erreur lors de l'approbation du commentaire.";
        }

        $this->redirect('/admin/commentaires-signales');
    }

    /**
     * Rejette un commentaire (change son statut en 'rejete')
     * 
     * @param int $id ID du commentaire
     */
    public function rejeterCommentaire($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Méthode non autorisée";
            $this->redirect('/admin/commentaires-signales');
            return;
        }

        // Récupérer le commentaire
        $commentaire = $this->commentaireModel->getById($id);
        
        if (!$commentaire) {
            $_SESSION['error'] = "Commentaire introuvable";
            $this->redirect('/admin/commentaires-signales');
            return;
        }

        // Changer le statut en 'rejete'
        if ($this->commentaireModel->updateStatut($id, 'rejete')) {
            $_SESSION['success'] = "Le commentaire a été rejeté et n'est plus visible publiquement.";
        } else {
            $_SESSION['error'] = "Erreur lors du rejet du commentaire.";
        }

        $this->redirect('/admin/commentaires-signales');
    }
}

?>
