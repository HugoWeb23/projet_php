<?php

session_start();

require('../config.php');

if(isset($_POST['action']) && $_POST['action'] == 'afficher_livraisons') {

$req = $bdd->prepare('SELECT *, b.id_livraison as id_livraison FROM commandes as a INNER JOIN livraisons as b ON a.id_livraison = b.id_livraison WHERE a.etat = 1 AND a.type = 1 AND b.etat = 1 AND b.id_livreur is null ORDER BY a.id_commande');
$req->execute();
if($req->rowCount() < 1) {
    echo 'Aucun résultat';
} else {

while($afficher = $req->fetch()) {
?>
<div class="contenu_commande">
<div class="titre_commande">
Commande n° <?= $afficher['id_commande']; ?><button class="prendre_commande" data-id_livraison="<?= $afficher['id_livraison']; ?>">Prendre en charge</button>
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
}

if(isset($_POST['action']) && $_POST['action'] == 'details_commande') {
$id_commande = $_POST['id_commande'];
?>
<div class="produit">
<?php
$req4 = $bdd->prepare('SELECT a.quantite, a.etat, b.nom, a.id_menu as id_menu FROM commandes_menus as a INNER JOIN menus as b ON a.id_menu = b.id_menu AND a.id_commande = :commande ORDER BY a.quantite DESC');
$req4->bindValue('commande', $id_commande, PDO::PARAM_INT);
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
$req2->bindValue('commande', $id_commande, PDO::PARAM_INT);
$req2->execute();
while($commande = $req2->fetch()) {
?>
<div class="commande_details_produit"><?= $commande['quantite']; ?> x <?= $commande['libelle']; ?>
</div>
<?php } ?>
</div>
<?php 
}

// Prendre en charge une livraison

if(isset($_POST['action']) && $_POST['action'] == 'prendre_commande') {
$id_livraison = $_POST['id_livraison'];
$req = $bdd->prepare('SELECT id_livreur FROM livraisons WHERE id_livraison = :id_livraison');
$req->bindValue('id_livraison', $id_livraison, PDO::PARAM_INT);
$req->execute();
$livraison = $req->fetch();
if($livraison['id_livreur'] == null) {
$date = date('Y-m-d H:i:s');
$req = $bdd->prepare('UPDATE livraisons SET etat = 2, date_debut = :date_debut, id_livreur = :id_livreur WHERE id_livraison = :id_livraison');
$req->bindValue('date_debut', $date, PDO::PARAM_STR);
$req->bindValue('id_livreur', $personnel['id_personnel'], PDO::PARAM_INT);
$req->bindValue('id_livraison', $id_livraison, PDO::PARAM_INT);
$req->execute() or die(print_r($req->errorInfo(), TRUE));
$message = array("type" => "succes");
} else {
$message = array("type" => "erreur", "message" => "Cette livraison a déjà été traitée par un livreur");
}
echo json_encode($message);
}

?>