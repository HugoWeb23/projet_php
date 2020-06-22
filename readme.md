# Application de gestion des commandes

## Pré-requis

### Présentation du projet

Cette application permet aux employés d'une pizzeria de gérer les commandes du restaurant. Les commandes peuvent être constituées de produits (boissons, entrées, plats, desserts, ...) et de menus qui contiennent eux-même des produits.

### Base de données

La base de données est disponible dans le dossier [BDD](https://github.com/HugoWeb23/projet_php/tree/master/BDD).

### Identifiants de connexion

Adresse e-mail : __admin@admin.be__ Mot de passe : __admin1234__

## Détails et fonctionnement de l'application

### Connexion

Les employés se connectent avec leur adresse e-mail et leur mot de passe.

### Création des produits

Pour créer un produit dans l'application, la personne responsable de cette tâche devra saisir un nom ainsi qu'un prix. Le produit doit obligatoirement être associé à une photo pour être mis en ligne.

### Gestion des produits

Cette page affiche l'ensemble des produits les uns à côté des autres. Elle permet de les modifier facilement en cliquant sur l'icône "modifier". Un message sur fond rouge apparaît si le produit n'est associé à aucune catégorie.

### Gestion des catégories des produits

Les catégories sont affichées dans un tableau, elles peuvent être éditées et supprimées facilement.

### Création d'une commande

Il existe trois types de commande (livraison à domicile, sur place et à emporter).

#### Livraison à domicile

L'employé doit saisir une adresse de livraison (rue, numéro, code postal, ville, pays). Le champ "rue" propose automatiquement des noms de rues en rapport avec la saisie pour ensuite remplir automatiquement les champs (code postal, ville et pays). L'autocomplétion se base sur les adresses présentes dans la base de données pour proposer des suggestions. L'employé doit également saisir des informations de contact sur le client à livrer (numéro de téléphone, numéro de GSM, adresse e-mail), seulement un champ sur trois est obligatoire.

#### Sur place

Seul le choix d'une table est obligatoire.

#### À emporter

L'employé doit compléter les informations de contact du client.

#### Ajouter des produits dans une commande

La méthode d'ajout de produits/menus à la commande reste la même pour les trois types de commande. Un simple clic sur le boutton "ajouter" et le produit/menu se retrouve dans la colonne "détails de la commande". La quantité par défaut est définie à 1, si le boutton "ajouter" est enclenché plusieurs fois, la quantité du produit va augmenter (+1). Pour aller plus vite, l'employé a la possibilité de saisir un chiffre dans le champ "quantité" et de cliquer sur "valider quantité".

### Responsive

L'application étant destinée à être utilisée sur des tablettes et téléphones, elle est entièrement responsive. En réduisant votre écran, vous verrez le contenu s'adapter et le menu horizontal disparaître pour laisser place à l'icône "burger" qui fera apparaître le menu vertical.