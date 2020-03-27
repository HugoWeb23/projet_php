<?php

session_start();

require('config.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link href="css/styles.css" rel="stylesheet">
    <title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Gestion des employés</h1>
</div>
<div class="recherche-employe">
<label for="rechercher">Chercher un compte :</label> <input type="text" name="rechercher" id="rechercher" placeholder="Rechercher un nom, un prénom, une adresse, ...">
</div>
<div id="result"></div>
<script>
$(document).ready(function(){
	load_data();
	function load_data(query)
	{
		$.ajax({
			url:"recherche_employes.php",
			method:"post",
			data:{query:query},
			success:function(data)
			{
				$('#result').html(data);
			}
		});
	}
	
	$('#rechercher').keyup(function(){
		var search = $(this).val();
		if(search != '')
		{
			load_data(search);
		}
		else
		{
			load_data();			
		}
	});
});
</script>
</div>
</body>
</html>
