DROP DATABASE IF EXISTS Location;
CREATE DATABASE Location;
USE Location;

-- Table `commune`
CREATE TABLE IF NOT EXISTS `commune` (
  `id_commune` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ville_departement` varchar(3) DEFAULT NULL,
  `ville_slug` varchar(255) DEFAULT NULL,
  `ville_nom` varchar(45) DEFAULT NULL,
  `ville_nom_simple` varchar(45) DEFAULT NULL,
  `ville_nom_reel` varchar(45) DEFAULT NULL,
  `ville_nom_soundex` varchar(20) DEFAULT NULL,
  `ville_nom_metaphone` varchar(22) DEFAULT NULL,
  `ville_code_postal` varchar(255) DEFAULT NULL,
  `ville_commune` varchar(3) DEFAULT NULL,
  `ville_code_commune` varchar(5) NOT NULL,
  `ville_arrondissement` smallint(3) UNSIGNED DEFAULT NULL,
  `ville_canton` varchar(4) DEFAULT NULL,
  `ville_amdi` smallint(5) UNSIGNED DEFAULT NULL,
  `ville_population_2010` mediumint(11) UNSIGNED DEFAULT NULL,
  `ville_population_1999` mediumint(11) UNSIGNED DEFAULT NULL,
  `ville_population_2012` mediumint(10) UNSIGNED DEFAULT NULL COMMENT 'approximatif',
  `ville_densite_2010` int(11) DEFAULT NULL,
  `ville_surface` float DEFAULT NULL,
  `ville_longitude_deg` float DEFAULT NULL,
  `ville_latitude_deg` float DEFAULT NULL,
  `ville_longitude_grd` varchar(9) DEFAULT NULL,
  `ville_latitude_grd` varchar(8) DEFAULT NULL,
  `ville_longitude_dms` varchar(9) DEFAULT NULL,
  `ville_latitude_dms` varchar(8) DEFAULT NULL,
  `ville_zmin` mediumint(4) DEFAULT NULL,
  `ville_zmax` mediumint(4) DEFAULT NULL,
  PRIMARY KEY (`id_commune`),
  UNIQUE KEY `ville_code_commune_2` (`ville_code_commune`),
  UNIQUE KEY `ville_slug` (`ville_slug`),
  KEY `ville_departement` (`ville_departement`),
  KEY `ville_nom` (`ville_nom`),
  KEY `ville_nom_reel` (`ville_nom_reel`),
  KEY `ville_code_commune` (`ville_code_commune`),
  KEY `ville_code_postal` (`ville_code_postal`),
  KEY `ville_longitude_latitude_deg` (`ville_longitude_deg`,`ville_latitude_deg`),
  KEY `ville_nom_soundex` (`ville_nom_soundex`),
  KEY `ville_nom_metaphone` (`ville_nom_metaphone`),
  KEY `ville_population_2010` (`ville_population_2010`),
  KEY `ville_nom_simple` (`ville_nom_simple`)
) ENGINE=InnoDB AUTO_INCREMENT=36831 DEFAULT CHARSET=utf8;

-- Table `Type_Bien`
CREATE TABLE Type_Bien (
    id_typebien INT PRIMARY KEY AUTO_INCREMENT,
    designation_bien VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Users` (renommée de Locataire et adaptée)
CREATE TABLE Users (
    id_users INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nom_users VARCHAR(100),
    prenom_users VARCHAR(100),
    dateNaissance_users DATE,
    email_users VARCHAR(255) UNIQUE NOT NULL,
    password_users VARCHAR(255) NOT NULL,
    tel_users VARCHAR(20),
    rue_users VARCHAR(255),
    complement_users VARCHAR(255),
    RaisonSociale VARCHAR(255),
    Siret VARCHAR(14) UNIQUE,
    is_moral BOOLEAN DEFAULT FALSE, -- Ajout pour distinguer personne physique/morale
    id_commune mediumint(8) UNSIGNED,
    FOREIGN KEY (id_commune) REFERENCES `commune`(id_commune)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Roles`
CREATE TABLE Roles (
    id_roles INT PRIMARY KEY AUTO_INCREMENT,
    nom_roles VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `User_role` (table de liaison pour les rôles des utilisateurs)
CREATE TABLE User_role (
    id_users INT UNSIGNED NOT NULL,
    id_roles INT NOT NULL,
    PRIMARY KEY (id_users, id_roles),
    FOREIGN KEY (id_users) REFERENCES Users(id_users) ON DELETE CASCADE,
    FOREIGN KEY (id_roles) REFERENCES Roles(id_roles) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Biens`
CREATE TABLE Biens (
    id_biens INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    designation_bien VARCHAR(255) NOT NULL,
    rue_biens VARCHAR(255) NOT NULL,
    complement_biens VARCHAR(255),
    superficie_biens DECIMAL(10,2) NOT NULL,
    description_biens TEXT,
    animaux_biens BOOLEAN DEFAULT FALSE,
    nb_couchage INT NOT NULL,
    id_TypeBien INT NOT NULL,
    id_commune mediumint(8) UNSIGNED NOT NULL,
    id_proprietaire INT UNSIGNED NOT NULL, -- Ajout de la clé étrangère vers Users
    FOREIGN KEY (id_TypeBien) REFERENCES Type_Bien(id_typebien),
    FOREIGN KEY (id_commune) REFERENCES `commune`(id_commune),
    FOREIGN KEY (id_proprietaire) REFERENCES Users(id_users) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Photos`
CREATE TABLE Photos (
    id_photo INT PRIMARY KEY AUTO_INCREMENT,
    nom_photo VARCHAR(255),
    lien_photo VARCHAR(255) NOT NULL,
    id_biens INT UNSIGNED NOT NULL,
    FOREIGN KEY (id_biens) REFERENCES Biens(id_biens) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Saison`
CREATE TABLE Saison (
    id_saison INT PRIMARY KEY AUTO_INCREMENT,
    lib_saison VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Tarifs`
CREATE TABLE Tarifs (
    id_tarif INT PRIMARY KEY AUTO_INCREMENT,
    prix_semaine DECIMAL(10,2) NOT NULL,
    annee INT NOT NULL,
    id_biens INT UNSIGNED NOT NULL,
    id_saison INT NOT NULL,
    FOREIGN KEY (id_biens) REFERENCES Biens(id_biens) ON DELETE CASCADE,
    FOREIGN KEY (id_saison) REFERENCES Saison(id_saison)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Reservations`
CREATE TABLE Reservations (
    id_reservation INT PRIMARY KEY AUTO_INCREMENT,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    id_users INT UNSIGNED NOT NULL, -- Renommé de id_locataire à id_users
    id_biens INT UNSIGNED NOT NULL,
    id_tarif INT NOT NULL,
    FOREIGN KEY (id_users) REFERENCES Users(id_users) ON DELETE CASCADE,
    FOREIGN KEY (id_biens) REFERENCES Biens(id_biens) ON DELETE CASCADE,
    FOREIGN KEY (id_tarif) REFERENCES Tarifs(id_tarif)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Prestataire`
CREATE TABLE Prestataire (
    id_prestataire INT PRIMARY KEY AUTO_INCREMENT,
    lib_prestataire VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table `Se_Compose`
CREATE TABLE Se_Compose (
    id_prestataire INT NOT NULL,
    id_biens INT UNSIGNED NOT NULL,
    quantite_prestataire INT,
    PRIMARY KEY (id_prestataire, id_biens),
    FOREIGN KEY (id_prestataire) REFERENCES Prestataire(id_prestataire) ON DELETE CASCADE,
    FOREIGN KEY (id_biens) REFERENCES Biens(id_biens) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion des rôles de base
INSERT INTO Roles (nom_roles) VALUES (
    'Administrateur'
), (
    'Propriétaire'
), (
    'Locataire'
);
