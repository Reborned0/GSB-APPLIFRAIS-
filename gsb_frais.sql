-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 13 Juin 2017 à 21:41
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gsb_frais`
--

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `idEmploye` int(11) NOT NULL,
  `idTypeEmploye` char(3) NOT NULL,
  `idLocalisation` int(11) NOT NULL,
  `idTypeVehicule` int(11) DEFAULT NULL,
  `nom` char(50) DEFAULT NULL,
  `prenom` char(50) DEFAULT NULL,
  `adresse` char(50) DEFAULT NULL,
  `login` char(20) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `dateEmbauche` date DEFAULT NULL,
  `dateModifFicheE` date DEFAULT NULL,
  `cptActif` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `employe`
--

INSERT INTO `employe` (`idEmploye`, `idTypeEmploye`, `idLocalisation`, `idTypeVehicule`, `nom`, `prenom`, `adresse`, `login`, `mdp`, `dateEmbauche`, `dateModifFicheE`, `cptActif`) VALUES
(1, 'ADM', 2, 6, 'Chea', 'Souvannarith', '15 rue du Bouccicaut', 'scheaADM', '6c54609c9e13bdc86d80acb2678f0d9ee7126b38', '2005-08-16', '2017-04-03', 1),
(2, 'COM', 15, NULL, 'Ibrahimovic', 'Zlatan', '96 avenue De la Vanité', 'zibraCOM', '6c54609c9e13bdc86d80acb2678f0d9ee7126b38', '2012-07-01', '2017-04-03', 1),
(3, 'VIS', 10, 2, 'Bond', 'James', '5 allée 007', 'jbondVIS', '6c54609c9e13bdc86d80acb2678f0d9ee7126b38', '2016-01-02', NULL, 1),
(14, 'ADM', 20, NULL, 'Compte Test', 'ADM', 'test test', 'testADM', '4028a0e356acc947fcd2bfbf00cef11e128d484a', '2016-09-01', '2017-02-01', 1),
(15, 'COM', 30, NULL, 'Compte Test', 'COM', 'test test', 'testCOM', '4028a0e356acc947fcd2bfbf00cef11e128d484a', '2015-02-01', '2017-02-01', 1),
(16, 'VIS', 48, 3, 'Compte Test', 'VIS', 'test test', 'testVIS', '4028a0e356acc947fcd2bfbf00cef11e128d484a', '2016-09-01', '2017-02-01', 1);

-- --------------------------------------------------------

--
-- Structure de la table `etatfiche`
--

CREATE TABLE `etatfiche` (
  `idEtatFiche` char(2) NOT NULL,
  `libelleEtatFiche` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `etatfiche`
--

INSERT INTO `etatfiche` (`idEtatFiche`, `libelleEtatFiche`) VALUES
('CL', 'Fiche clôturée'),
('CR', 'Fiche créée, saisie en cours'),
('RB', 'Remboursé'),
('VA', 'Validé et en attente de mise en paiement');

-- --------------------------------------------------------

--
-- Structure de la table `fichefrais`
--

CREATE TABLE `fichefrais` (
  `emp_idEmploye` int(11) NOT NULL,
  `periodeConcernee` char(6) NOT NULL,
  `idEtatFiche` char(2) NOT NULL,
  `idEmploye` int(11) DEFAULT NULL,
  `montantValide` decimal(10,2) DEFAULT NULL,
  `dateModifFicheF` date DEFAULT NULL,
  `nbrJustificatif` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `fichefrais`
--

INSERT INTO `fichefrais` (`emp_idEmploye`, `periodeConcernee`, `idEtatFiche`, `idEmploye`, `montantValide`, `dateModifFicheF`, `nbrJustificatif`) VALUES
(3, '201601', 'RB', 15, '1610.90', '2016-02-22', 1),
(3, '201602', 'RB', 2, '1970.24', '2016-03-23', 0),
(3, '201603', 'RB', 2, '3780.64', '2016-04-20', 0),
(3, '201604', 'RB', 2, '2736.32', '2016-05-23', 1),
(3, '201605', 'CL', NULL, '0.00', '2017-04-10', 0),
(16, '201601', 'RB', 2, '2545.50', '2016-02-22', 1),
(16, '201602', 'RB', 2, '3288.90', '2016-03-23', 1),
(16, '201603', 'RB', 15, '2989.92', '2016-04-20', 1),
(16, '201604', 'RB', 15, '785.66', '2016-05-23', 1),
(16, '201605', 'CL', NULL, '0.00', '2017-04-10', 4),
(16, '201704', 'CL', NULL, '0.00', '2017-05-17', 0),
(16, '201705', 'CL', NULL, '0.00', '2017-06-12', 0),
(16, '201706', 'CR', NULL, '0.00', '2017-06-12', 0);

-- --------------------------------------------------------

--
-- Structure de la table `fraisforfaitise`
--

CREATE TABLE `fraisforfaitise` (
  `idFraisForfaitise` char(3) NOT NULL,
  `libelleFF` char(50) DEFAULT NULL,
  `montantFF` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `fraisforfaitise`
--

INSERT INTO `fraisforfaitise` (`idFraisForfaitise`, `libelleFF`, `montantFF`) VALUES
('ETP', 'Forfait étape', '110.00'),
('KM1', 'Frais Km Diesel 4CV', '0.52'),
('KM2', 'Frais Km Diesel 5/6CV', '0.58'),
('KM3', 'Frais Km Essence 4CV', '0.62'),
('KM4', 'Frais Km Essence 5/6CV', '0.67'),
('NUI', 'Nuitée hôtel', '80.00'),
('REP', 'Repas restaurant', '29.00');

-- --------------------------------------------------------

--
-- Structure de la table `justificatiflignefhf`
--

CREATE TABLE `justificatiflignefhf` (
  `idJustificatif` int(11) NOT NULL,
  `idLigneFHF` int(11) NOT NULL,
  `lienJustificatif` char(255) DEFAULT NULL,
  `dateUpload` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `justificatiflignefhf`
--

INSERT INTO `justificatiflignefhf` (`idJustificatif`, `idLigneFHF`, `lienJustificatif`, `dateUpload`) VALUES
(9, 10, './files/201601_idFHF10_idVisiteur3_1451736543.jpg', '2016-01-02'),
(10, 11, './files/201601_idFHF11_idVisiteur16_1451736676.jpg', '2016-01-02'),
(11, 12, './files/201602_idFHF12_idVisiteur16_1454588039.jpg', '2016-02-04'),
(12, 15, './files/201603_idFHF15_idVisiteur16_1458736566.jpg', '2016-03-23'),
(13, 17, './files/201604_idFHF17_idVisiteur16_1461152701.jpg', '2016-04-20'),
(14, 16, './files/201604_idFHF16_idVisiteur3_1461152753.jpg', '2016-04-20'),
(15, 18, './files/201605_idFHF18_idVisiteur16_1462448939.jpg', '2016-05-05'),
(16, 19, './files/201605_idFHF19_idVisiteur16_1462448971.jpg', '2016-05-05'),
(17, 19, './files/201605_idFHF19_idVisiteur16_1462448979.jpg', '2016-05-05'),
(18, 20, './files/201605_idFHF20_idVisiteur16_1462449016.jpg', '2016-05-05');

-- --------------------------------------------------------

--
-- Structure de la table `lignefraisforfaitise`
--

CREATE TABLE `lignefraisforfaitise` (
  `emp_idEmploye` int(11) NOT NULL,
  `periodeConcernee` char(6) NOT NULL,
  `idFraisForfaitise` char(3) NOT NULL,
  `qteLigneFF` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `lignefraisforfaitise`
--

INSERT INTO `lignefraisforfaitise` (`emp_idEmploye`, `periodeConcernee`, `idFraisForfaitise`, `qteLigneFF`) VALUES
(3, '201601', 'ETP', 2),
(3, '201601', 'KM2', 105),
(3, '201601', 'NUI', 5),
(3, '201601', 'REP', 20),
(3, '201602', 'ETP', 0),
(3, '201602', 'KM2', 228),
(3, '201602', 'NUI', 15),
(3, '201602', 'REP', 22),
(3, '201603', 'ETP', 12),
(3, '201603', 'KM2', 108),
(3, '201603', 'NUI', 22),
(3, '201603', 'REP', 22),
(3, '201604', 'ETP', 18),
(3, '201604', 'KM2', 354),
(3, '201604', 'NUI', 0),
(3, '201604', 'REP', 19),
(3, '201605', 'ETP', 5),
(3, '201605', 'KM2', 48),
(3, '201605', 'NUI', 2),
(3, '201605', 'REP', 10),
(16, '201601', 'ETP', 15),
(16, '201601', 'KM3', 165),
(16, '201601', 'NUI', 2),
(16, '201601', 'REP', 20),
(16, '201602', 'ETP', 8),
(16, '201602', 'KM3', 95),
(16, '201602', 'NUI', 20),
(16, '201602', 'REP', 20),
(16, '201603', 'ETP', 13),
(16, '201603', 'KM3', 266),
(16, '201603', 'NUI', 12),
(16, '201603', 'REP', 15),
(16, '201604', 'ETP', 0),
(16, '201604', 'KM3', 155),
(16, '201604', 'NUI', 0),
(16, '201604', 'REP', 23),
(16, '201605', 'ETP', 13),
(16, '201605', 'KM3', 126),
(16, '201605', 'NUI', 0),
(16, '201605', 'REP', 17),
(16, '201704', 'ETP', 0),
(16, '201704', 'KM3', 0),
(16, '201704', 'NUI', 0),
(16, '201704', 'REP', 0),
(16, '201705', 'ETP', 0),
(16, '201705', 'KM3', 0),
(16, '201705', 'NUI', 0),
(16, '201705', 'REP', 0),
(16, '201706', 'ETP', 0),
(16, '201706', 'KM3', 0),
(16, '201706', 'NUI', 0),
(16, '201706', 'REP', 0);

-- --------------------------------------------------------

--
-- Structure de la table `lignefraishorsforfait`
--

CREATE TABLE `lignefraishorsforfait` (
  `idLigneFHF` int(11) NOT NULL,
  `emp_idEmploye` int(11) NOT NULL,
  `periodeConcernee` char(6) NOT NULL,
  `libelleLigneFHF` char(255) DEFAULT NULL,
  `montantLigneFHF` decimal(10,2) DEFAULT NULL,
  `dateLigneFHF` date DEFAULT NULL,
  `statutLigneFHF_refuse` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `lignefraishorsforfait`
--

INSERT INTO `lignefraishorsforfait` (`idLigneFHF`, `emp_idEmploye`, `periodeConcernee`, `libelleLigneFHF`, `montantLigneFHF`, `dateLigneFHF`, `statutLigneFHF_refuse`) VALUES
(10, 3, '201601', 'Cadeau fin année client DURASTEL', '350.00', '2015-12-12', NULL),
(11, 16, '201601', 'Taxi depuis aéroport', '53.20', '2015-11-28', NULL),
(12, 16, '201602', 'Réservation salle conférence', '170.00', '2016-01-02', NULL),
(13, 16, '201602', 'REFUSE - Divers', '45.00', '2016-01-15', 1),
(14, 16, '201605', 'Réservation salle conférence', '126.00', '2016-03-12', NULL),
(15, 16, '201603', 'REFUSE - Invitation restaurant client', '535.00', '2016-03-15', 1),
(16, 3, '201604', 'REFUSE - Divers', '80.00', '2016-03-03', 1),
(17, 16, '201604', 'Taxi', '22.56', '2016-04-05', NULL),
(18, 16, '201605', 'Réservation stand salon industrie pharmaceutique', '856.00', '2016-05-12', NULL),
(19, 16, '201605', 'Traiteur salon industrie pharmaceutique', '1228.00', '2016-05-15', NULL),
(20, 16, '201605', 'Impression carte visite et prospectus', '87.00', '2016-05-18', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `localisation`
--

CREATE TABLE `localisation` (
  `idLocalisation` int(11) NOT NULL,
  `codePostal` char(5) DEFAULT NULL,
  `libelleVille` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `localisation`
--

INSERT INTO `localisation` (`idLocalisation`, `codePostal`, `libelleVille`) VALUES
(1, '69001', 'Lyon'),
(2, '75001', 'Paris'),
(3, '69300', 'Caluire et Cuire'),
(4, '69500', 'Bron'),
(5, '69150', 'Décines'),
(6, '69100', 'Villeurbanne'),
(7, '77000', 'Serris'),
(8, '78000', 'Poissy'),
(9, '91000', 'Evry'),
(10, '92000', 'Saint-Cloud'),
(11, '93000', 'Pantin'),
(12, '94000', 'Creteil'),
(13, '95000', 'Cergy'),
(14, '01000', 'Bourg-en-Bresse'),
(15, '02000', 'Laon'),
(16, '03000', 'Moulins'),
(17, '04000', 'Digne-les-Bains'),
(18, '05000', 'Gap'),
(19, '06000', 'Nice'),
(20, '07000', 'Saint-Priest'),
(21, '08000', 'Charleville Mezieres'),
(22, '09000', 'Foix'),
(23, '10000', 'Troyes'),
(24, '11000', 'Carcasonne'),
(25, '12000', 'Rodez'),
(26, '13000', 'Marseille'),
(27, '14000', 'Caen'),
(28, '15000', 'Aurillac'),
(29, '16000', 'Angouleme'),
(30, '17000', 'La Rochelle'),
(31, '18000', 'Bourges'),
(32, '19000', 'Tulle'),
(33, '20000', 'Ajaccio'),
(34, '21000', 'Dijon'),
(35, '22000', 'Saint Brieuc'),
(36, '23000', 'Peyrabout'),
(37, '24000', 'Perigueux'),
(38, '25000', 'Besancon'),
(39, '26000', 'Valence'),
(40, '27000', 'Evreux'),
(41, '28000', 'Chartres'),
(42, '29000', 'Quimper'),
(43, '30000', 'Nimes'),
(44, '31000', 'Toulouse'),
(45, '32000', 'Auch'),
(46, '33000', 'Bordeaux'),
(47, '34000', 'Montpellier'),
(48, '35000', 'Rennes'),
(49, '36000', 'Chateauroux'),
(50, '37000', 'Tours'),
(51, '38000', 'Grenoble'),
(52, '39000', 'Lons-le-Saunier'),
(53, '40000', 'Mont-de-Marsan'),
(54, '41000', 'Blois'),
(55, '42000', 'Saint-Etienne'),
(56, '43000', 'Le Puy-en-Velay'),
(57, '44000', 'Nantes'),
(58, '45000', 'Orleans'),
(59, '46000', 'Cahors'),
(60, '47000', 'Agen'),
(61, '48000', 'Servieres'),
(62, '49000', 'Angers'),
(63, '50000', 'Saint-Lo'),
(64, '51000', 'Calons-en-Champagne'),
(65, '52000', 'Villiers-le-Sec'),
(66, '53000', 'Laval'),
(67, '54000', 'Nancy'),
(68, '55000', 'Gery'),
(69, '56000', 'Vannes'),
(70, '57000', 'Metz'),
(71, '58000', 'Nevers'),
(72, '59000', 'Lille'),
(73, '60000', 'Beauvais'),
(74, '61000', 'Cerise'),
(75, '62000', 'Arras'),
(76, '63000', 'Clermont-Ferrand'),
(77, '64000', 'Pau'),
(78, '65000', 'Tarbes'),
(79, '66000', 'Perpignan'),
(80, '67000', 'Strasbourg'),
(81, '68000', 'Colmar'),
(82, '69000', 'Cuny'),
(83, '70000', 'Raze'),
(84, '71000', 'Macon'),
(85, '72000', 'Le mans'),
(86, '73000', 'Chambery'),
(87, '74000', 'Annecy'),
(88, '76000', 'Rouen'),
(89, '79000', 'Niort'),
(90, '80000', 'Amiens'),
(91, '81000', 'Albi'),
(92, '82000', 'Montauban'),
(93, '83000', 'Toulon'),
(94, '84000', 'Avignon'),
(95, '85000', 'La Roche-sur-Yon'),
(96, '86000', 'Poitiers'),
(97, '87000', 'Limoges'),
(98, '88000', 'Longchamp'),
(99, '89000', 'Auxerre'),
(100, '90000', 'Belfort'),
(101, '98000', 'Monaco');

-- --------------------------------------------------------

--
-- Structure de la table `typeemploye`
--

CREATE TABLE `typeemploye` (
  `idTypeEmploye` char(3) NOT NULL,
  `libelleTypeEmploye` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `typeemploye`
--

INSERT INTO `typeemploye` (`idTypeEmploye`, `libelleTypeEmploye`) VALUES
('ADM', 'Administrateur'),
('COM', 'Comptable'),
('VIS', 'Visiteur');

-- --------------------------------------------------------

--
-- Structure de la table `typevehicule`
--

CREATE TABLE `typevehicule` (
  `idTypeVehicule` int(11) NOT NULL,
  `libelleTypeVehicule` char(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `typevehicule`
--

INSERT INTO `typevehicule` (`idTypeVehicule`, `libelleTypeVehicule`) VALUES
(1, 'Diesel 4CV'),
(2, 'Diesel 5/6CV'),
(3, 'Essence 4CV'),
(4, 'Essence 5/6CV'),
(5, 'Diesel 7/8CV'),
(6, 'Diesel 9/10CV'),
(7, 'Essence 7/8CV'),
(8, 'Essence 9/10CV');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`idEmploye`),
  ADD KEY `fk_employe_typeEmploye` (`idTypeEmploye`),
  ADD KEY `fk_employe_fraisForfaitiseKm` (`idTypeVehicule`),
  ADD KEY `fk_employe_localisation` (`idLocalisation`);

--
-- Index pour la table `etatfiche`
--
ALTER TABLE `etatfiche`
  ADD PRIMARY KEY (`idEtatFiche`);

--
-- Index pour la table `fichefrais`
--
ALTER TABLE `fichefrais`
  ADD PRIMARY KEY (`emp_idEmploye`,`periodeConcernee`),
  ADD KEY `fk_ficheFrais_etatFiche` (`idEtatFiche`),
  ADD KEY `fk_employeComptable_ficheFrais` (`idEmploye`);

--
-- Index pour la table `fraisforfaitise`
--
ALTER TABLE `fraisforfaitise`
  ADD PRIMARY KEY (`idFraisForfaitise`);

--
-- Index pour la table `justificatiflignefhf`
--
ALTER TABLE `justificatiflignefhf`
  ADD PRIMARY KEY (`idJustificatif`),
  ADD KEY `fk_ligneFHF_justificatifLigneFHF` (`idLigneFHF`);

--
-- Index pour la table `lignefraisforfaitise`
--
ALTER TABLE `lignefraisforfaitise`
  ADD PRIMARY KEY (`emp_idEmploye`,`periodeConcernee`,`idFraisForfaitise`),
  ADD KEY `fk_ligneFraisForfaitise` (`idFraisForfaitise`);

--
-- Index pour la table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  ADD PRIMARY KEY (`idLigneFHF`),
  ADD KEY `fk_ficheFrais_ligneFHF` (`emp_idEmploye`,`periodeConcernee`);

--
-- Index pour la table `localisation`
--
ALTER TABLE `localisation`
  ADD PRIMARY KEY (`idLocalisation`);

--
-- Index pour la table `typeemploye`
--
ALTER TABLE `typeemploye`
  ADD PRIMARY KEY (`idTypeEmploye`);

--
-- Index pour la table `typevehicule`
--
ALTER TABLE `typevehicule`
  ADD PRIMARY KEY (`idTypeVehicule`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `employe`
--
ALTER TABLE `employe`
  MODIFY `idEmploye` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT pour la table `justificatiflignefhf`
--
ALTER TABLE `justificatiflignefhf`
  MODIFY `idJustificatif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  MODIFY `idLigneFHF` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT pour la table `localisation`
--
ALTER TABLE `localisation`
  MODIFY `idLocalisation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT pour la table `typevehicule`
--
ALTER TABLE `typevehicule`
  MODIFY `idTypeVehicule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `fk_employe_localisation` FOREIGN KEY (`idLocalisation`) REFERENCES `localisation` (`idLocalisation`),
  ADD CONSTRAINT `fk_employe_typeEmploye` FOREIGN KEY (`idTypeEmploye`) REFERENCES `typeemploye` (`idTypeEmploye`),
  ADD CONSTRAINT `fk_employe_typeVehicule` FOREIGN KEY (`idTypeVehicule`) REFERENCES `typevehicule` (`idTypeVehicule`);

--
-- Contraintes pour la table `fichefrais`
--
ALTER TABLE `fichefrais`
  ADD CONSTRAINT `fk_employeComptable_ficheFrais` FOREIGN KEY (`idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `fk_employeVisiteur_ficheFrais` FOREIGN KEY (`emp_idEmploye`) REFERENCES `employe` (`idEmploye`),
  ADD CONSTRAINT `fk_ficheFrais_etatFiche` FOREIGN KEY (`idEtatFiche`) REFERENCES `etatfiche` (`idEtatFiche`);

--
-- Contraintes pour la table `justificatiflignefhf`
--
ALTER TABLE `justificatiflignefhf`
  ADD CONSTRAINT `fk_ligneFHF_justificatifLigneFHF` FOREIGN KEY (`idLigneFHF`) REFERENCES `lignefraishorsforfait` (`idLigneFHF`);

--
-- Contraintes pour la table `lignefraisforfaitise`
--
ALTER TABLE `lignefraisforfaitise`
  ADD CONSTRAINT `fk_ligneFraisForfaitise` FOREIGN KEY (`idFraisForfaitise`) REFERENCES `fraisforfaitise` (`idFraisForfaitise`),
  ADD CONSTRAINT `fk_ligneFraisForfaitise2` FOREIGN KEY (`emp_idEmploye`,`periodeConcernee`) REFERENCES `fichefrais` (`emp_idEmploye`, `periodeConcernee`);

--
-- Contraintes pour la table `lignefraishorsforfait`
--
ALTER TABLE `lignefraishorsforfait`
  ADD CONSTRAINT `fk_ficheFrais_ligneFHF` FOREIGN KEY (`emp_idEmploye`,`periodeConcernee`) REFERENCES `fichefrais` (`emp_idEmploye`, `periodeConcernee`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
