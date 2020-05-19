<?php

session_start();

require('config.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Script -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="js/functions.js"></script>
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