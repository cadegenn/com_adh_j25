--
-- Structure de la table `adh_adherents`
--

CREATE TABLE IF NOT EXISTS `#__adh_adherents` (
  `id` double unsigned NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'M., Mme, Société, Association',
  `personne_morale` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Nom de la société ou association. Entrez les coordonnées du contact dans nom/prénom',
  `nom` varchar(255) COLLATE utf8_bin NOT NULL,
  `prenom` varchar(255) COLLATE utf8_bin NOT NULL,
  `date_naissance` date NOT NULL,
  `adresse` varchar(255) COLLATE utf8_bin NOT NULL,
  `adresse2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `cp` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `ville` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `pays` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `gsm` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `profession_id` int(16) unsigned DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `created_by` double unsigned DEFAULT NULL COMMENT 'joomla -> #__users.id',
  `modification_date` datetime DEFAULT NULL,
  `modified_by` double unsigned DEFAULT NULL COMMENT 'joomla -> #__users.id',
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `published` int(2) NOT NULL,
  `description` text COLLATE utf8_bin,
  `origine_id` int(8) unsigned DEFAULT NULL,
  `origine_text` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `imposable` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `recv_newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `recv_infos` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `adh_groups`
--

CREATE TABLE IF NOT EXISTS `#__adh_groups` (
  `id` double unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8_bin NOT NULL,
  `creation_date` datetime NULL DEFAULT NULL,
  `modification_date` datetime NULL DEFAULT NULL,
  `modified_by` double unsigned NULL COMMENT 'joomla -> #__users.id',
  `parent_group_id` double unsigned NOT NULL,
  `published` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `adh_groups_members`
--

CREATE TABLE IF NOT EXISTS `#__adh_groups_members` (
  `group_id` double unsigned NOT NULL,
  `adherent_id` double unsigned NOT NULL,
  UNIQUE KEY `id` (`group_id`,`adherent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--
CREATE TABLE IF NOT EXISTS `#__adh_tarifs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) COLLATE utf8_bin NOT NULL,
  `tarif` int(10) unsigned NOT NULL,
  `monnaie` varchar(10) COLLATE utf8_bin NOT NULL,
  `symbol` varchar(10) COLLATE utf8_bin NOT NULL,
  `published` int(2) NOT NULL DEFAULT '1',
  `creation_date` datetime NULL DEFAULT NULL,
  `modification_date` datetime NULL DEFAULT NULL,
  `modified_by` double unsigned NULL COMMENT 'joomla -> #__users.id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `origines`
--

CREATE TABLE IF NOT EXISTS `#__adh_origines` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categorie` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `published` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `creation_date` datetime NULL DEFAULT NULL,
  `modification_date` datetime NULL DEFAULT NULL,
  `modified_by` double unsigned NULL COMMENT 'joomla -> #__users.id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;

-- --------------------------------------------------------

--
-- Structure de la table `professions`
--

CREATE TABLE IF NOT EXISTS `#__adh_professions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categorie` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `published` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `creation_date` datetime NULL DEFAULT NULL,
  `modification_date` datetime NULL DEFAULT NULL,
  `modified_by` double unsigned NULL COMMENT 'joomla -> #__users.id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;

-- --------------------------------------------------------

--
-- Structure de la table `cotisations`
--

CREATE TABLE IF NOT EXISTS `#__adh_cotisations` (
  `id` DOUBLE unsigned NOT NULL AUTO_INCREMENT,
  `adherent_id` double unsigned NOT NULL,
  `tarif_id` int(10) unsigned NOT NULL,
  `montant` int(10) unsigned NOT NULL COMMENT 'le montant REEL de la cotisation (certains montants peuvent être modulés par l''adhérent)',
  `mode_paiement` VARCHAR( 32 ) NOT NULL,
  `date` date DEFAULT NULL COMMENT 'OBSOLETE : si la date est NULL : l''adhérent s''est inscrit mais sa cotisation n''a pas encore été reçue/encaissée... si la date n''est pas NULL et que l''année est égale à  l''année en cours, alors l''adhérent est inscrit cette année (civile)',
  `date_debut_cotiz` date NULL,
  `date_fin_cotiz` date NULL,
  `payee` TINYINT( 1 ) UNSIGNED NULL DEFAULT 0,
  `commentaire` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'on peut y mettre le numéro de chèque, ou un numéro de transaction',
  `creation_date` datetime NULL DEFAULT NULL,
  `created_by` double unsigned DEFAULT NULL COMMENT 'joomla -> #__users.id',
  `modification_date` datetime NULL DEFAULT NULL,
  `modified_by` double unsigned NULL COMMENT 'joomla -> #__users.id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;

