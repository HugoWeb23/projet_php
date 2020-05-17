<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'afficher_commandes') {

$req = $bdd->prepare('SELECT * FROM commandes WHERE etat = 0 ORDER BY id_commande');
$req->execute();
if($req->rowCount() < 1) {
    echo 'Aucun résultat';
} else {

while($afficher = $req->fetch()) {
switch($afficher['type']) {
case 1:
$type = 'livraison';
break;
case 2:
$type = 'sur place';
break;
case 3:
$type = 'à emporter';
break;
}
?>
<div class="contenu_commande">
<div class="titre_commande">
Commande n° <?= $afficher['id_commande']; ?> <span class="type"><?= $type; ?></span><button class="cloturer_commande">Clôturer la commande</button><a class="editer_commande" href="#">Modifier la commande</a>
</div>
<div class="commande_produits">
<div class="produit">
<?php
$req2 = $bdd->prepare('SELECT a.libelle, a.id_produit, count(b.id_produit) as compteur, b.etat FROM produits as a INNER JOIN commandes_produits as b ON b.id_produit = a.id_produit AND b.id_commande = :commande GROUP BY b.id_produit ORDER BY compteur DESC');
$req2->bindValue('commande', $afficher['id_commande'], PDO::PARAM_INT);
$req2->execute();
$f = $req2->rowCount();
while($commande = $req2->fetch()) {
?>
<div><?= $commande['compteur']; ?> x <?= $commande['libelle']; ?> <?php if($commande['etat'] == 0) { ?><button class="commande_etat" data-id_commande="<?= $afficher['id_commande']; ?>" data-id_produit="<?= $commande['id_produit']; ?>">Prêt</button><?php } else { echo '<font color="green">Prêt !</font>'; } ?></div>
<?php } ?>
</div>
<?php
$req3 = $bdd->prepare('SELECT * FROM commandes as a LEFT JOIN livraisons as b ON a.id_livraison = b.id_livraison LEFT JOIN adresses as c ON b.id_adresse = c.id_adresse LEFT JOIN commandes_contact as d ON a.id_commande = d.id_commande WHERE a.id_commande = :id_commande');
$req3->bindValue('id_commande', $afficher['id_commande'], PDO::PARAM_INT);
$req3->execute();
$adresse = $req3->fetch();
?>
<?php if($adresse['type'] == 1) { ?>
<div class="commande_livraison">
Informations de livraison
<br>
<span><?= $adresse['rue']; ?>, <?= $adresse['numero']; ?></span>
<span><?= $adresse['code_postal']; ?> <?= $adresse['ville']; ?></span>
</div>
<?php } ?>
<?php if($adresse['type'] == 1 || $adresse['type'] == 3) { ?>
<div class="commande_contact">
Informations de contact
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
}

if(isset($_POST['action']) && $_POST['action'] == 'etat_produit') {
$id_commande = $_POST['id_commande'];
$id_produit = $_POST['id_produit'];

$req = $bdd->prepare('UPDATE commandes_produits SET etat = 1 WHERE id_commande = :id_commande AND id_produit = :id_produit');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->bindValue('id_produit', $id_produit, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}

if(isset($_POST['action']) && $_POST['action'] == 'cloturer_commande') {
$id_commande = $_POST['id_commande'];
$req = $bdd->prepare('UPDATE commandes SET etat = 1 WHERE id_commande = :id_commande');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
}

?>