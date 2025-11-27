<!DOCTYPE html>
<html>
<head>
    <title>Debug Session</title>
</head>
<body>
    <h1>Debug Session et Photo de Profil</h1>
    
    <?php
    session_start();
    require_once __DIR__ . "/../config/config.php";
    require_once __DIR__ . "/../app/Models/UserModel.php";
    
    echo "<h2>Contenu de la session :</h2>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    
    if (isset($_SESSION['user_id'])) {
        $userModel = new UserModel();
        $user = $userModel->getById($_SESSION['user_id']);
        
        echo "<h2>Données utilisateur depuis la base :</h2>";
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        
        if (isset($user['pfp_loca'])) {
            echo "<h2>Photo de profil détectée :</h2>";
            echo "<p><strong>Chemin :</strong> " . htmlspecialchars($user['pfp_loca']) . "</p>";
            
            $fullPath = __DIR__ . $user['pfp_loca'];
            echo "<p><strong>Chemin complet :</strong> " . htmlspecialchars($fullPath) . "</p>";
            echo "<p><strong>Fichier existe :</strong> " . (file_exists($fullPath) ? "OUI" : "NON") . "</p>";
            
            if (!empty($user['pfp_loca'])) {
                echo "<p><img src='" . htmlspecialchars($user['pfp_loca']) . "' style='width: 100px; height: 100px; border-radius: 50%; object-fit: cover;' /></p>";
            }
        } else {
            echo "<h2>Aucune photo de profil (colonne pfp_loca non trouvée)</h2>";
            echo "<p style='color: red;'>⚠️ La colonne 'pfp_loca' n'existe probablement pas encore dans la base de données.</p>";
            echo "<p>Exécutez cette requête SQL :</p>";
            echo "<pre>ALTER TABLE `locataire` ADD COLUMN `pfp_loca` VARCHAR(255) NULL DEFAULT NULL AFTER `id_commune`;</pre>";
        }
    } else {
        echo "<p style='color: red;'>Vous n'êtes pas connecté.</p>";
    }
    ?>
    
    <hr>
    <p><a href="/home">Retour à l'accueil</a> | <a href="/logout">Se déconnecter</a> | <a href="/profile">Mon profil</a></p>
</body>
</html>
