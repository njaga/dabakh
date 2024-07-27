-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 18 oct. 2023 à 11:54
-- Version du serveur : 10.6.15-MariaDB
-- Version de PHP : 8.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `zulf8960_dabakh`
--

-- --------------------------------------------------------

--
-- Structure de la table `bailleur`
--

CREATE TABLE `bailleur` (
  `id` int(11) NOT NULL,
  `num_dossier` varchar(255) DEFAULT NULL,
  `prenom` text NOT NULL,
  `nom` text NOT NULL,
  `tel` text NOT NULL,
  `adresse` text NOT NULL,
  `annee_inscription` int(11) NOT NULL,
  `pourcentage` int(11) NOT NULL,
  `etat` varchar(255) DEFAULT NULL,
  `cni` text DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `duree_contrat` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `date_enregistrement` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `bailleur`
--

INSERT INTO `bailleur` (`id`, `num_dossier`, `prenom`, `nom`, `tel`, `adresse`, `annee_inscription`, `pourcentage`, `etat`, `cni`, `date_debut`, `duree_contrat`, `id_user`, `date_enregistrement`) VALUES
(12, '001', 'monsieur ismael ', 'NDOYE', '78 383 69 40', 'YOFF APECSY 1', 2011, 10, 'activer', '1751195308289', '2023-10-01', 1, 1, '2023-10-05 18:39:10');

-- --------------------------------------------------------

--
-- Structure de la table `banque`
--

CREATE TABLE `banque` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `num_cheque` varchar(255) DEFAULT NULL,
  `section` varchar(255) NOT NULL,
  `date_operation` date NOT NULL,
  `montant` bigint(20) NOT NULL,
  `structure` varchar(255) NOT NULL,
  `id_mensualite_bailleur` int(11) DEFAULT NULL,
  `id_depense_bailleur` int(11) DEFAULT NULL,
  `id_user` text DEFAULT NULL,
  `pj` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caisse_btp`
--

CREATE TABLE `caisse_btp` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `date_operation` date NOT NULL,
  `montant` bigint(20) NOT NULL,
  `id_user` text DEFAULT NULL,
  `pj` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caisse_caution`
--

CREATE TABLE `caisse_caution` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `date_operation` date NOT NULL,
  `montant` bigint(20) NOT NULL,
  `id_versement` int(11) DEFAULT NULL,
  `id_location` int(11) DEFAULT NULL,
  `id_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `caisse_caution`
--

INSERT INTO `caisse_caution` (`id`, `type`, `motif`, `section`, `date_operation`, `montant`, `id_versement`, `id_location`, `id_user`) VALUES
(74, 'entree', 'Caution locataire Omar THIOUNE', 'Caution', '2023-10-07', 200000, NULL, 68, 'Admin Admin'),
(75, 'entree', 'Caution locataire Mme seye salimata  TOURE', 'Caution', '2023-10-01', 200000, NULL, 69, 'Admin Admin'),
(76, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 200000, NULL, 70, 'Admin Admin'),
(77, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 200000, NULL, 71, 'Admin Admin'),
(78, 'entree', 'Caution locataire Mme fagueye  DIEYE', 'Caution', '2023-09-01', 250000, NULL, 72, 'Admin Admin'),
(79, 'entree', 'Caution locataire Mme marie parcine DIOP', 'Caution', '2023-09-01', 200000, NULL, 73, 'Admin Admin'),
(80, 'entree', 'Caution locataire Mme ndeye fama  TRAORE', 'Caution', '2023-09-01', 250000, NULL, 74, 'Admin Admin'),
(81, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 250000, NULL, 75, 'Admin Admin'),
(82, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 250000, NULL, 76, 'Admin Admin'),
(83, 'entree', 'Caution locataire Mme cimo morte  LORIEN', 'Caution', '2023-10-01', 250000, NULL, 77, 'Admin Admin');

-- --------------------------------------------------------

--
-- Structure de la table `caisse_depot`
--

CREATE TABLE `caisse_depot` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `date_operation` date NOT NULL,
  `montant` bigint(20) NOT NULL,
  `id_location` int(11) DEFAULT NULL,
  `id_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caisse_immo`
--

CREATE TABLE `caisse_immo` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `date_operation` date NOT NULL,
  `montant` bigint(20) NOT NULL,
  `id_mensualite` int(11) DEFAULT NULL,
  `id_location` int(11) DEFAULT NULL,
  `id_mensualite_bailleur` int(11) DEFAULT NULL,
  `id_depense_bailleur` int(11) DEFAULT NULL,
  `id_cotisation_locataire` int(11) DEFAULT NULL,
  `id_user` text DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `id_caisse_caution` int(11) DEFAULT NULL,
  `id_caisse_depot` int(11) DEFAULT NULL,
  `id_cotisation_depense` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `caisse_immo`
--

INSERT INTO `caisse_immo` (`id`, `type`, `motif`, `section`, `date_operation`, `montant`, `id_mensualite`, `id_location`, `id_mensualite_bailleur`, `id_depense_bailleur`, `id_cotisation_locataire`, `id_user`, `pj`, `id_caisse_caution`, `id_caisse_depot`, `id_cotisation_depense`) VALUES
(340, 'entree', 'Loyer Novembre de Omar THIOUNE', 'Reglement mensualite', '2023-11-02', 200000, 2, NULL, NULL, NULL, NULL, 'Admin Admin', NULL, NULL, NULL, NULL),
(343, 'entree', 'Commision locataire Mme seye salimata  TOURE', 'Commision', '2023-10-01', 200000, NULL, 69, NULL, NULL, NULL, 'Admin Admin', 69, NULL, NULL, NULL),
(344, 'entree', 'Caution locataire Mme seye salimata  TOURE', 'Caution', '2023-10-01', 200000, NULL, 69, NULL, NULL, NULL, 'Admin Admin', 69, NULL, NULL, NULL),
(345, 'entree', 'Avance premier mois loyer Mme seye salimata  TOURE', 'Reglement facture', '2023-10-01', 200000, NULL, 69, NULL, NULL, NULL, 'Admin Admin', 69, NULL, NULL, NULL),
(346, 'entree', 'Commision locataire  ', 'Commision', '2023-09-01', 200000, NULL, 70, NULL, NULL, NULL, 'Admin Admin', 70, NULL, NULL, NULL),
(347, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 200000, NULL, 70, NULL, NULL, NULL, 'Admin Admin', 70, NULL, NULL, NULL),
(348, 'entree', 'Avance premier mois loyer  ', 'Reglement facture', '2023-09-01', 200000, NULL, 70, NULL, NULL, NULL, 'Admin Admin', 70, NULL, NULL, NULL),
(349, 'entree', 'Commision locataire  ', 'Commision', '2023-09-01', 200000, NULL, 71, NULL, NULL, NULL, 'Admin Admin', 71, NULL, NULL, NULL),
(350, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 200000, NULL, 71, NULL, NULL, NULL, 'Admin Admin', 71, NULL, NULL, NULL),
(351, 'entree', 'Avance premier mois loyer  ', 'Reglement facture', '2023-09-01', 200000, NULL, 71, NULL, NULL, NULL, 'Admin Admin', 71, NULL, NULL, NULL),
(352, 'entree', 'Commision locataire Mme fagueye  DIEYE', 'Commision', '2023-09-01', 132000, NULL, 72, NULL, NULL, NULL, 'Admin Admin', 72, NULL, NULL, NULL),
(353, 'entree', 'Caution locataire Mme fagueye  DIEYE', 'Caution', '2023-09-01', 250000, NULL, 72, NULL, NULL, NULL, 'Admin Admin', 72, NULL, NULL, NULL),
(354, 'entree', 'Avance premier mois loyer Mme fagueye  DIEYE', 'Reglement facture', '2023-09-01', 250000, NULL, 72, NULL, NULL, NULL, 'Admin Admin', 72, NULL, NULL, NULL),
(355, 'entree', 'Commision locataire Mme marie parcine DIOP', 'Commision', '2023-09-01', 105600, NULL, 73, NULL, NULL, NULL, 'Admin Admin', 73, NULL, NULL, NULL),
(356, 'entree', 'Caution locataire Mme marie parcine DIOP', 'Caution', '2023-09-01', 200000, NULL, 73, NULL, NULL, NULL, 'Admin Admin', 73, NULL, NULL, NULL),
(357, 'entree', 'Avance premier mois loyer Mme marie parcine DIOP', 'Reglement facture', '2023-09-01', 200000, NULL, 73, NULL, NULL, NULL, 'Admin Admin', 73, NULL, NULL, NULL),
(358, 'entree', 'Commision locataire Mme ndeye fama  TRAORE', 'Commision', '2023-09-01', 250000, NULL, 74, NULL, NULL, NULL, 'Admin Admin', 74, NULL, NULL, NULL),
(359, 'entree', 'Caution locataire Mme ndeye fama  TRAORE', 'Caution', '2023-09-01', 250000, NULL, 74, NULL, NULL, NULL, 'Admin Admin', 74, NULL, NULL, NULL),
(360, 'entree', 'Avance premier mois loyer Mme ndeye fama  TRAORE', 'Reglement facture', '2023-09-01', 250000, NULL, 74, NULL, NULL, NULL, 'Admin Admin', 74, NULL, NULL, NULL),
(361, 'entree', 'Commision locataire  ', 'Commision', '2023-09-01', 250000, NULL, 75, NULL, NULL, NULL, 'Admin Admin', 75, NULL, NULL, NULL),
(362, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 250000, NULL, 75, NULL, NULL, NULL, 'Admin Admin', 75, NULL, NULL, NULL),
(363, 'entree', 'Avance premier mois loyer  ', 'Reglement facture', '2023-09-01', 250000, NULL, 75, NULL, NULL, NULL, 'Admin Admin', 75, NULL, NULL, NULL),
(364, 'entree', 'Commision locataire  ', 'Commision', '2023-09-01', 250000, NULL, 76, NULL, NULL, NULL, 'Admin Admin', 76, NULL, NULL, NULL),
(365, 'entree', 'Caution locataire  ', 'Caution', '2023-09-01', 250000, NULL, 76, NULL, NULL, NULL, 'Admin Admin', 76, NULL, NULL, NULL),
(366, 'entree', 'Avance premier mois loyer  ', 'Reglement facture', '2023-09-01', 250000, NULL, 76, NULL, NULL, NULL, 'Admin Admin', 76, NULL, NULL, NULL),
(367, 'entree', 'Loyer Octobre de Mme seye salimata  TOURE', 'Reglement mensualite', '2023-10-06', 211200, 11, NULL, NULL, NULL, NULL, 'Admin Admin', NULL, NULL, NULL, NULL),
(368, 'entree', 'Loyer Octobre de Mme marie parcine DIOP', 'Reglement mensualite', '2023-10-09', 211200, 12, NULL, NULL, NULL, NULL, 'Admin Admin', NULL, NULL, NULL, NULL),
(369, 'entree', 'Commision locataire Mme cimo morte  LORIEN', 'Commision', '2023-10-01', 250000, NULL, 77, NULL, NULL, NULL, 'Admin Admin', 77, NULL, NULL, NULL),
(370, 'entree', 'Caution locataire Mme cimo morte  LORIEN', 'Caution', '2023-10-01', 250000, NULL, 77, NULL, NULL, NULL, 'Admin Admin', 77, NULL, NULL, NULL),
(371, 'entree', 'Avance premier mois loyer Mme cimo morte  LORIEN', 'Reglement facture', '2023-10-01', 250000, NULL, 77, NULL, NULL, NULL, 'Admin Admin', 77, NULL, NULL, NULL);

--
-- Déclencheurs `caisse_immo`
--
DELIMITER $$
CREATE TRIGGER `delete_caisse_immo` BEFORE DELETE ON `caisse_immo` FOR EACH ROW INSERT INTO caisse_immo_s(id, type, motif, section, date_operation, montant, id_mensualite, id_location, 
	id_mensualite_bailleur, id_depense_bailleur, id_cotisation_locataire, id_user, pj, id_caisse_caution, id_caisse_depot, 
	type_op, date_op) VALUES (old.id, old.type, old.motif, old.section, old.date_operation, old.montant, old.id_mensualite, 
	old.id_location, 	 old.id_mensualite_bailleur, old.id_depense_bailleur, old.id_cotisation_locataire, old.id_user, old.pj, 
	old.id_caisse_caution, 	old.id_caisse_depot, "DELETE", CURRENT_TIMESTAMP)
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_caisse` AFTER UPDATE ON `caisse_immo` FOR EACH ROW INSERT INTO caisse_immo_s(id, type, motif, section, date_operation, montant, id_mensualite, id_location, 
	id_mensualite_bailleur, id_depense_bailleur, id_cotisation_locataire, id_user, pj, id_caisse_caution, id_caisse_depot, 
	type_op, date_op) VALUES (old.id, old.type, old.motif, old.section, old.date_operation, old.montant, old.id_mensualite, 
	old.id_location, 	 old.id_mensualite_bailleur, old.id_depense_bailleur, old.id_cotisation_locataire, old.id_user, old.pj, 
	old.id_caisse_caution, 	old.id_caisse_depot, "UPDATE", CURRENT_TIMESTAMP)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `caisse_immo_s`
--

CREATE TABLE `caisse_immo_s` (
  `id_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `motif` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `date_operation` date NOT NULL,
  `montant` bigint(20) NOT NULL,
  `id_mensualite` int(11) DEFAULT NULL,
  `id_location` int(11) DEFAULT NULL,
  `id_mensualite_bailleur` int(11) DEFAULT NULL,
  `id_depense_bailleur` int(11) DEFAULT NULL,
  `id_cotisation_locataire` int(11) DEFAULT NULL,
  `id_user` text DEFAULT NULL,
  `pj` int(11) DEFAULT NULL,
  `id_caisse_caution` int(11) DEFAULT NULL,
  `id_caisse_depot` int(11) DEFAULT NULL,
  `type_op` varchar(255) DEFAULT NULL,
  `date_op` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `caisse_immo_s`
--

INSERT INTO `caisse_immo_s` (`id_id`, `id`, `type`, `motif`, `section`, `date_operation`, `montant`, `id_mensualite`, `id_location`, `id_mensualite_bailleur`, `id_depense_bailleur`, `id_cotisation_locataire`, `id_user`, `pj`, `id_caisse_caution`, `id_caisse_depot`, `type_op`, `date_op`) VALUES
(468, 367, 'entree', 'Loyer Octobre de Mme seye salimata  TOURE', 'Reglement mensualite', '2023-10-06', 200000, 11, NULL, NULL, NULL, NULL, 'Admin Admin', NULL, NULL, NULL, 'UPDATE', '2023-10-09 15:24:29'),
(469, 367, 'entree', 'Loyer Octobre de Mme seye salimata  TOURE', 'Reglement mensualite', '2023-10-06', 211200, 11, NULL, NULL, NULL, NULL, 'Admin Admin', NULL, NULL, NULL, 'UPDATE', '2023-10-09 15:25:27'),
(470, 368, 'entree', 'Loyer Octobre de Mme marie parcine DIOP', 'Reglement mensualite', '2023-10-09', 211200, 12, NULL, NULL, NULL, NULL, 'Admin Admin', NULL, NULL, NULL, 'UPDATE', '2023-10-10 16:04:02'),
(471, 337, 'entree', 'Commision locataire Omar THIOUNE', 'Commision', '2023-10-07', 200000, NULL, 68, NULL, NULL, NULL, 'Admin Admin', 68, NULL, NULL, 'DELETE', '2023-10-10 16:19:29'),
(472, 339, 'entree', 'Avance premier mois loyer Omar THIOUNE', 'Reglement facture', '2023-10-07', 200000, NULL, 68, NULL, NULL, NULL, 'Admin Admin', 68, NULL, NULL, 'DELETE', '2023-10-10 16:19:47'),
(473, 338, 'entree', 'Caution locataire Omar THIOUNE', 'Caution', '2023-10-07', 200000, NULL, 68, NULL, NULL, NULL, 'Admin Admin', 68, NULL, NULL, 'DELETE', '2023-10-10 16:20:11'),
(474, 368, 'entree', 'Loyer Septembre de Mme marie parcine DIOP', 'Reglement mensualite', '2023-10-09', 211200, 12, NULL, NULL, NULL, NULL, 'Admin Admin', NULL, NULL, NULL, 'UPDATE', '2023-10-10 16:30:02');

-- --------------------------------------------------------

--
-- Structure de la table `compte_rendu`
--

CREATE TABLE `compte_rendu` (
  `id` int(11) NOT NULL,
  `compte_rendu` text DEFAULT NULL,
  `date_redaction` date NOT NULL,
  `id_personnel` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consommable`
--

CREATE TABLE `consommable` (
  `id` int(11) NOT NULL,
  `consommable` varchar(255) NOT NULL,
  `pu` int(11) NOT NULL,
  `qt` int(11) NOT NULL DEFAULT 0,
  `id_ravitaillement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consommable_utilisation`
--

CREATE TABLE `consommable_utilisation` (
  `id` int(11) NOT NULL,
  `date_operation` date NOT NULL,
  `demandeur` text NOT NULL,
  `commentaire` text DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `id_consommable` int(11) NOT NULL,
  `id_user` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `telephone` text DEFAULT NULL,
  `mail` text NOT NULL,
  `contact` text NOT NULL,
  `autres_infos` text NOT NULL,
  `structure` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

CREATE TABLE `contrat` (
  `id` int(11) NOT NULL,
  `type` text NOT NULL,
  `part1` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `part2` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cotisation_locataire`
--

CREATE TABLE `cotisation_locataire` (
  `id` int(11) NOT NULL,
  `motif` text NOT NULL,
  `type_depense` text NOT NULL,
  `date_depense` date NOT NULL,
  `mois` varchar(255) NOT NULL,
  `annee` int(11) NOT NULL,
  `id_locataire` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `id_user` text DEFAULT NULL,
  `id_cotisation_depense` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cotisation_locataire_depense`
--

CREATE TABLE `cotisation_locataire_depense` (
  `id` int(11) NOT NULL,
  `date_depense` date DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `montant_a_regler` int(11) DEFAULT NULL,
  `montant_regler` int(11) NOT NULL,
  `reliquat` int(11) NOT NULL,
  `id_bailleur` int(11) DEFAULT NULL,
  `id_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `courrier`
--

CREATE TABLE `courrier` (
  `id` int(11) NOT NULL,
  `numero` varchar(255) NOT NULL,
  `date_courrier` date NOT NULL,
  `type_courrier` varchar(255) NOT NULL,
  `intitule` text NOT NULL,
  `description` text DEFAULT NULL,
  `expediteur` text NOT NULL,
  `destinataire` text NOT NULL,
  `chemin` text NOT NULL,
  `id_user` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depense_bailleur`
--

CREATE TABLE `depense_bailleur` (
  `id` int(11) NOT NULL,
  `motif` text NOT NULL,
  `type_depense` text NOT NULL,
  `type_paiement` text NOT NULL,
  `date_depense` date NOT NULL,
  `mois` varchar(255) NOT NULL,
  `annee` int(11) NOT NULL,
  `id_bailleur` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  `id_mensualite_bailleur` int(11) DEFAULT 0,
  `id_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `locataire`
--

CREATE TABLE `locataire` (
  `id` int(11) NOT NULL,
  `prenom` text NOT NULL,
  `nom` text NOT NULL,
  `tel` text NOT NULL,
  `cni` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `num_dossier` int(11) NOT NULL,
  `annee_inscription` int(11) NOT NULL,
  `statut` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `locataire`
--

INSERT INTO `locataire` (`id`, `prenom`, `nom`, `tel`, `cni`, `email`, `num_dossier`, `annee_inscription`, `statut`) VALUES
(69, 'Mme seye salimata ', 'TOURE', '78 196 93 67', '2 07 19980205 00019 1', '', 2, 2023, 'actif'),
(72, 'Mme fagueye ', 'DIEYE', '77 158 93 20', 'PASSPORT.N° A02441208', '', 5, 2023, 'actif'),
(73, 'Mme marie parcine', 'DIOP', '77 278 85 62', '2 773 1997 00846', '', 6, 2023, 'actif'),
(74, 'Mme ndeye fama ', 'TRAORE', '76 925 76 12', '2 01 19880713 00041 7', '', 7, 2023, 'actif'),
(75, '', '', '', '', NULL, 8, 2023, 'actif'),
(76, '', '', '', '', NULL, 9, 2023, 'actif'),
(77, 'Mme cimo morte ', 'LORIEN', '0033636183515', 'PASS.N° YA5027857', '', 7, 2023, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `caution` bigint(20) NOT NULL,
  `commission` int(11) NOT NULL,
  `prix_location` bigint(20) NOT NULL,
  `id_logement` int(11) NOT NULL,
  `id_locataire` int(11) NOT NULL,
  `etat` varchar(255) NOT NULL,
  `type_contrat` text DEFAULT NULL,
  `id_user` text DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `location`
--

INSERT INTO `location` (`id`, `date_debut`, `caution`, `commission`, `prix_location`, `id_logement`, `id_locataire`, `etat`, `type_contrat`, `id_user`, `date_fin`) VALUES
(68, '2023-10-07', 200000, 200000, 200000, 69, 68, 'inactive', 'habitation', 'Admin Admin', '2023-10-06'),
(69, '2023-10-01', 200000, 200000, 200000, 65, 69, 'active', 'habitation', 'Admin Admin', NULL),
(70, '2023-09-01', 200000, 200000, 200000, 65, 70, 'inactive', 'habitation', 'Admin Admin', '2023-10-09'),
(71, '2023-09-01', 200000, 200000, 200000, 65, 71, 'inactive', 'habitation', 'Admin Admin', '2023-10-09'),
(72, '2023-09-01', 250000, 132000, 250000, 66, 72, 'active', 'habitation', 'Admin Admin', NULL),
(73, '2023-09-01', 200000, 105600, 200000, 70, 73, 'active', 'habitation', 'Admin Admin', NULL),
(74, '2023-09-01', 250000, 250000, 250000, 68, 74, 'active', 'habitation', 'Admin Admin', NULL),
(75, '2023-09-01', 250000, 250000, 250000, 68, 75, 'active', 'habitation', 'Admin Admin', NULL),
(76, '2023-09-01', 250000, 250000, 250000, 68, 76, 'active', 'habitation', 'Admin Admin', NULL),
(77, '2023-10-01', 250000, 250000, 250000, 74, 77, 'active', 'habitation', 'Admin Admin', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `logement`
--

CREATE TABLE `logement` (
  `id` int(11) NOT NULL,
  `designation` text NOT NULL,
  `adresse` text NOT NULL,
  `pu` int(11) NOT NULL,
  `nbr` int(11) NOT NULL,
  `nbr_occupe` int(11) NOT NULL,
  `date_location` date DEFAULT NULL,
  `id_type` int(11) NOT NULL,
  `id_bailleur` int(11) NOT NULL,
  `etat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `logement`
--

INSERT INTO `logement` (`id`, `designation`, `adresse`, `pu`, `nbr`, `nbr_occupe`, `date_location`, `id_type`, `id_bailleur`, `etat`) VALUES
(64, 'TEST', 'SALY', 2, 2, 0, NULL, 2, 0, 'actif'),
(65, '1 CHAMBRE . 1 SALON, 1 CUISINE ET 2 TOILETTES AU 1IER ETAGE ', 'YOFF APECSY 1', 200000, 0, 1, NULL, 12, 12, 'actif'),
(66, '2 CHAMBRES, 1 SALON, 1 CUISINE ET 2 TOILETTES AU 1IER ETAGE', 'YOFF APECSY 1', 250000, 0, 1, NULL, 8, 12, 'actif'),
(67, '1 CHAMBRE, 1 SALON, 1CUISINE ET 2 TOILETTES AU 2IEME ETAGE', 'YOFF APECSY 1', 200000, 1, 0, NULL, 12, 12, 'actif'),
(68, '2 CHAMBRES, 1 SALON, 1 CUISINE ET  2 TOILETTES AU 2IEME', 'YOFF APECSY 1', 250000, -2, 3, NULL, 8, 12, 'actif'),
(69, 'STUDIO AMERICAIN CHAMBRE   SALON ET SALLE DE BAIN', 'PATTE D\'OIE', 200000, 2, 0, NULL, 12, 13, 'actif'),
(70, '1 CHAMBRE, 1 SALON, 1 CUISINE ET 2 TOILETTES AU 3IEME ETAGE', 'YOFF APECSY 1', 200000, 0, 1, NULL, 12, 12, 'actif'),
(71, '2 CHAMBRES 1 SALON, 1 CUISINE ET E TOILETTES AU 3IEME ETATGE', 'YOFF APECSY 1', 250000, 1, 0, NULL, 8, 12, 'actif'),
(72, '1 CHAMBRE, 1 SALON, 1 CUISINE ET 1 TERRASSE AU 5 IEME ETAGE', 'YOFF APECSY 1', 250000, 2, 0, NULL, 12, 12, 'inactif'),
(73, '1 PLATEAU ET 2 SALLES D\'EAU AU RDC', 'YOFF APECSY 1', 300000, 2, 0, NULL, 3, 12, 'actif'),
(74, '1 CHAMBRE, 1 SALON, 1 CUISINE ET 1 TERRASSE AU 5IEME ETAGE', 'YOFF APECSY 1', 250000, 0, 1, NULL, 12, 12, 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `mensualite`
--

CREATE TABLE `mensualite` (
  `id` int(11) NOT NULL,
  `montant` int(11) DEFAULT 0,
  `date_versement` date DEFAULT NULL,
  `mois` varchar(255) NOT NULL,
  `annee` int(11) NOT NULL,
  `date_versement_bailleur` date DEFAULT NULL,
  `montant_bailleur` int(11) DEFAULT 0,
  `id_location` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `id_mensualite_bailleur` int(11) NOT NULL DEFAULT 0,
  `id_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `mensualite`
--

INSERT INTO `mensualite` (`id`, `montant`, `date_versement`, `mois`, `annee`, `date_versement_bailleur`, `montant_bailleur`, `id_location`, `type`, `id_mensualite_bailleur`, `id_user`) VALUES
(1, 200000, '2023-10-07', 'Octobre', 2023, NULL, 0, 68, 'complet', 0, 'Admin Admin'),
(2, 200000, '2023-11-02', 'Novembre', 2023, NULL, 0, 68, 'complet', 0, 'A.A'),
(4, 200000, '2023-09-01', 'Septembre', 2023, NULL, 0, 70, 'complet', 0, 'Admin Admin'),
(5, 200000, '2023-09-01', 'Septembre', 2023, NULL, 0, 71, 'complet', 0, 'Admin Admin'),
(6, 250000, '2023-09-01', 'Septembre', 2023, NULL, 0, 72, 'complet', 0, 'Admin Admin'),
(7, 200000, '2023-09-01', 'Septembre', 2023, NULL, 0, 73, 'complet', 0, 'Admin Admin'),
(8, 250000, '2023-09-01', 'Septembre', 2023, NULL, 0, 74, 'complet', 0, 'Admin Admin'),
(9, 250000, '2023-09-01', 'Septembre', 2023, NULL, 0, 75, 'complet', 0, 'Admin Admin'),
(10, 250000, '2023-09-01', 'Septembre', 2023, NULL, 0, 76, 'complet', 0, 'Admin Admin'),
(11, 211200, '2023-10-06', 'Octobre', 2023, NULL, 0, 69, 'complet', 0, 'A.A'),
(12, 211200, '2023-10-09', 'Octobre', 2023, NULL, 0, 73, 'complet', 0, 'A.A'),
(13, 250000, '2023-10-01', 'Octobre', 2023, NULL, 0, 77, 'complet', 0, 'Admin Admin');

-- --------------------------------------------------------

--
-- Structure de la table `mensualite_bailleur`
--

CREATE TABLE `mensualite_bailleur` (
  `id` int(11) NOT NULL,
  `montant` int(11) DEFAULT 0,
  `frais_reparation` int(11) DEFAULT NULL,
  `frais_judiciaire` int(11) DEFAULT NULL,
  `autres_frais` int(11) DEFAULT NULL,
  `date_versement` date DEFAULT NULL,
  `mois` varchar(255) NOT NULL,
  `annee` int(11) NOT NULL,
  `type_versement` text DEFAULT NULL,
  `non_recouvrer` text DEFAULT NULL,
  `commission` int(11) DEFAULT NULL,
  `id_bailleur` int(11) NOT NULL,
  `id_user` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `matricule` text DEFAULT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `sexe` text DEFAULT NULL,
  `fonction` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `date_embauche` date NOT NULL,
  `login` varchar(255) NOT NULL,
  `pwd` text NOT NULL,
  `service` varchar(255) NOT NULL,
  `etat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`id`, `matricule`, `prenom`, `nom`, `sexe`, `fonction`, `telephone`, `date_embauche`, `login`, `pwd`, `service`, `etat`) VALUES
(1, '55', 'Admin', 'Admin', 'Masculin', 'administrateur', 'a', '2019-08-07', 'a@dabakh', '3bbe29e8ef937d488ec97d95b0db61d8db5706da', 'service general', 'activer');

-- --------------------------------------------------------

--
-- Structure de la table `pj_bailleur`
--

CREATE TABLE `pj_bailleur` (
  `id` int(11) NOT NULL,
  `nom` text DEFAULT NULL,
  `chemin` longtext DEFAULT NULL,
  `id_bailleur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pj_caisse`
--

CREATE TABLE `pj_caisse` (
  `id` int(11) NOT NULL,
  `nom` text DEFAULT NULL,
  `chemin` longtext DEFAULT NULL,
  `id_caisse` int(11) DEFAULT NULL,
  `id_caisse_btp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pj_demandes`
--

CREATE TABLE `pj_demandes` (
  `id` int(11) NOT NULL,
  `nom` text DEFAULT NULL,
  `chemin` longtext DEFAULT NULL,
  `id_demande` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pj_demande_emploi`
--

CREATE TABLE `pj_demande_emploi` (
  `id` int(11) NOT NULL,
  `nom` text DEFAULT NULL,
  `type_demande` text DEFAULT NULL,
  `chemin` longtext DEFAULT NULL,
  `id_demande` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pj_locataire`
--

CREATE TABLE `pj_locataire` (
  `id` int(11) NOT NULL,
  `nom` text DEFAULT NULL,
  `chemin` longtext DEFAULT NULL,
  `id_locataire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pj_personnel`
--

CREATE TABLE `pj_personnel` (
  `id` int(11) NOT NULL,
  `nom` text DEFAULT NULL,
  `chemin` longtext DEFAULT NULL,
  `id_personnel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `planning_recouvrement`
--

CREATE TABLE `planning_recouvrement` (
  `id` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `date_creation` datetime NOT NULL,
  `compte_rendu` longtext DEFAULT NULL,
  `id_user` varchar(255) NOT NULL,
  `id_user_compte_rendu` text DEFAULT NULL,
  `id_agent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `planning_recouv_locataire`
--

CREATE TABLE `planning_recouv_locataire` (
  `id` int(11) NOT NULL,
  `compte_rendu` longtext DEFAULT NULL,
  `id_user` text DEFAULT NULL,
  `id_locataire` int(11) NOT NULL,
  `id_planning` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rdv`
--

CREATE TABLE `rdv` (
  `id_rdv` int(11) NOT NULL,
  `date_rdv` date NOT NULL,
  `heure_rdv` time DEFAULT NULL,
  `date_prescription` date NOT NULL,
  `id_patient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `type_logement`
--

CREATE TABLE `type_logement` (
  `id` int(11) NOT NULL,
  `type_logement` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `type_logement`
--

INSERT INTO `type_logement` (`id`, `type_logement`) VALUES
(1, 'TERRAIN'),
(2, 'VILLA'),
(3, 'MAGASIN'),
(4, 'DUPLEX'),
(5, 'BOUTIQUE'),
(8, 'APPARTEMENT'),
(12, 'STUDIO'),
(14, 'APPARTEMENT MEUBLE'),
(15, 'PLACES');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bailleur`
--
ALTER TABLE `bailleur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ref` (`num_dossier`),
  ADD KEY `annee_inscription` (`annee_inscription`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `banque`
--
ALTER TABLE `banque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `structure` (`structure`),
  ADD KEY `id_mensualite_bailleur` (`id_mensualite_bailleur`,`id_depense_bailleur`),
  ADD KEY `depense_bailleur_banque` (`id_depense_bailleur`);

--
-- Index pour la table `caisse_btp`
--
ALTER TABLE `caisse_btp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`);

--
-- Index pour la table `caisse_caution`
--
ALTER TABLE `caisse_caution`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `id_location` (`id_location`),
  ADD KEY `id_versement` (`id_versement`);

--
-- Index pour la table `caisse_depot`
--
ALTER TABLE `caisse_depot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `id_location` (`id_location`);

--
-- Index pour la table `caisse_immo`
--
ALTER TABLE `caisse_immo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `mensualite_consultation` (`id_mensualite`),
  ADD KEY `id_location` (`id_location`),
  ADD KEY `mensualite_bailleur_caisse` (`id_mensualite_bailleur`),
  ADD KEY `depense_bailleur_caisse` (`id_depense_bailleur`),
  ADD KEY `id_depense_locataire` (`id_cotisation_locataire`),
  ADD KEY `id_caisse_caution` (`id_caisse_caution`),
  ADD KEY `id_caisse_depot` (`id_caisse_depot`),
  ADD KEY `id_cotisation_depense` (`id_cotisation_depense`);

--
-- Index pour la table `caisse_immo_s`
--
ALTER TABLE `caisse_immo_s`
  ADD PRIMARY KEY (`id_id`),
  ADD KEY `type` (`type`),
  ADD KEY `mensualite_consultation` (`id_mensualite`),
  ADD KEY `id_location` (`id_location`),
  ADD KEY `mensualite_bailleur_caisse` (`id_mensualite_bailleur`),
  ADD KEY `depense_bailleur_caisse` (`id_depense_bailleur`),
  ADD KEY `id_depense_locataire` (`id_cotisation_locataire`),
  ADD KEY `id_caisse_caution` (`id_caisse_caution`),
  ADD KEY `id_caisse_depot` (`id_caisse_depot`),
  ADD KEY `type_op` (`type_op`,`date_op`);

--
-- Index pour la table `compte_rendu`
--
ALTER TABLE `compte_rendu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_personnel` (`id_personnel`);

--
-- Index pour la table `consommable`
--
ALTER TABLE `consommable`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `consommable_utilisation`
--
ALTER TABLE `consommable_utilisation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_consommable` (`id_consommable`);

--
-- Index pour la table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `contrat`
--
ALTER TABLE `contrat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cotisation_locataire`
--
ALTER TABLE `cotisation_locataire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bailleur` (`id_locataire`),
  ADD KEY `id_cotisation_depense` (`id_cotisation_depense`);

--
-- Index pour la table `cotisation_locataire_depense`
--
ALTER TABLE `cotisation_locataire_depense`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `courrier`
--
ALTER TABLE `courrier`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `depense_bailleur`
--
ALTER TABLE `depense_bailleur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bailleur` (`id_bailleur`),
  ADD KEY `id_mensualite_bailleur` (`id_mensualite_bailleur`);

--
-- Index pour la table `locataire`
--
ALTER TABLE `locataire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_logement` (`id_logement`,`id_locataire`),
  ADD KEY `locataire_location` (`id_locataire`);

--
-- Index pour la table `logement`
--
ALTER TABLE `logement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type` (`id_type`),
  ADD KEY `id_bayeur` (`id_bailleur`);

--
-- Index pour la table `mensualite`
--
ALTER TABLE `mensualite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_mensualite` (`id_location`),
  ADD KEY `id_mensualite_bailleur` (`id_mensualite_bailleur`);

--
-- Index pour la table `mensualite_bailleur`
--
ALTER TABLE `mensualite_bailleur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_mensualite` (`id_bailleur`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pj_bailleur`
--
ALTER TABLE `pj_bailleur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_demande` (`id_bailleur`);

--
-- Index pour la table `pj_caisse`
--
ALTER TABLE `pj_caisse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_demande` (`id_caisse`),
  ADD KEY `id_caisse_btp` (`id_caisse_btp`);

--
-- Index pour la table `pj_demandes`
--
ALTER TABLE `pj_demandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_demande` (`id_demande`);

--
-- Index pour la table `pj_demande_emploi`
--
ALTER TABLE `pj_demande_emploi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_demande` (`id_demande`);

--
-- Index pour la table `pj_locataire`
--
ALTER TABLE `pj_locataire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_demande` (`id_locataire`);

--
-- Index pour la table `pj_personnel`
--
ALTER TABLE `pj_personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_demande` (`id_personnel`);

--
-- Index pour la table `planning_recouvrement`
--
ALTER TABLE `planning_recouvrement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_agent` (`id_agent`);

--
-- Index pour la table `planning_recouv_locataire`
--
ALTER TABLE `planning_recouv_locataire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_locataire` (`id_locataire`,`id_planning`),
  ADD KEY `planning_recouv` (`id_planning`);

--
-- Index pour la table `rdv`
--
ALTER TABLE `rdv`
  ADD PRIMARY KEY (`id_rdv`),
  ADD KEY `id_patient` (`id_patient`);

--
-- Index pour la table `type_logement`
--
ALTER TABLE `type_logement`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bailleur`
--
ALTER TABLE `bailleur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `banque`
--
ALTER TABLE `banque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `caisse_btp`
--
ALTER TABLE `caisse_btp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `caisse_caution`
--
ALTER TABLE `caisse_caution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT pour la table `caisse_depot`
--
ALTER TABLE `caisse_depot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `caisse_immo`
--
ALTER TABLE `caisse_immo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=372;

--
-- AUTO_INCREMENT pour la table `caisse_immo_s`
--
ALTER TABLE `caisse_immo_s`
  MODIFY `id_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=475;

--
-- AUTO_INCREMENT pour la table `compte_rendu`
--
ALTER TABLE `compte_rendu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consommable`
--
ALTER TABLE `consommable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `consommable_utilisation`
--
ALTER TABLE `consommable_utilisation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `contrat`
--
ALTER TABLE `contrat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cotisation_locataire`
--
ALTER TABLE `cotisation_locataire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cotisation_locataire_depense`
--
ALTER TABLE `cotisation_locataire_depense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `courrier`
--
ALTER TABLE `courrier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `depense_bailleur`
--
ALTER TABLE `depense_bailleur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `locataire`
--
ALTER TABLE `locataire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `logement`
--
ALTER TABLE `logement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `mensualite`
--
ALTER TABLE `mensualite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT pour la table `mensualite_bailleur`
--
ALTER TABLE `mensualite_bailleur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `pj_bailleur`
--
ALTER TABLE `pj_bailleur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `pj_caisse`
--
ALTER TABLE `pj_caisse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pj_demandes`
--
ALTER TABLE `pj_demandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pj_demande_emploi`
--
ALTER TABLE `pj_demande_emploi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pj_locataire`
--
ALTER TABLE `pj_locataire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `pj_personnel`
--
ALTER TABLE `pj_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `planning_recouvrement`
--
ALTER TABLE `planning_recouvrement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `planning_recouv_locataire`
--
ALTER TABLE `planning_recouv_locataire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `rdv`
--
ALTER TABLE `rdv`
  MODIFY `id_rdv` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `type_logement`
--
ALTER TABLE `type_logement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `caisse_caution`
--
ALTER TABLE `caisse_caution`
  ADD CONSTRAINT `cautionècaution` FOREIGN KEY (`id_versement`) REFERENCES `caisse_caution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `location_caution` FOREIGN KEY (`id_location`) REFERENCES `location` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `caisse_immo`
--
ALTER TABLE `caisse_immo`
  ADD CONSTRAINT `caisse_caution_caisse_immo` FOREIGN KEY (`id_caisse_caution`) REFERENCES `caisse_caution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `caisse_depot_immo` FOREIGN KEY (`id_caisse_depot`) REFERENCES `caisse_depot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cotisation_depense_caisse` FOREIGN KEY (`id_cotisation_depense`) REFERENCES `cotisation_locataire_depense` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `depense_bailleur_caisse` FOREIGN KEY (`id_depense_bailleur`) REFERENCES `depense_bailleur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `depense_locataire_caisse_immo` FOREIGN KEY (`id_cotisation_locataire`) REFERENCES `cotisation_locataire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensualite_bailleur_caisse` FOREIGN KEY (`id_mensualite_bailleur`) REFERENCES `mensualite_bailleur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mensualite_caisse` FOREIGN KEY (`id_mensualite`) REFERENCES `mensualite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cotisation_locataire`
--
ALTER TABLE `cotisation_locataire`
  ADD CONSTRAINT `locataire` FOREIGN KEY (`id_locataire`) REFERENCES `locataire` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pj_bailleur`
--
ALTER TABLE `pj_bailleur`
  ADD CONSTRAINT `bailleur_pj` FOREIGN KEY (`id_bailleur`) REFERENCES `bailleur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pj_caisse`
--
ALTER TABLE `pj_caisse`
  ADD CONSTRAINT `caisse_pj` FOREIGN KEY (`id_caisse`) REFERENCES `caisse_immo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `caisse_pj_btp` FOREIGN KEY (`id_caisse_btp`) REFERENCES `caisse_btp` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
