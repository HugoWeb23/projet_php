<?php

session_start();

require('config.php');
verif_connexion();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="js/jquery.js"></script>
<script src="js/jquery-ui/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
<link rel="icon" href="images/favicon.ico"/>
<link href="css/styles.css" rel="stylesheet">
<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
<script>
$(document).ready(function() { 

    afficher_commandes();

   function afficher_commandes() {
    $.ajax({
				url:"ajax/affichercommandes.php",
				method:"post",
				data:{action:'afficher_commandes'},
				success:function(data)
				{
					$('#liste_commandes').html(data).fadeIn('slow');
				}
			});
   }

   function commandes_inactives() {
    $.ajax({
				url:"ajax/commandes_inactives.php",
				method:"post",
				data:{action:'afficher_commandes'},
				success:function(data)
				{
					$('#commandes_inactives').html(data).fadeIn('slow');
				}
			});
   }

   $('.nav-commandes a').on('click', function() { 
	$(this).siblings('.active').removeClass('active');
	$(this).addClass('active');
	if($('.nav-commandes a').eq(0).hasClass('active')) {
	$('#commandes_inactives').hide();
	$('#liste_commandes').show();
	afficher_commandes();
	} else if($('.nav-commandes a').eq(1).hasClass('active')) {
	$('#commandes_inactives').show();
	$('#liste_commandes').hide();
	commandes_inactives();
	}
	});

/*
    setInterval(function(){ 

		if($('.nav-commandes a').eq(0).hasClass('active')) {
		afficher_commandes();
		} else  if($('.nav-commandes a').eq(1).hasClass('active')) {
		commandes_inactives();
		}
	   }, 10000);
	   */
    
});
</script>
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des commandes</h1>
</div>
<div class="nav-commandes">
<a href="#" class="active">Commandes actives</a>
<a href="#">Commandes clôturées</a>
</div>
<div id="liste_commandes"></div>
<div id="commandes_inactives"></div>
</div>
</body>
</html>