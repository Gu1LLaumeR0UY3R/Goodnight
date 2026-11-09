-- =============================================================================
--  GOODNIGHT - Jeu de données de démonstration (personnages et lieux fictifs)
-- =============================================================================
--  À importer sur une base VIDE créée par database/goodnight.sql :
--      mysql -u root -p goodnight < database/seed_demo.sql
--
--  Mot de passe de TOUS les comptes : Demo!2026
--    Admin        : admin@example.com
--    Propriétaires: claire.marchand@example.com, julien.rousseau@example.com,
--                   sophie.lambert@example.com
--    Locataires   : thomas.girard@example.com, camille.dubois@example.com,
--                   lucas.fontaine@example.com, emma.lefevre@example.com,
--                   hugo.mercier@example.com, lea.bertrand@example.com
--    Les deux rôles: nathalie.roy@example.com
--  Les adresses e-mail utilisent le domaine réservé example.com (aucun envoi réel).
--  À ne jamais importer en production.
-- =============================================================================

USE `goodnight`;
SET NAMES utf8mb4;
START TRANSACTION;

-- Communes de démonstration (ignorées si votre table commune est déjà chargée)
INSERT IGNORE INTO `commune` (`ville_departement`,`ville_slug`,`ville_nom`,`ville_nom_simple`,`ville_nom_reel`,`ville_code_postal`,`ville_code_commune`,`ville_longitude_deg`,`ville_latitude_deg`) VALUES
('24','sarlat-la-caneda','SARLAT-LA-CANEDA','sarlat la caneda','Sarlat-la-Canéda','24200','24520',1.216,44.8894),
('06','cannes','CANNES','cannes','Cannes','06400','06029',7.0174,43.5528),
('74','annecy','ANNECY','annecy','Annecy','74000','74010',6.1294,45.8992),
('33','arcachon','ARCACHON','arcachon','Arcachon','33120','33009',-1.1685,44.6588),
('06','nice','NICE','nice','Nice','06000','06088',7.262,43.7102),
('84','avignon','AVIGNON','avignon','Avignon','84000','84007',4.8055,43.9493),
('17','la-rochelle','LA ROCHELLE','la rochelle','La Rochelle','17000','17300',-1.1511,46.1603),
('33','bordeaux','BORDEAUX','bordeaux','Bordeaux','33000','33063',-0.5792,44.8378),
('68','colmar','COLMAR','colmar','Colmar','68000','68066',7.3585,48.0794),
('19','brive-la-gaillarde','BRIVE-LA-GAILLARDE','brive la gaillarde','Brive-la-Gaillarde','19100','19031',1.5326,45.1584),
('64','biarritz','BIARRITZ','biarritz','Biarritz','64200','64122',-1.5586,43.4832),
('35','saint-malo','SAINT-MALO','saint malo','Saint-Malo','35400','35288',-2.0257,48.6493),
('74','chamonix-mont-blanc','CHAMONIX-MONT-BLANC','chamonix mont blanc','Chamonix-Mont-Blanc','74400','74056',6.8694,45.9237);

-- Administrateur
INSERT INTO `admin` (`id_admin`,`nom_admin`,`prenom_admin`,`email_admin`,`mot_de_passe_admin`,`is_admin`) VALUES (1,'Goodnight','Admin','admin@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O',1);

-- Utilisateurs (propriétaires et locataires)
INSERT INTO `locataire` (`id_locataire`,`nom_locataire`,`prenom_locataire`,`dateNaissance_locataire`,`email_locataire`,`password_locataire`,`tel_locataire`,`rue_locataire`,`RaisonSociale`,`id_commune`,`frames_unlocked`) VALUES
(1,'Marchand','Claire','1979-03-14','claire.marchand@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980001','14 rue des Tilleuls',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='24520'),0),
(2,'Rousseau','Julien','1975-09-02','julien.rousseau@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980002','8 avenue de la Croisette',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='06029'),0),
(3,'Lambert','Sophie','1982-11-23','sophie.lambert@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980003','27 promenade des Anglais','Lambert Locations',(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='06088'),0),
(4,'Girard','Thomas','1995-01-30','thomas.girard@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980004','5 rue Sainte-Catherine',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='33063'),0),
(5,'Dubois','Camille','1998-06-17','camille.dubois@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980005','31 rue Paul Bert',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='19031'),1),
(6,'Fontaine','Lucas','1990-12-05','lucas.fontaine@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980006','2 place de la Comédie',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='84007'),0),
(7,'Lefèvre','Emma','1993-04-21','emma.lefevre@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980007','19 quai Saint-Pierre',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='74010'),0),
(8,'Mercier','Hugo','1988-08-09','hugo.mercier@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980008','6 rue du Parlement',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='17300'),0),
(9,'Bertrand','Léa','2000-02-27','lea.bertrand@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980009','11 rue des Marchands',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='68066'),0),
(10,'Roy','Nathalie','1971-07-12','nathalie.roy@example.com','$2y$10$d/jIboxp6IO2XgIhZblV9.euPifAxyO/mBaZFP5AvVT8MtnBWb89O','+33639980010','23 rue de la Paix',NULL,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='17300'),0);

-- Rôles : 1 = Propriétaire, 2 = Locataire
INSERT INTO `user_role` (`id_roles`,`id_locataire`) VALUES (1,1),(1,2),(1,3),(2,4),(2,5),(2,6),(2,7),(2,8),(2,9),(1,10),(2,10);

-- Biens : 7 validés, 1 en attente de validation, 1 refusé
INSERT INTO `biens` (`id_biens`,`designation_bien`,`rue_biens`,`superficie_biens`,`description_biens`,`animaux_biens`,`nb_couchage`,`id_TypeBien`,`id_commune`,`id_locataire`,`statut_validation`,`date_soumission`,`date_validation`,`id_admin_validateur`,`motif_refus`) VALUES
(1,'Cabane des Cimes','Lieu-dit Les Combes',28,'Cabane perchée à six mètres du sol au cœur d''une chênaie. Lit double, poêle à bois, terrasse suspendue et petit-déjeuner livré au pied de l''échelle.',0,2,4,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='24520'),1,'valide','2026-02-11 10:00:00','2026-02-11 16:30:00',1,NULL),
(2,'Villa Horizon','32 chemin des Collines',180,'Villa contemporaine avec grande piscine et terrasse en bois exotique, à dix minutes des plages. Quatre chambres climatisées, cuisine ouverte et salon lumineux.',0,8,1,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='06029'),2,'valide','2026-03-12 10:00:00','2026-03-12 16:30:00',1,NULL),
(3,'La Tour des Bois','Chemin du Pré Vert',45,'Cabane-tour sur deux niveaux accrochée à un versant boisé. Mezzanine, coin cuisine et coursive panoramique avec vue sur la forêt.',0,4,4,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='74010'),1,'valide','2026-04-13 10:00:00','2026-04-13 16:30:00',1,NULL),
(4,'Maison Blanche','9 allée des Mimosas',140,'Maison d''architecte aux lignes épurées, piscine miroir et grandes baies vitrées. Idéale pour un séjour en famille à quelques minutes du bassin.',0,6,2,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='33009'),2,'valide','2026-05-14 10:00:00','2026-05-14 16:30:00',1,NULL),
(5,'Villa Bleu Azur','4 corniche des Oliviers',120,'Villa de charme dominant la mer, tuiles provençales, jardin fleuri et palmiers. Réveil face à l''horizon, plage accessible à pied.',0,6,1,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='06088'),3,'valide','2026-01-15 10:00:00','2026-01-15 16:30:00',1,NULL),
(6,'Villa Patio','17 chemin des Cigales',160,'Villa de plain-pied organisée autour d''un patio et d''une piscine chauffée. Cuisine d''été, terrasse ombragée et jardin méditerranéen.',0,8,1,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='84007'),3,'valide','2026-02-16 10:00:00','2026-02-16 16:30:00',1,NULL),
(7,'Maison de Pays','6 rue des Ardoises',105,'Maison récente à toiture d''ardoise, jardin clos et garage. Calme, lumineuse et idéale pour découvrir le littoral à vélo.',1,5,2,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='17300'),10,'valide','2026-03-17 10:00:00','2026-03-17 16:30:00',1,NULL),
(8,'Appartement des Chartrons','22 rue Notre-Dame',52,'Appartement rénové dans le quartier des Chartrons : séjour lumineux, cuisine équipée et chambre sur cour calme.',0,3,3,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='33063'),10,'en_attente','2026-10-30 18:20:00',NULL,NULL,NULL),
(9,'Studio centre historique','3 rue des Clefs',21,'Studio en centre-ville.',0,2,3,(SELECT `id_commune` FROM `commune` WHERE `ville_code_commune`='68066'),2,'refuse','2026-10-04 11:00:00','2026-10-05 09:15:00',1,'Photos manquantes et description insuffisante : merci de compléter l''annonce avant une nouvelle soumission.');

-- Photos (fichiers présents dans public/uploads)
INSERT INTO `photos` (`nom_photo`,`lien_photo`,`id_biens`) VALUES ('Cabane des Cimes','/uploads/photo_692874b32b59d.jpg',1),
('Villa Horizon','/uploads/photo_692874b32ed0e.jpg',2),
('La Tour des Bois','/uploads/photo_6936d52c6dcf2.jpg',3),
('Maison Blanche','/uploads/photo_69401640903b3.webp',4),
('Villa Bleu Azur','/uploads/photo_69411dd2e3ade.jpg',5),
('Villa Patio','/uploads/photo_69411dd2e478b.jpg',6),
('Maison de Pays','/uploads/maison-ardoise.jpg',7);

-- Prestations de chaque bien (id_prestation, id_biens, quantité)
INSERT INTO `se_compose` (`id_prestation`,`id_biens`,`quantite_prestation`) VALUES (1,1,1),(2,1,1),(4,1,1),(5,1,1),(7,1,1),(8,1,1),(13,1,1),(22,1,1),(24,1,1),(3,1,1),(1,2,1),(2,2,4),(4,2,1),(5,2,4),(7,2,1),(9,2,1),(10,2,1),(12,2,1),(16,2,1),(22,2,1),(25,2,1),(1,3,1),(2,3,1),(4,3,1),(5,3,2),(7,3,1),(13,3,1),(24,3,1),(1,4,1),(2,4,2),(4,4,1),(5,4,3),(7,4,1),(8,4,1),(9,4,1),(10,4,1),(11,4,1),(12,4,1),(15,4,1),(1,5,1),(2,5,3),(4,5,1),(5,5,3),(7,5,1),(8,5,1),(12,5,1),(10,5,1),(23,5,1),(1,6,1),(2,6,3),(4,6,1),(5,6,4),(7,6,1),(8,6,1),(9,6,1),(10,6,1),(12,6,1),(22,6,1),(1,7,1),(2,7,1),(4,7,1),(5,7,3),(7,7,1),(8,7,1),(11,7,1),(13,7,1),(15,7,1),(1,8,1),(2,8,1),(4,8,1),(5,8,1),(23,8,1),(13,8,1);

-- Tarifs hebdomadaires : 4 saisons x 3 années pour chaque bien validé
INSERT INTO `tarifs` (`id_tarif`,`prix_semaine`,`annee`,`id_biens`,`id_saison`) VALUES (1,420.00,2025,1,1),
(2,630.00,2025,1,2),
(3,320.00,2025,1,3),
(4,380.00,2025,1,4),
(5,420.00,2026,1,1),
(6,630.00,2026,1,2),
(7,320.00,2026,1,3),
(8,380.00,2026,1,4),
(9,420.00,2027,1,1),
(10,630.00,2027,1,2),
(11,320.00,2027,1,3),
(12,380.00,2027,1,4),
(13,1900.00,2025,2,1),
(14,2850.00,2025,2,2),
(15,1420.00,2025,2,3),
(16,1710.00,2025,2,4),
(17,1900.00,2026,2,1),
(18,2850.00,2026,2,2),
(19,1420.00,2026,2,3),
(20,1710.00,2026,2,4),
(21,1900.00,2027,2,1),
(22,2850.00,2027,2,2),
(23,1420.00,2027,2,3),
(24,1710.00,2027,2,4),
(25,560.00,2025,3,1),
(26,840.00,2025,3,2),
(27,420.00,2025,3,3),
(28,500.00,2025,3,4),
(29,560.00,2026,3,1),
(30,840.00,2026,3,2),
(31,420.00,2026,3,3),
(32,500.00,2026,3,4),
(33,560.00,2027,3,1),
(34,840.00,2027,3,2),
(35,420.00,2027,3,3),
(36,500.00,2027,3,4),
(37,1300.00,2025,4,1),
(38,1950.00,2025,4,2),
(39,980.00,2025,4,3),
(40,1170.00,2025,4,4),
(41,1300.00,2026,4,1),
(42,1950.00,2026,4,2),
(43,980.00,2026,4,3),
(44,1170.00,2026,4,4),
(45,1300.00,2027,4,1),
(46,1950.00,2027,4,2),
(47,980.00,2027,4,3),
(48,1170.00,2027,4,4),
(49,1500.00,2025,5,1),
(50,2250.00,2025,5,2),
(51,1120.00,2025,5,3),
(52,1350.00,2025,5,4),
(53,1500.00,2026,5,1),
(54,2250.00,2026,5,2),
(55,1120.00,2026,5,3),
(56,1350.00,2026,5,4),
(57,1500.00,2027,5,1),
(58,2250.00,2027,5,2),
(59,1120.00,2027,5,3),
(60,1350.00,2027,5,4),
(61,1400.00,2025,6,1),
(62,2100.00,2025,6,2),
(63,1050.00,2025,6,3),
(64,1260.00,2025,6,4),
(65,1400.00,2026,6,1),
(66,2100.00,2026,6,2),
(67,1050.00,2026,6,3),
(68,1260.00,2026,6,4),
(69,1400.00,2027,6,1),
(70,2100.00,2027,6,2),
(71,1050.00,2027,6,3),
(72,1260.00,2027,6,4),
(73,900.00,2025,7,1),
(74,1350.00,2025,7,2),
(75,680.00,2025,7,3),
(76,810.00,2025,7,4),
(77,900.00,2026,7,1),
(78,1350.00,2026,7,2),
(79,680.00,2026,7,3),
(80,810.00,2026,7,4),
(81,900.00,2027,7,1),
(82,1350.00,2027,7,2),
(83,680.00,2027,7,3),
(84,810.00,2027,7,4);

-- Réservations (passées, en cours de saison et à venir) ; id_tarif = tarif réellement retenu par l'application
INSERT INTO `reservations` (`date_debut`,`date_fin`,`id_locataire`,`id_biens`,`id_tarif`) VALUES ('2026-04-04','2026-04-11',6,1,5),
('2026-07-11','2026-07-18',4,1,6),
('2026-08-08','2026-08-15',5,1,6),
('2026-09-05','2026-09-12',10,1,7),
('2026-12-26','2027-01-02',7,1,8),
('2025-08-09','2025-08-16',4,2,14),
('2026-07-18','2026-07-25',6,2,18),
('2026-08-01','2026-08-08',8,2,18),
('2026-12-19','2026-12-26',9,2,20),
('2026-06-20','2026-06-27',5,3,30),
('2026-09-12','2026-09-19',7,3,31),
('2027-01-09','2027-01-16',4,3,36),
('2026-05-23','2026-05-30',9,4,41),
('2026-07-25','2026-08-01',8,4,42),
('2026-11-21','2026-11-28',5,4,43),
('2026-03-14','2026-03-21',4,5,53),
('2026-08-15','2026-08-22',7,5,54),
('2026-12-12','2026-12-19',6,5,56),
('2026-07-04','2026-07-11',9,6,66),
('2026-10-10','2026-10-17',6,6,67),
('2026-05-02','2026-05-09',5,7,77),
('2026-08-22','2026-08-29',8,7,78),
('2027-02-06','2027-02-13',9,7,84);

-- Périodes bloquées par les propriétaires
INSERT INTO `blocages` (`id_biens`,`date_debut`,`date_fin`,`motif`,`commentaire`) VALUES (1,'2026-11-10','2026-11-14','entretien','Nettoyage des abords et révision du poêle'),(6,'2026-12-01','2026-12-10','personnel','Séjour des propriétaires');

-- Avis : 13 publiés, 1 signalé, 1 en attente de modération
INSERT INTO `commentaires` (`id_biens`,`id_locataire`,`note`,`titre`,`contenu`,`date_creation`,`statut`,`signale`) VALUES (1,4,5,'Une nuit magique dans les arbres','Réveil au chant des oiseaux, poêle à bois et silence total. Claire est aux petits soins, le petit-déjeuner dans le panier était excellent.','2026-07-19 10:12:00','publie',0),
(1,5,4,'Dépaysement total','Très bel endroit pour se déconnecter. Un peu étroit à deux avec les valises, mais le cadre compense largement.','2026-08-16 18:40:00','publie',0),
(1,10,5,'Parfait pour se ressourcer','Un week-end prolongé hors du temps. Je recommande de monter avec peu de bagages !','2026-09-13 09:05:00','publie',0),
(2,6,5,'Villa sublime','Piscine impeccable, terrasse immense et literie confortable. Nous reviendrons sans hésiter.','2026-07-26 20:15:00','publie',0),
(2,8,4,'Très belle villa','Séjour très agréable en famille. Seul bémol : la climatisation du salon est un peu bruyante.','2026-08-09 11:30:00','publie',0),
(2,4,4,'Souvenirs d''août','Excellente situation, proche des plages. Check-in rapide et propriétaire réactif.','2025-08-17 16:20:00','publie',0),
(3,5,4,'Cabane originale','L''expérience est unique, la mezzanine est très cosy. Prévoir de bonnes chaussures pour l''accès.','2026-06-28 12:00:00','publie',0),
(3,7,3,'Joli mais un peu bruyant','Le lieu est charmant, mais la nuit les bruits de la forêt peuvent surprendre. À réserver si l''on aime la nature.','2026-09-20 08:45:00','publie',0),
(4,8,5,'Idéal en famille','Grande maison très bien équipée, les enfants ont adoré la piscine. Tout était propre et soigné.','2026-08-02 19:10:00','publie',0),
(4,9,4,'Bel emplacement','Maison lumineuse et agréable. Le jardin mériterait un peu plus d''ombre en été.','2026-05-31 15:25:00','publie',0),
(5,7,5,'Vue à couper le souffle','Le balcon face à la mer vaut à lui seul le séjour. Accueil chaleureux de Sophie.','2026-08-23 17:50:00','publie',0),
(5,4,4,'Week-end de mars réussi','Belle villa et calme absolu hors saison. Quelques marches à prévoir pour accéder à la plage.','2026-03-22 10:00:00','publie',0),
(6,9,4,'Patio très agréable','Le patio et la piscine chauffée sont un vrai plus. Cuisine bien équipée.','2026-07-12 13:35:00','publie',0),
(6,6,2,'Annonce peu conforme','Les photos ne correspondaient pas tout à fait à ce que nous avons trouvé sur place. Dommage.','2026-10-18 21:05:00','publie',1),
(7,8,5,'Un séjour parfait','Maison confortable, jardin clos pratique avec un chien. Les vélos du garage sont un bonus appréciable.','2026-08-30 09:30:00','publie',0),
(7,5,4,'Charmante maison','Maison agréable et propre, bien située. (En attente de modération.)','2026-05-10 14:00:00','en_attente',0);

-- Likes sur les avis (id_commentaire, id_locataire)
INSERT INTO `commentaire_likes` (`id_commentaire`,`id_locataire`) VALUES (1,5),(1,6),(1,7),(2,4),(2,9),(3,4),(4,5),(4,9),(4,7),(6,7),(6,8),(8,9),(9,7),(9,5),(10,8),(11,9),(11,4),(12,5),(13,6),(15,4),(15,9);

-- Favoris (id_locataire, id_biens)
INSERT INTO `favoris` (`id_locataire`,`id_biens`) VALUES (4,2),(4,3),(5,1),(5,2),(5,4),(6,1),(6,5),(7,2),(7,4),(8,4),(8,6),(9,4),(9,6),(9,7),(10,2);

-- Signalements : en attente, traité, rejeté (dont un visiteur non connecté)
INSERT INTO `signalements` (`id_biens`,`id_locataire`,`email_signaleur`,`motif`,`description`,`statut`,`date_signalement`,`date_traitement`,`id_admin_traitant`,`commentaire_admin`) VALUES
(6,5,NULL,'photos_trompeuses','Les photos de la piscine semblent anciennes et ne correspondent plus à l''état actuel.','en_attente','2026-10-20 14:30:00',NULL,NULL,NULL),
(4,7,NULL,'fausses_informations','La superficie annoncée paraît supérieure à la réalité.','traite','2026-09-02 09:10:00','2026-09-04 11:00:00',1,'Vérification faite avec le propriétaire : la fiche a été corrigée.'),
(2,NULL,'visiteur@example.com','autre','Je trouve le tarif trop élevé.','rejete','2026-08-12 19:45:00','2026-08-13 08:30:00',1,'Le tarif relève de la liberté du propriétaire : signalement non fondé.');

-- Notifications (certaines lues, d'autres non)
INSERT INTO `notifications` (`user_id`,`type`,`title`,`message`,`link`,`is_read`,`created_at`) VALUES (1,'bien_validated','Annonce validée','Votre bien « Cabane des Cimes » a été validé et est désormais visible.','/proprietaire',1,'2026-02-11 16:31:00'),
(3,'bien_validated','Annonce validée','Votre bien « Villa Bleu Azur » a été validé et est désormais visible.','/proprietaire',1,'2026-04-13 16:31:00'),
(10,'bien_validated','Annonce validée','Votre bien « Maison de Pays » a été validé et est désormais visible.','/proprietaire',1,'2026-07-17 16:31:00'),
(2,'bien_change','Annonce refusée','Votre bien « Studio centre historique » a été refusé : photos manquantes et description insuffisante.','/proprietaire',0,'2026-10-05 09:16:00'),
(1,'reservation_created','Nouvelle réservation','Emma Lefèvre a réservé « Cabane des Cimes » du 26/12/2026 au 02/01/2027.','/proprietaire/myReservations',0,'2026-10-28 20:12:00'),
(7,'reservation_created','Réservation confirmée','Votre réservation de « Cabane des Cimes » du 26/12/2026 au 02/01/2027 est enregistrée.','/locataire/myReservations',1,'2026-10-28 20:12:00'),
(2,'reservation_created','Nouvelle réservation','Léa Bertrand a réservé « Villa Horizon » du 19/12/2026 au 26/12/2026.','/proprietaire/myReservations',0,'2026-10-31 09:48:00'),
(9,'reservation_created','Réservation confirmée','Votre réservation de « Villa Horizon » du 19/12/2026 au 26/12/2026 est enregistrée.','/locataire/myReservations',0,'2026-10-31 09:48:00'),
(2,'reservation_created','Nouvelle réservation','Camille Dubois a réservé « Maison Blanche » du 21/11/2026 au 28/11/2026.','/proprietaire/myReservations',1,'2026-10-22 15:05:00');

COMMIT;
