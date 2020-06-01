<?php

session_start();

require('config.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
<link href="css/styles.css" rel="stylesheet">
<script>
afficher_livraisons();

function afficher_livraisons() {
 $.ajax({
             url:"ajax/afficherlivraisons.php",
             method:"post",
             data:{action:'afficher_livraisons'},
             success:function(data)
             {
                 $('#liste_livraisons').html(data).fadeIn('slow');
             }
         });
}
</script>
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des livraisons</h1>
</div>
<div class="meslivraisons">
<h2>Mes livraisons</h2>
</div>
<?php
$req = $bdd->prepare('SELECT *, b.id_livraison as id_livraison FROM commandes as a INNER JOIN livraisons as b ON a.id_livraison = b.id_livraison WHERE a.etat = 1 AND a.type = 1 AND b.etat = 1 AND b.id_livreur = :id_livreur ORDER BY b.date_debut');
$req->bindValue('id_livreur', $personnel['id_personnel'], PDO::PARAM_INT);
$req->execute();
if($req->rowCount() > 0) {

while($afficher = $req->fetch()) {
?>
<div class="contenu_commande">
<div class="titre_commande">
Commande n° <?= $afficher['id_commande']; ?><button class="prendre_commande" data-id_livraison="<?= $afficher['id_livraison']; ?>">Livraison effectuée</button>
</div>
<button class="livraison-details-produits" data-id_commande="<?= $afficher['id_commande']; ?>">Détails commande</button>
<div class="commande_produits">
<?php
$req3 = $bdd->prepare('SELECT * FROM commandes as a LEFT JOIN livraisons as b ON a.id_livraison = b.id_livraison LEFT JOIN adresses as c ON b.id_adresse = c.id_adresse LEFT JOIN commandes_contact as d ON a.id_commande = d.id_commande WHERE a.id_commande = :id_commande');
$req3->bindValue('id_commande', $afficher['id_commande'], PDO::PARAM_INT);
$req3->execute();
$adresse = $req3->fetch();
?>
<?php if($adresse['type'] == 1) { ?>
<div class="commande_livraison">
<b>Informations de livraison</b>
<br>
<span><?= $adresse['rue']; ?>, <?= $adresse['numero']; ?></span>
<span><?= $adresse['code_postal']; ?> <?= $adresse['ville']; ?></span>
</div>
<?php } ?>
<?php if($adresse['type'] == 1 || $adresse['type'] == 3) { ?>
<div class="commande_contact">
<b>Informations de contact</b>
<br>
<span><?= $adresse['tel_fixe']; ?></span>
<span><?= $adresse['gsm']; ?></span>
<span><?= $adresse['email']; ?></span>
</div>
<?php }?>
</div>

</div>    
<?php        
}
}
?>
<div class="attentelivraisons">
<h2>Livraisons en attente</h2>
</div>
<div id="liste_livraisons"></div>
</div>
</body>
</html>