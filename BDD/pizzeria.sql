-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 17 mai 2020 à 20:00
-- Version du serveur :  10.4.11-MariaDB
-- Version de PHP : 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pizzeria`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

CREATE TABLE `adresses` (
  `id_adresse` int(11) NOT NULL,
  `rue` varchar(255) NOT NULL,
  `numero` varchar(15) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `code_postal` int(11) NOT NULL,
  `pays` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `adresses`
--

INSERT INTO `adresses` (`id_adresse`, `rue`, `numero`, `ville`, `code_postal`, `pays`) VALUES
(156, 'Rue des sablessss', '52', 'Mouscron', 7700, 'France'),
(173, 'Rue du jus', '89', 'Tournai', 7500, 'Belgique'),
(174, 'rue de tournai', '89', 'Tournai', 7500, 'Belgique'),
(175, 'rue albert 1er', '89', 'Tournai', 7500, 'Belgique'),
(176, 'rue achile de backer', '89', 'Mouscron', 7700, 'Belgique'),
(177, 'Rue des moulins', '56', 'Tournai', 7500, 'Belgique'),
(181, 'Rue des sablessss', '53', 'Mouscron', 7700, 'France'),
(182, 'Rue des sablessss', '55', 'Tournai', 7700, 'France'),
(183, 'Rue des sablessss', '9999', 'Mouscron', 7700, 'Belgique'),
(184, 'rue de tournai', '56', 'Tournai', 7500, 'Belgique'),
(185, 'Rue des sablessss', '52', 'Mouscron', 7700, 'France'),
(186, 'Rue des sablessss', '52', 'Mouscron', 7700, 'France'),
(187, 'rue achile de backer', '56', 'Mouscron', 7700, 'Belgique'),
(188, 'rue achile de backer', '1050', 'Mouscron', 7700, 'Belgique');

-- --------------------------------------------------------

--
-- Structure de la table `cartes_fidelite`
--

CREATE TABLE `cartes_fidelite` (
  `id_carte` int(11) NOT NULL,
  `date_creation` date NOT NULL,
  `points` int(11) NOT NULL,
  `expire` date NOT NULL DEFAULT current_timestamp(),
  `id_client` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `cartes_fidelite`
--

INSERT INTO `cartes_fidelite` (`id_carte`, `date_creation`, `points`, `expire`, `id_client`) VALUES
(18, '2020-05-07', 3, '2020-11-07', 18),
(19, '2020-05-09', 1, '2022-05-09', 5),
(20, '2020-05-12', 5, '2021-05-12', 19);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id_categorie` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_categorie`, `nom`, `description`) VALUES
(11, 'Pizzas', 'Les pizzas'),
(15, 'Entrées', 'jhjjhkhj');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id_client` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone_fixe` varchar(20) DEFAULT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `prenom`, `date_naissance`, `email`, `telephone_fixe`, `gsm`, `id_adresse`) VALUES
(5, 'Jacquesd', 'Durand', '2021-02-03', 'jacques@jacques.fr', '056 48 12 45', '0477 45 82 32', 156),
(18, 'Moncul', 'Noir', '2020-05-14', 'dssds@ssdsd.fr', '056 56 89', '', 173),
(19, 'Thierry', 'Montoit', '1998-06-05', 'thierrymontoit@gmail.com', '056 89 74 12', '0478 56 12 56', 177);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `id_personnel` int(11) DEFAULT NULL,
  `id_table` int(11) DEFAULT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_livraison` int(11) DEFAULT NULL,
  `type` enum('1','2','3') NOT NULL,
  `etat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `date`, `id_personnel`, `id_table`, `id_client`, `id_livraison`, `type`, `etat`) VALUES
(1, '2020-05-16 23:15:02', 149, NULL, 5, 1, '1', 0),
(2, '2020-05-16 23:30:50', 149, NULL, 18, 2, '1', 0),
(3, '2020-05-16 23:51:29', 149, NULL, 5, 3, '1', 0),
(4, '2020-05-17 00:00:26', 149, NULL, 5, 4, '1', 0);

-- --------------------------------------------------------

--
-- Structure de la table `commandes_contact`
--

CREATE TABLE `commandes_contact` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `tel_fixe` varchar(20) DEFAULT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_contact`
--

INSERT INTO `commandes_contact` (`id`, `id_commande`, `tel_fixe`, `gsm`, `email`) VALUES
(1, 1, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(2, 2, '056 56 89', '', 'dssds@ssdsd.fr'),
(3, 3, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(4, 4, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_menus`
--

CREATE TABLE `commandes_menus` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_menus`
--

INSERT INTO `commandes_menus` (`id`, `id_commande`, `id_menu`) VALUES
(1, 1, 76),
(2, 1, 76),
(3, 1, 76);

-- --------------------------------------------------------

--
-- Structure de la table `commandes_produits`
--

CREATE TABLE `commandes_produits` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `etat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_produits`
--

INSERT INTO `commandes_produits` (`id`, `id_commande`, `id_produit`, `etat`) VALUES
(1, 1, 26, 1),
(2, 1, 26, 1),
(3, 1, 26, 1),
(4, 1, 26, 1),
(5, 1, 26, 1),
(6, 1, 19, 1),
(7, 1, 19, 1),
(8, 1, 20, 1),
(9, 1, 19, 1),
(10, 1, 19, 1),
(11, 1, 19, 1),
(12, 1, 19, 1),
(13, 2, 19, 1),
(14, 2, 19, 1),
(15, 2, 26, 1),
(16, 2, 26, 1),
(17, 3, 19, 1),
(18, 3, 19, 1),
(19, 3, 26, 1),
(20, 3, 26, 1),
(21, 4, 26, 1),
(22, 4, 26, 1),
(23, 4, 26, 1),
(24, 4, 26, 1),
(25, 4, 26, 1),
(26, 4, 26, 1),
(27, 4, 26, 1),
(28, 4, 26, 1),
(29, 4, 26, 1),
(30, 4, 26, 1);

-- --------------------------------------------------------

--
-- Structure de la table `fonctions`
--

CREATE TABLE `fonctions` (
  `id_fonction` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fonctions`
--

INSERT INTO `fonctions` (`id_fonction`, `nom`) VALUES
(1, 'Gérant'),
(7, 'Serveur'),
(8, 'Cuisinier'),
(9, 'Livreur'),
(99, 'Homme d\'entretien');

-- --------------------------------------------------------

--
-- Structure de la table `fonctions_personnel`
--

CREATE TABLE `fonctions_personnel` (
  `id` int(11) NOT NULL,
  `id_fonction` int(11) DEFAULT NULL,
  `id_personnel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `fonctions_personnel`
--

INSERT INTO `fonctions_personnel` (`id`, `id_fonction`, `id_personnel`) VALUES
(221, 1, 149),
(222, 7, 149),
(223, 8, 149),
(224, 9, 149);

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE `livraisons` (
  `id_livraison` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `etat` int(11) NOT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  `id_livreur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `livraisons`
--

INSERT INTO `livraisons` (`id_livraison`, `date_debut`, `date_fin`, `etat`, `id_adresse`, `id_livreur`) VALUES
(1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 156, NULL),
(2, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 173, NULL),
(3, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 156, NULL),
(4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 156, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `menus`
--

CREATE TABLE `menus` (
  `id_menu` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prix` varchar(10) NOT NULL,
  `etat` int(11) NOT NULL DEFAULT 1,
  `date_creation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `menus`
--

INSERT INTO `menus` (`id_menu`, `nom`, `prix`, `etat`, `date_creation`) VALUES
(76, 'Menu de pâques', '16', 1, '2020-05-08');

-- --------------------------------------------------------

--
-- Structure de la table `menus_produits`
--

CREATE TABLE `menus_produits` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `menus_produits`
--

INSERT INTO `menus_produits` (`id`, `id_menu`, `id_produit`) VALUES
(2679, 76, 26),
(2680, 76, 19),
(2681, 76, 20),
(2682, 76, 29);

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `id_personnel` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `date_naissance` date NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `id_adresse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`id_personnel`, `nom`, `prenom`, `email`, `pass`, `date_naissance`, `telephone`, `id_adresse`) VALUES
(149, 'Hourriez', 'Hugo', 'hugohourriez@live.be', '$2y$08$iTfPQYUV4opJLNUJdZVn..qnbcyl6LL3LaV7QbpbxylijM.2nVHXK', '1999-06-10', '0484 67 16 17', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `prix` varchar(10) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `libelle`, `prix`, `photo`) VALUES
(19, 'Pizza au fromage avec sa feuille de persil', '12', 'uploads/9a4b0d59fe591.jpeg'),
(20, 'Pizza viande', '15', 'uploads/1586098267.jpeg'),
(26, 'Pizza au poulet', '12', 'uploads/d45e79efbc4ad.jpeg'),
(29, 'hhhh', '56', 'uploads/491def75ffe73.jpeg'),
(30, 'dddssd', '6', 'uploads/a4ef02a2e501e.jpeg'),
(31, 'd', '6', 'uploads/5061ef0a3fa90.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `produits_categories`
--

CREATE TABLE `produits_categories` (
  `id` int(11) NOT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `produits_categories`
--

INSERT INTO `produits_categories` (`id`, `id_categorie`, `id_produit`) VALUES
(85, 11, 26),
(92, 15, 20),
(94, 11, 19);

-- --------------------------------------------------------

--
-- Structure de la table `tables`
--

CREATE TABLE `tables` (
  `id_table` int(11) NOT NULL,
  `nb_places` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tables`
--

INSERT INTO `tables` (`id_table`, `nb_places`) VALUES
(1, 4),
(2, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD PRIMARY KEY (`id_adresse`);

--
-- Index pour la table `cartes_fidelite`
--
ALTER TABLE `cartes_fidelite`
  ADD PRIMARY KEY (`id_carte`),
  ADD KEY `fk_client2` (`id_client`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `fk_adresse` (`id_adresse`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `fk_personnel3` (`id_personnel`),
  ADD KEY `fk_table` (`id_table`),
  ADD KEY `fk_client3` (`id_client`),
  ADD KEY `fk_livraison` (`id_livraison`);

--
-- Index pour la table `commandes_contact`
--
ALTER TABLE `commandes_contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commande3` (`id_commande`);

--
-- Index pour la table `commandes_menus`
--
ALTER TABLE `commandes_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commande2` (`id_commande`),
  ADD KEY `fk_menu2` (`id_menu`);

--
-- Index pour la table `commandes_produits`
--
ALTER TABLE `commandes_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commande` (`id_commande`),
  ADD KEY `fk_produit` (`id_produit`);

--
-- Index pour la table `fonctions`
--
ALTER TABLE `fonctions`
  ADD PRIMARY KEY (`id_fonction`);

--
-- Index pour la table `fonctions_personnel`
--
ALTER TABLE `fonctions_personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_personnel` (`id_personnel`),
  ADD KEY `fk_fonction` (`id_fonction`);

--
-- Index pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD PRIMARY KEY (`id_livraison`),
  ADD KEY `fk_adresse3` (`id_adresse`),
  ADD KEY `fk_personnel2` (`id_livreur`);

--
-- Index pour la table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id_menu`);

--
-- Index pour la table `menus_produits`
--
ALTER TABLE `menus_produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_menu` (`id_menu`),
  ADD KEY `fk_produit3` (`id_produit`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id_personnel`),
  ADD KEY `fk_adresse2` (`id_adresse`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `produits_categories`
--
ALTER TABLE `produits_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_categorie` (`id_categorie`),
  ADD KEY `fk_produit2` (`id_produit`);

--
-- Index pour la table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id_table`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresses`
--
ALTER TABLE `adresses`
  MODIFY `id_adresse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT pour la table `cartes_fidelite`
--
ALTER TABLE `cartes_fidelite`
  MODIFY `id_carte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commandes_contact`
--
ALTER TABLE `commandes_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commandes_menus`
--
ALTER TABLE `commandes_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `commandes_produits`
--
ALTER TABLE `commandes_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `fonctions`
--
ALTER TABLE `fonctions`
  MODIFY `id_fonction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT pour la table `fonctions_personnel`
--
ALTER TABLE `fonctions_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `id_livraison` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `menus_produits`
--
ALTER TABLE `menus_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2684;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id_personnel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `produits_categories`
--
ALTER TABLE `produits_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT pour la table `tables`
--
ALTER TABLE `tables`
  MODIFY `id_table` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cartes_fidelite`
--
ALTER TABLE `cartes_fidelite`
  ADD CONSTRAINT `fk_client2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_adresse` FOREIGN KEY (`id_adresse`) REFERENCES `adresses` (`id_adresse`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `fk_client3` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_livraison` FOREIGN KEY (`id_livraison`) REFERENCES `livraisons` (`id_livraison`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_personnel3` FOREIGN KEY (`id_personnel`) REFERENCES `personnel` (`id_personnel`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_table` FOREIGN KEY (`id_table`) REFERENCES `tables` (`id_table`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `commandes_contact`
--
ALTER TABLE `commandes_contact`
  ADD CONSTRAINT `fk_commande3` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `commandes_menus`
--
ALTER TABLE `commandes_menus`
  ADD CONSTRAINT `fk_commande2` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_menu2` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `commandes_produits`
--
ALTER TABLE `commandes_produits`
  ADD CONSTRAINT `fk_commande` FOREIGN KEY (`id_commande`) REFERENCES `commandes` (`id_commande`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `fonctions_personnel`
--
ALTER TABLE `fonctions_personnel`
  ADD CONSTRAINT `fk_fonction` FOREIGN KEY (`id_fonction`) REFERENCES `fonctions` (`id_fonction`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_personnel` FOREIGN KEY (`id_personnel`) REFERENCES `personnel` (`id_personnel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `livraisons`
--
ALTER TABLE `livraisons`
  ADD CONSTRAINT `fk_adresse3` FOREIGN KEY (`id_adresse`) REFERENCES `adresses` (`id_adresse`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_personnel2` FOREIGN KEY (`id_livreur`) REFERENCES `personnel` (`id_personnel`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `menus_produits`
--
ALTER TABLE `menus_produits`
  ADD CONSTRAINT `fk_menu` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit3` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD CONSTRAINT `fk_adresse2` FOREIGN KEY (`id_adresse`) REFERENCES `adresses` (`id_adresse`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `produits_categories`
--
ALTER TABLE `produits_categories`
  ADD CONSTRAINT `fk_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categories` (`id_categorie`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_produit2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
