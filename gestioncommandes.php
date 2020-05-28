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

    setInterval(function(){ 

    afficher_commandes();
       }, 10000);
    
    
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
<div id="liste_commandes"></div>
</div>
</body>
</html>