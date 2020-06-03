-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 03 juin 2020 à 23:55
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
(188, 'rue achile de backer', '1050', 'Mouscron', 7700, 'Belgique'),
(189, 'Rue de test', '56', 'Dottignies', 7700, 'Belgique'),
(190, 'Rue des sablessss', '56', 'Tournai', 7700, 'France'),
(191, 'rue albert 1er', '59', 'Tournai', 7500, 'Belgique'),
(192, 'rue achile de backer', '9999', 'Mouscron', 7700, 'Belgique'),
(193, 'Rue du plavitout', '56', 'Luingne', 7700, 'Belgique'),
(194, 'Rue moncul', '51', 'Mouscron', 7700, 'Belgique'),
(195, 'Rue du plavitout', '57', 'Luingne', 7700, 'Belgique'),
(196, 'Rue des sablessss', '54', 'Mouscron', 7700, 'France');

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
(18, '2020-05-07', 25, '2022-05-19', 18),
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
(50, 'Pizzas', 'Les pizzas');

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
(18, 'Pierre', 'Dupont', '2020-05-14', 'pierredupont@gmail.com', '056 56 89', '', 173),
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
  `etat` int(11) NOT NULL,
  `commentaire` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `date`, `id_personnel`, `id_table`, `id_client`, `id_livraison`, `type`, `etat`, `commentaire`) VALUES
(1, '2020-06-02 17:26:34', 149, NULL, 5, 1, '1', 1, ''),
(2, '2020-06-02 17:26:49', 149, NULL, 19, NULL, '3', 1, ''),
(3, '2020-06-02 17:41:37', 149, NULL, 5, 2, '1', 1, ''),
(4, '2020-06-03 02:15:59', 149, NULL, 5, 3, '1', 1, ''),
(5, '2020-06-03 15:49:09', 149, NULL, 5, 4, '1', 1, '- Pas de champignons sur la pizza au poulet\n- Livraison : sonner longtemps'),
(6, '2020-06-03 16:26:13', 149, NULL, 18, 5, '1', 1, ''),
(7, '2020-06-03 16:53:56', 149, NULL, 19, 6, '1', 1, ''),
(8, '2020-06-03 16:54:02', 149, NULL, 19, 7, '1', 1, ''),
(9, '2020-06-03 21:29:12', 149, NULL, 5, 8, '1', 1, ''),
(10, '2020-06-03 21:29:38', 149, NULL, 5, 9, '1', 1, ''),
(11, '2020-06-03 21:33:44', 149, NULL, 5, 10, '1', 1, ''),
(12, '2020-06-03 23:03:57', 149, NULL, 5, 11, '1', 1, '');

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
(2, 2, '056 89 74 12', '0478 56 12 56', 'thierrymontoit@gmail.com'),
(3, 3, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(4, 4, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(5, 5, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(6, 6, '056 56 89', '', 'pierredupont@gmail.com'),
(7, 7, '056 89 74 12', '0478 56 12 56', 'thierrymontoit@gmail.com'),
(8, 8, '056 89 74 12', '0478 56 12 56', 'thierrymontoit@gmail.com'),
(9, 9, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(10, 10, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(11, 11, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr'),
(12, 12, '056 48 12 45', '0477 45 82 32', 'jacques@jacques.fr');

-- --------------------------------------------------------

--
-- Structure de la table `commandes_menus`
--

CREATE TABLE `commandes_menus` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `etat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_menus`
--

INSERT INTO `commandes_menus` (`id`, `id_commande`, `id_menu`, `quantite`, `etat`) VALUES
(1, 1, 95, 1, 0),
(2, 1, 96, 1, 0),
(3, 4, 95, 1, 0),
(4, 5, 80, 1, 0),
(5, 6, 80, 1, 0),
(6, 7, 96, 1, 0),
(7, 7, 95, 1, 0),
(8, 7, 80, 1, 0),
(9, 7, 76, 1, 0),
(10, 11, 96, 1, 0),
(11, 11, 95, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `commandes_produits`
--

CREATE TABLE `commandes_produits` (
  `id` int(11) NOT NULL,
  `id_commande` int(11) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `etat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes_produits`
--

INSERT INTO `commandes_produits` (`id`, `id_commande`, `id_produit`, `quantite`, `etat`) VALUES
(1, 1, 30, 5, 0),
(2, 1, 26, 2, 0),
(3, 2, 26, 1, 0),
(4, 2, 20, 1, 0),
(5, 3, 26, 1, 0),
(6, 5, 29, 1, 1),
(7, 5, 19, 1, 1),
(8, 5, 20, 1, 1),
(9, 5, 26, 1, 1),
(10, 5, 30, 1, 1),
(11, 8, 26, 1, 0),
(12, 9, 20, 5, 0),
(13, 10, 20, 1, 0),
(14, 10, 26, 1, 0),
(15, 12, 30, 1, 0),
(16, 12, 26, 1, 0);

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
(1, '2020-06-03 02:27:47', '2020-06-03 02:27:52', 3, 156, 149),
(2, '2020-06-02 21:46:24', '2020-06-02 21:47:39', 3, 156, 149),
(3, '2020-06-03 02:28:17', '2020-06-03 02:31:26', 3, 156, 149),
(4, '2020-06-03 18:48:25', '2020-06-03 21:41:16', 3, 156, 149),
(5, '2020-06-03 18:48:30', '2020-06-03 21:41:17', 3, 173, 149),
(6, '2020-06-03 18:48:31', '2020-06-03 18:50:14', 3, 177, 149),
(7, '2020-06-03 21:41:38', '0000-00-00 00:00:00', 2, 177, 149),
(8, '2020-06-03 23:35:15', '2020-06-03 23:35:19', 3, 156, 149),
(9, '2020-06-03 23:36:25', '0000-00-00 00:00:00', 2, 156, 149),
(10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 156, NULL),
(11, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 156, NULL);

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
(76, 'Menu de pâques', '16', 1, '2020-05-08'),
(80, 'Menu spécial fêtes', '14', 1, '2020-05-19'),
(95, 'menu test', '20', 1, '2020-05-28'),
(96, 'Menu jean-jacques', '50', 1, '2020-05-28');

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
(4, 76, 30, 1),
(5, 76, 19, 5),
(6, 76, 26, 1),
(7, 80, 29, 2),
(8, 80, 31, 1),
(9, 80, 26, 3),
(62, 80, 20, 1),
(63, 95, 29, 5),
(64, 95, 20, 5),
(65, 95, 26, 10),
(66, 95, 30, 6),
(67, 95, 19, 11),
(68, 96, 29, 1),
(69, 96, 19, 1),
(70, 96, 20, 1),
(71, 96, 26, 1),
(72, 96, 30, 1);

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
(149, 'Hourriez', 'Hugo', 'hugohourriez@live.be', '$2y$08$iTfPQYUV4opJLNUJdZVn..qnbcyl6LL3LaV7QbpbxylijM.2nVHXK', '1999-06-10', '0484 67 16 17', 177);

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
(19, 'Margherita', '9', 'uploads/7000ec3537e20.jpeg'),
(20, 'Pizza viande', '15', 'uploads/1586098267.jpeg'),
(26, 'Pizza au poulet', '12', 'uploads/ecd4c7346c50b.jpeg'),
(29, '4 fromages', '10', 'uploads/cec4203b0f527.jpeg'),
(30, '6 fromages', '11', 'uploads/e066eebe0c375.jpeg'),
(31, 'Napolitaine', '9', 'uploads/2603df01c35ec.jpeg');

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
(117, 50, 29),
(118, 50, 19),
(119, 50, 20),
(120, 50, 26),
(121, 50, 30);

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
(9, 4);

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
  MODIFY `id_adresse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT pour la table `cartes_fidelite`
--
ALTER TABLE `cartes_fidelite`
  MODIFY `id_carte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `commandes_contact`
--
ALTER TABLE `commandes_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `commandes_menus`
--
ALTER TABLE `commandes_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `commandes_produits`
--
ALTER TABLE `commandes_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
  MODIFY `id_livraison` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT pour la table `menus_produits`
--
ALTER TABLE `menus_produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT pour la table `tables`
--
ALTER TABLE `tables`
  MODIFY `id_table` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
