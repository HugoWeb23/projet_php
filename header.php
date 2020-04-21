<?php

if(empty($_SESSION['id_personnel'])) {
    header('location: connexion');
    exit;
}
if(isset($_GET['deconnexion'])) {
    session_destroy();
    header('location: connexion');
}
?>
<div class="nav">
<div class="titre">
<div class="logo">
<img src="images/logo.png">
</div>
<div class="nom">
    Pizza Royale
</div>
</div>
<nav>
<ul class="menu">
<div class="dropdown">
<a href="#">Commandes</a>
<div class="dropdown-child">
<a href="">Créer une nouvelle commande</a>
</div>
</div>
<div class="dropdown">
<a href="#">Clients</a>
<div class="dropdown-child">
<a href="creerclient">Créer un compte client</a>
<a href="gestionclients">Gestion des clients</a>
</div>
</div>
<div class="dropdown">
<a href="#">Personnel</a>
<div class="dropdown-child">
<a href="creeremploye">Créer un profil employé</a>
<a href="gestionemployes">Gestion des employés</a>
</div>
</div>
<div class="dropdown">
<a href="gestionproduits">Produits</a>
<div class="dropdown-child">
<a href="gestionproduits">Gestion des produits</a>
<a href="creerproduit">Créer un produit</a>
<a href="creermenu">Créer un menu</a>
<a href="gestioncategories">Gestion des catégories</a>
</div>
</div>
</ul>
</nav>
<div class="connexion">
<div class="dropdown">
<a href="#">Mon compte</a>
<div class="dropdown-child">
<a href="">Gérer mes informations</a>
<a href="?deconnexion">Déconnexion</a>
</div>
</div>
</div> 
</div>