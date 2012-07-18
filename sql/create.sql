# --------------------------------------------------------
# Host:                         ploki.info
# Server version:               5.1.41-3ubuntu12.8
# Server OS:                    debian-linux-gnu
# HeidiSQL version:             6.0.0.3603
# Date/time:                    2011-02-25 17:29:55
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

# Dumping structure for table db146381313.commentaire
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idKdo` int(11) unsigned NOT NULL DEFAULT '0',
  `commentaire` blob NOT NULL,
  `creeLe` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `creePar` int(11) unsigned NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `supprime` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table db146381313.evenement
CREATE TABLE IF NOT EXISTS `evenement` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `titre` varchar(60) NOT NULL DEFAULT '',
  `description` blob,
  `groupe` int(11) unsigned NOT NULL DEFAULT '0',
  `supprime` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table db146381313.groupe
CREATE TABLE IF NOT EXISTS `groupe` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `idMembre` int(11) unsigned NOT NULL DEFAULT '0',
  `nom` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`,`idMembre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table db146381313.kdo
CREATE TABLE IF NOT EXISTS `kdo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pour` int(11) unsigned NOT NULL DEFAULT '0',
  `titre` varchar(50) NOT NULL DEFAULT '',
  `description` blob,
  `url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `creeLe` date NOT NULL DEFAULT '0000-00-00',
  `creePar` int(11) unsigned NOT NULL DEFAULT '0',
  `reserveLe` date DEFAULT NULL,
  `reservePar` int(11) unsigned DEFAULT NULL,
  `acheteLe` date DEFAULT NULL,
  `achetePar` int(11) unsigned DEFAULT NULL,
  `partage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `supprime` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `priorite` tinyint(1) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table db146381313.membre
CREATE TABLE IF NOT EXISTS `membre` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `motPasse` varchar(50) NOT NULL DEFAULT '',
  `prenom` varchar(50) DEFAULT NULL,
  `anniversaire` date DEFAULT NULL,
  `presentation` blob,
  `email` varchar(60) DEFAULT NULL,
  `photo` varchar(25) DEFAULT NULL,
  `aime` text NOT NULL,
  `aimepas` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.


# Dumping structure for table db146381313.partage
CREATE TABLE IF NOT EXISTS `partage` (
  `idKdo` int(11) unsigned DEFAULT '0',
  `partagePar` int(11) unsigned NOT NULL DEFAULT '0',
  `combien` varchar(50) DEFAULT NULL,
  `supprime` tinyint(1) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

# Data exporting was unselected.
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
