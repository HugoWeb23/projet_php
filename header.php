<?php

if(empty($_SESSION['id_personnel'])) {
    header('location: connexion');
    exit;
}
if(isset($_POST['deconnexion'])) {
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
<a href="">Créer un compte client</a>
</div>
</div>
<div class="dropdown">
<a href="#">Personnel</a>
<div class="dropdown-child">
<a href="">Créer un profil employé</a>
<a href="">Gestion des employés</a>
</div>
</div>
<div class="dropdown">
<a href="#">Produits</a>
<div class="dropdown-child">
<a href="">Gestion des menus</a>
<a href="">Gestion des catégories</a>
</div>
</div>
</ul>
</nav>
<form action="" method="post">
<div class="connexion">
<?= $_SESSION['id_personnel']; ?><input type="submit" name="deconnexion" value="Se déconnecter">
</form>
</div> 
</div>