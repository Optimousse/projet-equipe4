-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 14 Février 2014 à 20:32
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `paris`
--
CREATE DATABASE IF NOT EXISTS `paris` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `paris`;

-- --------------------------------------------------------

--
-- Structure de la table `choix`
--

CREATE TABLE IF NOT EXISTS `choix` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pari_id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `cote` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pari_id` (`pari_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Contenu de la table `choix`
--

INSERT INTO `choix` (`id`, `pari_id`, `nom`, `cote`) VALUES
(19, 21, 'Canada', 7),
(20, 21, 'Ã‰tats-Unis', 5),
(21, 21, 'Allemagne', 3),
(22, 22, 'Stephanie', 2),
(23, 22, 'Georgette', 5),
(26, 29, '10 cm', 10),
(27, 29, '20 cm', 8),
(28, 29, '30 cm', 5);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `parieurs`
--

INSERT INTO `parieurs` (`id`, `pseudo`, `mot_passe`, `courriel`, `nombre_jetons`) VALUES
(9, 'bab', '4001b6c1d635f1ff6f8a111e2fd47b10674f1824', 'bab@bab', 100),
(10, 'bob', '670083016a830d7872ec80dcfa8a07c568555baf', 'bob@bob.ca', 900);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `parieurs_paris`
--

INSERT INTO `parieurs_paris` (`id`, `pari_id`, `parieur_id`, `choix_id`, `mise`) VALUES
(7, 29, 10, 26, 100),
(10, 21, 10, 21, 123);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

--
-- Contenu de la table `paris`
--

INSERT INTO `paris` (`id`, `parieur_id`, `nom`, `description`, `image`, `date_fin`, `choix_gagnant`) VALUES
(21, 9, 'Qui gagnera les Olympiques?', 'Quel pays remportera le plus de mÃ©dailles aux Olympiques de Sotchi ?', 'http://t2.gstatic.com/images?q=tbn:ANd9GcR3V-t3pcugSptya04pCeZrgFGPY2J8aWsKhVz3Z2AD1YTQWZF8', '2014-02-20', NULL),
(22, 9, 'Qui sera Ã©lue miss Pintendre ?', 'Quelle sera la prochaine grande star de Pintendre ? Sera-ce une parfaite inconnue, ou pas ? C''est ce que nous révélera la grande émission "Pintendre''s next Top Model", présentée à TVA les dimanches soirs !', 'http://cdn-lejdd.ladmedia.fr/var/lejdd/storage/images/media/images/archivesphotoscmc/international/concours-de-beaute-a-haifa/320330-1-fre-FR/Concours-de-beaute-a-Haifa_pics_809.jpg', '2014-02-14', 22),
(29, 10, 'TombÃ©e de neige', 'Combien de centimÃ¨tres de neige tomberont-t-ils aujourd''hui en cette journÃ©e de tempÃªte hivernale ?', 'http://www.hd-wallpaper.images-fonds.com/modules/mg3/albums/Paysages_(landscapes)_Wallpaper_HD/Neige_winter/Wallpaper_HD_neige_winter_2012031318_59.jpg', '2014-02-13', 27);

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
