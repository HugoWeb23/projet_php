<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'afficher_commandes') {

$req = $bdd->prepare('SELECT *, b.etat as etat FROM commandes as a LEFT JOIN livraisons as b ON a.id_livraison = b.id_livraison WHERE a.etat = 1 ORDER BY id_commande');
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
$type = 'sur place - table '.$afficher['id_table'];
break;
case 3:
$type = 'à emporter';
break;
}
?>
<div class="contenu_commande">
<div class="titre_commande">
Commande n° <?= $afficher['id_commande']; ?> <span class="type"><?= $type; ?></span><?php if($afficher['etat'] < 2 && minutes_dates($afficher['date']) <= 60) { ?> <button class="retablir_commande" data-id_commande="<?= $afficher['id_commande']; ?>">Rétablir la commande</button><?php } if($afficher['etat'] > 1) { ?> <button class="infos_commande" data-id_commande="<?= $afficher['id_commande']; ?>">Détails de livraison</button><?php } ?>
</div>
<div class="commande_produits">
<div class="produit">
<?php
$req4 = $bdd->prepare('SELECT a.quantite, a.etat, b.nom, a.id_menu as id_menu FROM commandes_menus as a INNER JOIN menus as b ON a.id_menu = b.id_menu AND a.id_commande = :commande ORDER BY a.quantite DESC');
$req4->bindValue('commande', $afficher['id_commande'], PDO::PARAM_INT);
$req4->execute();
while($menu = $req4->fetch()) {
?>
<div><?= $menu['quantite']; ?> x <?= $menu['nom']; ?></div>
<div class="menus_produits">
<?php
$req5 = $bdd->prepare('SELECT * FROM menus_produits as a INNER JOIN produits as b ON a.id_produit = b.id_produit WHERE a.id_menu = :id_menu ORDER BY a.quantite DESC');
$req5->bindValue('id_menu', $menu['id_menu'], PDO::PARAM_INT);
$req5->execute();
while($produitsmenu = $req5->fetch()) {
?>
<div class="details_menu"><?= $produitsmenu['quantite']; ?> x <?= $produitsmenu['libelle']; ?></div>

<?php } ?>
</div>
<?php 
}
$req2 = $bdd->prepare('SELECT a.libelle, a.id_produit, b.quantite, b.etat FROM produits as a INNER JOIN commandes_produits as b ON b.id_produit = a.id_produit AND b.id_commande = :commande ORDER BY b.quantite DESC');
$req2->bindValue('commande', $afficher['id_commande'], PDO::PARAM_INT);
$req2->execute();
while($commande = $req2->fetch()) {
?>
<div class="commande_details_produit"><?= $commande['quantite']; ?> x <?= $commande['libelle']; ?></div>
<?php } ?>
<?php if(strlen($afficher['commentaire']) > 1) { ?>
<div class="afficher-commentaire-commande">
<b>Commentaire :</b> <?= $afficher['commentaire']; ?>
</div>
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
}

// Rétablir une commande

if(isset($_POST['action']) && $_POST['action'] == 'retablir_commande') {
$id_commande = $_POST['id_commande'];
$req = $bdd->prepare('SELECT *, b.etat as type FROM commandes as a LEFT JOIN livraisons as b ON a.id_livraison = b.id_livraison WHERE a.id_commande = :id_commande');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute();
$checklivraison = $req->fetch(PDO::FETCH_ASSOC);
if($checklivraison['etat'] < 2) {
$req = $bdd->prepare('UPDATE commandes as a LEFT JOIN livraisons as b ON a.id_livraison = b.id_livraison SET a.etat = 0, b.etat = 0 WHERE id_commande = :id_commande');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$message = array("type" => "succes");
} else {
$message = array("type" => "erreur", "message" => "Cette commande ne peut pas être rétablie car elle a déjà été livrée ou est en cours de livraison");
}
echo json_encode($message);
}

// Détails d'une commande

if(isset($_POST['action']) && $_POST['action'] == 'details_commande') {
$id_commande = $_POST['id_commande'];
$req = $bdd->prepare('SELECT * FROM commandes as a LEFT JOIN livraisons as b ON a.id_livraison = b.id_livraison WHERE a.id_commande = :id_commande');
$req->bindValue('id_commande', $id_commande, PDO::PARAM_INT);
$req->execute();
$commande = $req->fetch();
$temps_livraison = minutes_dates($commande['date_debut'], $commande['date_fin']);
$resultat = array("date_commande" => $commande['date'], "duree_livraison" => $temps_livraison.' minutes', "livreur" => $commande['id_livreur']);
echo json_encode($resultat);
}

?>