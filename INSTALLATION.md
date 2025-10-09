# Guide d'Installation - GlobeNight

## Prérequis

Avant d'installer GlobeNight, assurez-vous d'avoir :
- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur (ou MariaDB)
- Apache avec mod_rewrite activé
- Extension PHP PDO et PDO_MySQL

## Étape 1 : Configuration de la Base de Données

### 1.1 Créer la base de données et l'utilisateur

Connectez-vous à MySQL en tant qu'administrateur et exécutez les commandes suivantes :

```sql
-- Créer la base de données
CREATE DATABASE Location CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Créer l'utilisateur (remplacez 'votre_mot_de_passe' par un mot de passe sécurisé)
CREATE USER 'globenight_user'@'localhost' IDENTIFIED BY 'votre_mot_de_passe';

-- Accorder tous les privilèges sur la base de données
GRANT ALL PRIVILEGES ON Location.* TO 'globenight_user'@'localhost';

-- Appliquer les changements
FLUSH PRIVILEGES;
```

### 1.2 Importer le schéma de la base de données

Une fois la base de données créée, importez le fichier SQL fourni :

```bash
mysql -u globenight_user -p Location < database.sql
```

Ou via phpMyAdmin :
1. Sélectionnez la base de données "Location"
2. Cliquez sur l'onglet "Importer"
3. Choisissez le fichier `database.sql`
4. Cliquez sur "Exécuter"

## Étape 2 : Configuration de l'Application

### 2.1 Configurer les paramètres de connexion

Éditez le fichier `config/config.php` et modifiez les paramètres de connexion :

```php
define("DB_HOST", "localhost");
define("DB_NAME", "Location");
define("DB_USER", "globenight_user");
define("DB_PASS", "votre_mot_de_passe");  // ← Modifiez cette ligne
```

**Important** : Remplacez `"votre_mot_de_passe"` par le mot de passe que vous avez défini lors de la création de l'utilisateur MySQL.

### 2.2 Vérifier les permissions du dossier uploads

Assurez-vous que le serveur web peut écrire dans le dossier uploads :

**Sur Linux/Mac :**
```bash
chmod 755 public/uploads
chown www-data:www-data public/uploads  # Pour Apache sur Ubuntu/Debian
```

**Sur Windows avec XAMPP :**
Les permissions sont généralement correctes par défaut. Vérifiez simplement que le dossier existe.

## Étape 3 : Configuration du Serveur Web

### Option A : Apache (Recommandé)

Créez un VirtualHost pointant vers le dossier `public` :

**Fichier : `/etc/apache2/sites-available/globenight.conf`** (Linux)

```apache
<VirtualHost *:80>
    ServerName globenight.local
    DocumentRoot /chemin/vers/GlobeNight/public
    
    <Directory /chemin/vers/GlobeNight/public>
        AllowOverride All
        Require all granted
        
        # Activer mod_rewrite
        RewriteEngine On
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/globenight-error.log
    CustomLog ${APACHE_LOG_DIR}/globenight-access.log combined
</VirtualHost>
```

Activez le site :
```bash
sudo a2ensite globenight.conf
sudo systemctl reload apache2
```

Ajoutez l'entrée dans `/etc/hosts` :
```
127.0.0.1    globenight.local
```

### Option B : XAMPP/WAMP (Windows)

**1. Éditez `C:\xampp\apache\conf\extra\httpd-vhosts.conf` :**

```apache
<VirtualHost *:80>
    ServerName globenight.local
    DocumentRoot "C:/xampp/htdocs/GlobeNight/public"
    
    <Directory "C:/xampp/htdocs/GlobeNight/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**2. Éditez `C:\Windows\System32\drivers\etc\hosts` (en tant qu'administrateur) :**

```
127.0.0.1    globenight.local
```

**3. Redémarrez Apache depuis le panneau de contrôle XAMPP**

### Option C : Serveur de Développement PHP

Pour un test rapide (non recommandé en production) :

```bash
cd public
php -S localhost:8000
```

**Note** : Avec cette méthode, les chemins vers les assets doivent être adaptés.

## Étape 4 : Vérification de l'Installation

### 4.1 Tester la connexion à la base de données

Créez un fichier temporaire `test_db.php` à la racine du projet :

```php
<?php
require_once 'config/config.php';
require_once 'lib/Database.php';

try {
    $db = Database::getInstance();
    echo "✅ Connexion à la base de données réussie !";
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
?>
```

Accédez à ce fichier via votre navigateur. Si vous voyez le message de succès, supprimez le fichier.

### 4.2 Accéder à l'application

Ouvrez votre navigateur et accédez à :
- `http://globenight.local` (si vous avez configuré un VirtualHost)
- `http://localhost/GlobeNight/public` (si vous utilisez XAMPP sans VirtualHost)

Vous devriez voir la page d'accueil de GlobeNight.

## Étape 5 : Créer le Premier Compte Administrateur

### Option A : Via SQL

Exécutez cette requête SQL pour créer un compte admin :

```sql
-- Insérer un utilisateur admin (mot de passe: admin123)
INSERT INTO user (email, password, tel, rue, complement, id_commune, id_roles) 
VALUES (
    'admin@globenight.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- password: admin123
    '0123456789',
    '1 rue de la République',
    '',
    1,
    1  -- ROLE_ADMIN
);
```

**Important** : Changez le mot de passe après la première connexion !

### Option B : Via la page d'inscription

1. Accédez à la page d'inscription
2. Créez un compte
3. Modifiez manuellement le rôle dans la base de données pour le passer en administrateur (id_roles = 1)

## Résolution des Problèmes Courants

### Erreur : "Access denied for user 'globenight_user'@'localhost'"

**Cause** : Les identifiants de connexion à la base de données sont incorrects.

**Solution** :
1. Vérifiez que l'utilisateur MySQL existe :
   ```sql
   SELECT User, Host FROM mysql.user WHERE User = 'globenight_user';
   ```
2. Vérifiez le mot de passe dans `config/config.php`
3. Recréez l'utilisateur si nécessaire (voir Étape 1.1)

### Erreur : "SQLSTATE[HY000] [1049] Unknown database 'Location'"

**Cause** : La base de données n'existe pas.

**Solution** :
```sql
CREATE DATABASE Location CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Erreur : "404 Not Found" sur toutes les pages sauf l'accueil

**Cause** : mod_rewrite n'est pas activé ou le fichier .htaccess n'est pas lu.

**Solution** :
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Vérifiez aussi que `AllowOverride All` est bien configuré dans votre VirtualHost.

### Les styles CSS ne se chargent pas

**Cause** : Le DocumentRoot ne pointe pas vers le dossier `public`.

**Solution** : Assurez-vous que votre configuration Apache pointe vers le dossier `public` et non vers la racine du projet.

### Erreur lors de l'upload de photos

**Cause** : Le dossier `uploads` n'a pas les bonnes permissions.

**Solution** :
```bash
chmod 755 public/uploads
chown www-data:www-data public/uploads
```

## Support

Pour toute question ou problème, consultez le fichier `CORRECTIONS.md` qui contient des informations supplémentaires sur la structure du projet.

---

**Installation préparée par Manus AI**
