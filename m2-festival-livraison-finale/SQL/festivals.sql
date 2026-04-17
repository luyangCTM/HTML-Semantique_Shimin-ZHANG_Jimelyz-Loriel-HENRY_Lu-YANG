SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `GLAM_FESTIVAL`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `GLAM_FESTIVAL`;

DROP TABLE IF EXISTS `Festivals`;

CREATE TABLE `Festivals` (
  `idFestival` int NOT NULL AUTO_INCREMENT,
  `edition_year` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `description` text,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_iso` varchar(16) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `location_city` varchar(120) NOT NULL,
  `location_country` varchar(120) NOT NULL,
  `organizer_name` varchar(255) DEFAULT NULL,
  `funder_name` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `maximum_attendee_capacity` int DEFAULT NULL,
  `in_language` varchar(120) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `official_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idFestival`),
  UNIQUE KEY `slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `Festivals` (
  `idFestival`,
  `edition_year`,
  `name`,
  `slug`,
  `description`,
  `start_date`,
  `end_date`,
  `duration_iso`,
  `location_name`,
  `location_city`,
  `location_country`,
  `organizer_name`,
  `funder_name`,
  `keywords`,
  `maximum_attendee_capacity`,
  `in_language`,
  `image_path`,
  `official_url`
) VALUES
  (1, 2025, 'Festival de Cannes', 'festival-de-cannes', 'Rendez-vous majeur du cinéma mondial, le Festival de Cannes combine compétition officielle, marché du film et circulation internationale des œuvres.', '2025-05-13', '2025-05-24', 'P12D', 'Palais des Festivals et des Congrès', 'Cannes', 'France', 'Festival de Cannes', 'Ville de Cannes', 'cinéma, compétition, auteur, tapis rouge', NULL, 'fr,en', '/images/covers/cannes.png', 'https://www.festival-cannes.com/'),
  (2, 2025, 'Festival d''Avignon', 'festival-avignon', 'Grand temps fort du spectacle vivant en France, le Festival d''Avignon articule théâtre, danse, performance et création contemporaine dans toute la ville.', '2025-07-05', '2025-07-26', 'P22D', 'Palais des Papes et lieux du festival', 'Avignon', 'France', 'Festival d''Avignon', 'Ministère de la Culture', 'théâtre, danse, performance, création contemporaine', NULL, 'fr,en', '/images/covers/avignon.png', 'https://festival-avignon.com/'),
  (3, 2026, 'Hellfest', 'hellfest', 'Festival de musiques metal et hard rock organisé à Clisson, Hellfest rassemble une programmation internationale sur plusieurs scènes en plein air.', '2026-06-18', '2026-06-21', 'P4D', 'Site du Hellfest', 'Clisson', 'France', 'Hellfest Productions', NULL, 'metal, hard rock, extreme music, live', 240000, 'fr,en', '/images/covers/hellfest.jpg', 'https://hellfest.fr/'),
  (4, 2026, 'Jazz in Marciac', 'jazz-in-marciac', 'Festival d’été installé au cœur du Gers, Jazz in Marciac associe grands concerts sous chapiteau, village animé et transmission pédagogique.', '2026-07-20', '2026-08-05', 'P17D', 'Chapiteau et places du village', 'Marciac', 'France', 'Association Jazz in Marciac', NULL, 'jazz, concerts, village, masterclasses', 300000, 'fr,en', '/images/covers/marciac.jpg', 'https://www.jazzinmarciac.com/'),
  (5, 2026, 'Festival Interceltique de Lorient', 'interceltique-lorient', 'Grand événement dédié aux cultures celtiques, le festival combine concerts, bagadoù, danse, expositions et rencontres dans l''espace urbain.', '2026-07-31', '2026-08-09', 'P10D', 'Lorient et agglomération', 'Lorient', 'France', 'Festival Interceltique de Lorient', 'Ville de Lorient', 'cultures celtiques, bagad, musique, danse', 950000, 'fr,en,es', '/images/covers/lorient.jpg', 'https://www.festival-interceltique.bzh/'),
  (6, 2026, 'Francofolies de La Rochelle', 'francofolies', 'Festival consacré aux musiques francophones, les Francofolies articulent grandes scènes, découvertes d''artistes et actions d''éducation culturelle.', '2026-07-10', '2026-07-14', 'P5D', 'Esplanade Saint-Jean d''Acre et centre-ville', 'La Rochelle', 'France', 'Francofolies de La Rochelle', NULL, 'chanson francophone, pop, live, francophonie', NULL, 'fr', '/images/covers/francofolies.png', 'https://www.francofolies.fr/'),
  (7, 2025, 'Montreux Jazz Festival', 'montreux-jazz-festival', 'Installé sur les rives du Léman, le Montreux Jazz Festival dépasse le seul jazz et programme aussi pop, soul, électronique et rencontres professionnelles.', '2025-07-04', '2025-07-19', 'P16D', 'Venues du lac et Place du Marché', 'Montreux', 'Suisse', 'Fondation du Festival de Jazz de Montreux', NULL, 'jazz, lakefront, live, music industry', NULL, 'fr,en', '/images/covers/montreux.jpg', 'https://www.montreuxjazzfestival.com/'),
  (8, 2026, 'Sziget Festival (2026)', 'sziget-festival-2026', 'Grand rendez-vous musical et artistique de Budapest, Sziget déploie concerts, arts visuels, installations immersives et vie de festival sur l’île d’Óbuda.', '2026-08-05', '2026-08-11', 'P7D', 'Óbudai-sziget', 'Budapest', 'Hongrie', 'Sziget Cultural Management', NULL, 'music, arts, island festival, international, immersive', NULL, 'en,hu', '/images/covers/sziget.png', 'https://szigetfestival.com/'),
  (9, 2025, 'Glastonbury Festival', 'glastonbury', 'Festival pluridisciplinaire organisé à Worthy Farm, Glastonbury réunit musique, arts de scène, camping et logique caritative.', '2025-06-25', '2025-06-29', 'P5D', 'Worthy Farm', 'Pilton', 'Royaume-Uni', 'Glastonbury Festivals Ltd', NULL, 'music, arts, camping, charity', NULL, 'en', '/images/covers/glastonbury.jpg', 'https://www.glastonburyfestivals.co.uk/'),
  (10, 2026, 'Edinburgh Festival Fringe', 'edinburgh-fringe', 'Le Fringe d''Édimbourg fonctionne en accès ouvert et agrège théâtre, humour, cabaret, danse et formes expérimentales dans toute la ville.', '2026-08-07', '2026-08-31', 'P25D', 'Multiple venues across the city', 'Edinburgh', 'Royaume-Uni', 'Edinburgh Festival Fringe Society', NULL, 'theatre, comedy, performance, open access', NULL, 'en', '/images/covers/edinburgh.jpg', 'https://www.edfringe.com/');

COMMIT;
