-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 24 juin 2020 à 20:46
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
(173, 'Rue du jus', '56', 'Tournai', 7500, 'Belgique'),
(174, 'Rue de Tournai', '89', 'Tournai', 7500, 'Belgique'),
(193, 'Rue du plavitout', '56', 'Luingne', 7700, 'Belgique'),
(223, 'Rue Albert 1er', '51', 'Mouscron', 7700, 'Belgique'),
(224, 'Rue de la Station', '100', 'Mouscron', 7700, 'Belgique'),
(225, 'Rue Courte', '6', 'Luingne', 7700, 'Belgique');

-- --------------------------------------------------------

--
-- Structure de la table `cartes_fidelite`
--

CREATE TABLE `cartes_fidelite` (
  `id_carte` int(11) NOT NULL,
  `date_creation` date NOT NULL,
  `points` int(11) NOT NULL,
  `expire` date NOT NULL,
  `id_client` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(124, 'Desserts', 'Les desserts'),
(127, 'Boissons', 'Les boissons'),
(136, 'Pizzas', 'Les pizzas');

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
(5, 'Jacques', 'Durand', '1989-02-03', 'jacques@jacques.fr', '056 48 12 45', '0477 45 82 32', 173),
(26, 'Jeannette', 'Bontoit', '1947-06-11', 'jeanne@free.frr', '045 56 23 56', '0489 56 23 11', 193);

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
  `etat` int(11) NOT NULL,
  `commentaire` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Structure de la table `commandes_menus`
--

CREATE TABLE `commandes_menus` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `etat` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `commandes_produits`
--

CREATE TABLE `commandes_produits` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `etat` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(2, 'Serveur'),
(3, 'Cuisinier'),
(4, 'Livreur'),
(5, 'Homme d\'entretien');

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
(326, 1, 149),
(327, 3, 154),
(328, 1, 161);

-- --------------------------------------------------------

--
-- Structure de la table `livraisons`
--

CREATE TABLE `livraisons` (
  `id_livraison` int(11) NOT NULL,
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `etat` int(11) NOT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  `id_livreur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(76, 'Menu de pâques', '25', 1, '2020-05-08'),
(80, 'Menu spécial fêtes', '12.6', 1, '2020-05-19'),
(97, 'Menu de la Saint-Valentin', '50.55', 1, '2020-06-06');

-- --------------------------------------------------------

--
-- Structure de la table `menus_produits`
--

CREATE TABLE `menus_produits` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `menus_produits`
--

INSERT INTO `menus_produits` (`id`, `id_menu`, `id_produit`, `quantite`) VALUES
(1, 97, 40, 1),
(2, 97, 19, 1),
(3, 97, 29, 1),
(4, 76, 39, 2),
(5, 76, 32, 2),
(6, 76, 31, 2),
(7, 80, 29, 2),
(8, 80, 32, 2);

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id_permission` int(11) NOT NULL,
  `id_fonction` int(11) NOT NULL,
  `c_produit` int(11) NOT NULL DEFAULT 0,
  `g_produits` int(11) NOT NULL DEFAULT 0,
  `c_menu` int(11) NOT NULL DEFAULT 1,
  `g_menus` int(11) NOT NULL DEFAULT 1,
  `g_categ` int(11) NOT NULL DEFAULT 0,
  `c_employe` int(11) NOT NULL DEFAULT 0,
  `g_employe` int(11) NOT NULL DEFAULT 0,
  `g_fonctions` int(11) NOT NULL DEFAULT 0,
  `g_permissions` int(11) NOT NULL DEFAULT 0,
  `c_client` int(11) NOT NULL DEFAULT 1,
  `g_clients` int(11) NOT NULL DEFAULT 1,
  `c_commande` int(11) NOT NULL DEFAULT 1,
  `g_commandes` int(11) NOT NULL DEFAULT 1,
  `g_livraisons` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id_permission`, `id_fonction`, `c_produit`, `g_produits`, `c_menu`, `g_menus`, `g_categ`, `c_employe`, `g_employe`, `g_fonctions`, `g_permissions`, `c_client`, `g_clients`, `c_commande`, `g_commandes`, `g_livraisons`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0),
(3, 3, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 1, 0),
(4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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
  `tel_fixe` varchar(15) DEFAULT NULL,
  `gsm` varchar(20) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`id_personnel`, `nom`, `prenom`, `email`, `pass`, `date_naissance`, `tel_fixe`, `gsm`, `id_adresse`) VALUES
(149, 'Hourriez', 'Hugo', 'hugohourriez@live.be', '$2y$08$iTfPQYUV4opJLNUJdZVn..qnbcyl6LL3LaV7QbpbxylijM.2nVHXK', '1999-06-10', '0484 67 16 17', '0484 671 617', 223),
(154, 'Jacques', 'Pierre', 'jacquespierre@gmail.com', '$2y$08$W/cAa3uUMsHUuhv7XpLef.4KeXX6PDh4cPxZAYvewGaQ7vN.0v732', '1987-06-26', '06 56 45 78', '0478 45 12 33', 224),
(161, 'Admin', 'Admin', 'admin@admin.be', '$2y$08$PxAw0F4osSU5GkTBApeL1uppVTvwtUHoP.JgZ1EKSrkKtzgyKraii', '1964-05-12', '056 45 12 56', '0489 56 12 56', 225);

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
(19, 'Margherita', '9.5', 'uploads/7000ec3537e20.jpeg'),
(20, 'Viande', '15', 'uploads/1586098267.jpeg'),
(29, '4 fromages', '10', 'uploads/cec4203b0f527.jpeg'),
(30, '6 fromages', '11', 'uploads/e066eebe0c375.jpeg'),
(31, 'Napolitaine', '9', 'uploads/2603df01c35ec.jpeg'),
(32, 'Coca-Cola 33cl', '2.6', 'uploads/db5079ef5c41f.jpeg'),
(33, 'Ice Tea 33cl', '2.5', 'uploads/56e5d19d1010f.jpeg'),
(39, 'Tiramisu spéculoos', '5.50', 'uploads/8f0f8e95a2323.jpeg'),
(40, 'Tarte aux fraises', '7.3', 'uploads/ebb9fd53e58a7.jpeg');

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
(160, 136, 19),
(161, 136, 20),
(162, 136, 29),
(163, 136, 30),
(164, 136, 31),
(168, 124, 39),
(169, 127, 32),
(170, 127, 33),
(171, 124, 40);

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
(2, 2),
(3, 6),
(4, 8),
(5, 1),
(6, 1),
(7, 2),
(8, 2),
(9, 4),
(10, 8);

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
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id_permission`),
  ADD KEY `fk_fonction2` (`id_fonction`);

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
  MODIFY `id_adresse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT pour la table `cartes_fidelite`
--
ALTER TABLE `cartes_fidelite`
  MODIFY `id_carte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commandes_contact`
--
ALTER TABLE `commandes_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commandes_menus`
--
ALTER TABLE `commandes_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commandes_produits`
--
ALTER TABLE `commandes_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `fonctions`
--
ALTER TABLE `fonctions`
  MODIFY `id_fonction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT pour la table `fonctions_personnel`
--
ALTER TABLE `fonctions_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=329;

--
-- AUTO_INCREMENT pour la table `livraisons`
--
ALTER TABLE `livraisons`
  MODIFY `id_livraison` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT pour la table `menus_produits`
--
ALTER TABLE `menus_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id_permission` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id_personnel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `produits_categories`
--
ALTER TABLE `produits_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT pour la table `tables`
--
ALTER TABLE `tables`
  MODIFY `id_table` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cartes_fidelite`
--
ALTER TABLE `cartes_fidelite`
  ADD CONSTRAINT `fk_client2` FOREIGN KEY (`id_client`) REFERENCES `clients` (`id_client`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Contraintes pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `fk_fonction2` FOREIGN KEY (`id_fonction`) REFERENCES `fonctions` (`id_fonction`) ON DELETE CASCADE ON UPDATE CASCADE;

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
