-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Lun 03 Mars 2014 à 16:03
-- Version du serveur: 5.5.35
-- Version de PHP: 5.3.10-1ubuntu3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `paris`
--

-- --------------------------------------------------------

--
-- Structure de la table `choix`
--

CREATE TABLE IF NOT EXISTS `choix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pari_id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `cote` decimal(11,1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pari_id` (`pari_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Contenu de la table `choix`
--

INSERT INTO `choix` (`id`, `pari_id`, `nom`, `cote`) VALUES
(19, 21, 'Canada', 7.0),
(20, 21, 'Etats-Unis', 5.0),
(21, 21, 'Allemagne', 3.0),
(22, 22, 'Stephanie', 2.0),
(23, 22, 'Georgette', 5.0),
(26, 29, '10 cm', 10.0),
(27, 29, '20 cm', 8.0),
(28, 29, '30 cm', 5.0),
(29, 30, 'Monaco !!!!', 5.0),
(30, 30, 'Paris ', 2.0),
(33, 32, 'oui ', 56.0),
(34, 32, 'non', 2.0),
(35, 32, 'peut être ...', 4.0),
(43, 36, 'oui', 12.0),
(44, 36, 'non', 3.0),
(45, 36, 'il va mourir', 2.0),
(49, 38, 'Les Albinos', 2.0),
(50, 38, 'Les Albigoies', 3.0),
(51, 38, 'Les alibis', 5.0),
(52, 39, 'Camille et Guillaume', 2.0),
(53, 39, 'Guillaume et Camille', 2.0),
(54, 39, 'Max et Noémie ...', 4.0),
(55, 40, 'oui', 1.2),
(56, 40, 'non', 1.6);

-- --------------------------------------------------------

--
-- Structure de la table `parieurs`
--

CREATE TABLE IF NOT EXISTS `parieurs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) DEFAULT NULL,
  `mot_passe` varchar(50) DEFAULT NULL,
  `courriel` varchar(255) NOT NULL,
  `nombre_jetons` int(11) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `parieurs`
--

INSERT INTO `parieurs` (`id`, `pseudo`, `mot_passe`, `courriel`, `nombre_jetons`) VALUES
(9, 'bab', '4001b6c1d635f1ff6f8a111e2fd47b10674f1824', 'bab@bab', 100),
(10, 'bob', '670083016a830d7872ec80dcfa8a07c568555baf', 'testreseau.ca', 900),
(11, 'prof', '786310899dde2ec28a0a9e4ccaee48eb94f39729', 'olivier.lafleur@cll.qc.ca', 108),
(12, 'guillaume', '8df0d9fe3a3832d2ae30d38486a024922de7abe5', 'guillaume@test.fr', 100),
(13, 'soso', 'dd569be1c7ebab88f7e59b76ea57e939b4a08e32', 'pol', 134);

-- --------------------------------------------------------

--
-- Structure de la table `parieurs_paris`
--

CREATE TABLE IF NOT EXISTS `parieurs_paris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pari_id` int(11) NOT NULL,
  `parieur_id` int(11) NOT NULL,
  `choix_id` int(11) NOT NULL,
  `mise` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `choix_id` (`choix_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `parieurs_paris`
--

INSERT INTO `parieurs_paris` (`id`, `pari_id`, `parieur_id`, `choix_id`, `mise`) VALUES
(7, 29, 10, 26, 100),
(10, 21, 10, 21, 123),
(11, 30, 9, 30, 2),
(12, 38, 10, 49, 100),
(13, 32, 13, 34, 100),
(14, 40, 13, 56, 100),
(15, 40, 11, 55, 20),
(16, 32, 11, 34, 60);

-- --------------------------------------------------------

--
-- Structure de la table `paris`
--

CREATE TABLE IF NOT EXISTS `paris` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parieur_id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `date_fin` date NOT NULL,
  `choix_gagnant` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `choix_gagnant` (`choix_gagnant`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Contenu de la table `paris`
--

INSERT INTO `paris` (`id`, `parieur_id`, `nom`, `description`, `image`, `date_fin`, `choix_gagnant`) VALUES
(21, 9, 'Qui gagnera les Olympiques?', 'Quel pays remportera le plus de médailles aux Olympiques de Sotchi ?', 'http://t2.gstatic.com/images?q=tbn:ANd9GcR3V-t3pcugSptya04pCeZrgFGPY2J8aWsKhVz3Z2AD1YTQWZF8', '2014-02-20', 19),
(22, 9, 'Qui sera élue miss Pintendre ?', 'Quelle sera la prochaine grande star de Pintendre ? Sera-ce une parfaite inconnue, ou pas ? C''est ce que nous révélera la grande émission "Pintendre''s next Top Model", présentée à TVA les dimanches soirs !', 'http://cdn-lejdd.ladmedia.fr/var/lejdd/storage/images/media/images/archivesphotoscmc/international/concours-de-beaute-a-haifa/320330-1-fre-FR/Concours-de-beaute-a-Haifa_pics_809.jpg', '2014-02-14', 22),
(29, 10, 'Tombée de neige', 'Combien de centimètres de neige tomberont-t-ils aujourd''hui en cette journée de tempête hivernale ?', 'http://www.hd-wallpaper.images-fonds.com/modules/mg3/albums/Paysages_(landscapes)_Wallpaper_HD/Neige_winter/Wallpaper_HD_neige_winter_2012031318_59.jpg', '2014-02-13', 27),
(30, 10, 'Vainqueur l1', 'Qui gagnera la ligue1 cette année ? ', 'http://www.francetvinfo.fr/image/74w0j2og4-c510/908/510/2550105.jpg', '2014-06-10', NULL),
(32, 10, 'Le crash de Guillaume', 'L''avion de Guillaume pour son retour va t''il s''écraser ? Survivra t''il ???', 'http://www.terredisrael.com/Images/Sante/avion-1.jpg', '2014-06-22', NULL),
(36, 10, 'Tyrion', 'Tyrion finira t''il sa vie en prison ? ', 'http://passionalia.files.wordpress.com/2012/07/tyrion-2.jpg', '2014-03-07', NULL),
(38, 9, 'Les albinos', 'Comments s''appellent les habitants d''Albi ?', 'http://airelle.a.i.pic.centerblog.net/o/ef4b3602.jpg', '2014-03-12', NULL),
(39, 9, 'Qui va faire le meilleur projet ?', 'Une réponse évidente ! ', 'http://www.camillefontaine.com/_images/photos/big/collect/002.jpg', '2014-03-07', NULL),
(40, 9, 'Cookie ?', 'Camille refera-t-elle des cookies avant la fin de la session ????????', 'http://www.velux.be/fr-BE/PublishingImages/cookie733.jpg', '2014-03-31', NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `choix`
--
ALTER TABLE `choix`
  ADD CONSTRAINT `choix_ibfk_1` FOREIGN KEY (`pari_id`) REFERENCES `paris` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `parieurs_paris`
--
ALTER TABLE `parieurs_paris`
  ADD CONSTRAINT `parieurs_paris_ibfk_1` FOREIGN KEY (`choix_id`) REFERENCES `choix` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
