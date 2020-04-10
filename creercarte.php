<?php

session_start();

require('config.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/styles.css" rel="stylesheet">
<title><?= $nom_site ?></title>
</head>
<body>
<?php include('header.php'); ?>
<div class="conteneur">
<div class="titre-page">
<h1>Créer une carte de fidélité</h1>
<a href="creerproduit">Gestion des cartes</a>
</div>
<div class="contenu">
<form action='' method='post'>
<input type="text" name="txtCountry" id="txtCountry" class="typeahead">
    </form>
<script type="text/javascript">
$(document).ready(function () {
        $('#txtCountry').typeahead({
            source: function (query, result) {
                $.ajax({
                    url: "ajax/clients_cartes.php",
					data: 'query=' + query,            
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
                    }
                });
            }
        });
    });
        </script>
<input class="boutton-rouge" type="submit" name="valider" value="Créer la carte">
</div>
</body>
</html>