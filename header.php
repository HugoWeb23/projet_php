<?php

if(empty($_SESSION['id_personnel'])) {
    header('location: connexion');
    exit;
}
if(isset($_GET['deconnexion'])) {
    session_destroy();
    header('location: connexion');
}

$permissions = verif_permissions($personnel['id_personnel'], array('c_produit', 'g_produits', 'c_menu', 'g_menus', 'g_categ', 'c_employe', 'g_employe', 'g_fonctions', 'g_permissions', 'c_client', 'g_clients', 'c_commande', 'g_commandes', 'g_livraisons'));

?>
<div class="nav">
<div class="burger" id="burger">
<div class="barre1"></div>
<div class="barre2"></div>
<div class="barre3"></div>
</div>
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
<?php if($permissions[11] == 1 || $permissions[12] == 1 || $permissions[13] == 1) { ?>
<div class="dropdown">
<a href="#">Commandes</a>
<div class="dropdown-child">
<?php if($permissions[11] == 1) { ?><li><a href="creercommande">Créer une nouvelle commande</a></li><?php } ?>
<?php if($permissions[12] == 1) { ?><li><a href="gestioncommandes">Gestion des commandes</a></li><?php } ?>
<?php if($permissions[13] == 1) { ?><li><a href="gestionlivraisons">Gestion des livraisons</a></li><?php } ?>
</div>
</div>
<?php } ?>
<?php if($permissions[9] == 1 || $permissions[10] == 1) { ?>
<div class="dropdown">
<a href="#">Clients</a>
<div class="dropdown-child">
<?php if($permissions[9] == 1) { ?><li><a href="creerclient">Créer un compte client</a></li><?php } ?>
<?php if($permissions[10] == 1) { ?><li><a href="gestionclients">Chercher des clients</a></li><?php } ?>
</div>
</div>
<?php } ?>
<?php if($permissions[5] == 1 || $permissions[6] == 1 || $permissions[8] == 1) { ?>
<div class="dropdown">
<a href="#">Personnel</a>
<div class="dropdown-child">
<?php if($permissions[5] == 1) { ?><li><a href="creeremploye">Créer un profil employé</a></li><?php } ?>
<?php if($permissions[6] == 1) { ?><li><a href="gestionemployes">Chercher des employés</a></li><?php } ?>
<?php if($permissions[6] == 1) { ?><li><a href="gestionfonctions">Gestion des fonctions</a></li><?php } ?>
<?php if($permissions[8] == 1) { ?><li><a href="gestionpermissions">Gestion des permissions</a></li><?php } ?>
</div>
</div>
<?php } ?>
<?php if($permissions[0] == 1 || $permissions[1] == 1 || $permissions[2] == 1 || $permissions[3] == 1 || $permissions[4] == 1) { ?>
<div class="dropdown">
<a href="#">Produits</a>
<div class="dropdown-child">
<?php if($permissions[0] == 1) { ?><li><a href="creerproduit">Créer un produit</a></li><?php } ?>
<?php if($permissions[1] == 1) { ?><li><a href="gestionproduits">Gestion des produits</a></li><?php } ?>
<?php if($permissions[2] == 1) { ?><li><a href="creermenu">Créer un menu</a><?php } ?>
<?php if($permissions[3] == 1) { ?><li><a href="gestionmenus">Gestion des menus</a></li><?php } ?>
<?php if($permissions[4] == 1) { ?><li><a href="gestioncategories">Gestion des catégories</a></li><?php } ?>
</div>
</div>
<?php } ?>
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
<div class="fermermenu"></div>
</div>