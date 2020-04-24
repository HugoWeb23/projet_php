// Recherche employés

$(document).ready(function(){
	function load_data(query)
	{
		$.ajax({
			url:"ajax/recherche_employes.php",
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

$(document).ready(function(){
	function load_clients(query)
	{
	
		$.ajax({
			url:"ajax/recherche_clients.php",
			method:"post",
			data:{query:query},
			success:function(data)
			{
				$('#result').html(data);
			}
		});
	}
		$('#rechercher_client').keyup(function(){
			var search = $(this).val();
			if(search != '')
			{
				load_clients(search);
			}
			else
			{
				load_clients();			
			}
		});	
});

$(document).ready(function(){

	$('.boutton-ajouter-produit').on('click', function() {

		var id_produit = $(this).data('id');

		$.ajax({
			url:"ajax/modifierquantite.php",
			method:"post",
			data: {id_produit:id_produit},
			success:function(data)
			{
				location.reload();
			}
		});

	});

	$('.validerQuantite').on('click', function(){
		
			var produit = $(this).data('produit');
			var quantite_saisie = $(this).parent().find('.quantite_saisie').val();
  
		
			$.ajax({
				url:"ajax/modifierquantite.php",
				method:"post",
				data: {produit:produit, quantite_saisie:quantite_saisie},
				success:function(data)
				{
					if(quantite_saisie == 0) {
					$('.validerQuantite').parent().parent().fadeOut(0);
					}
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
				}
			});

		});	

		$('.supprimer').on('click', function() {
			var id_produit = $(this).data('produit');
			$(this).parent().fadeOut(0);
			$.ajax({
				url:"ajax/modifierquantite.php",
				method:"post",
				data:{delete:id_produit},
				success:function(data)
				{
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
				}
			});
			

		});

		$('#creerMenu').submit(function() {
		var nom = $(this).find('input[name=nom]').val();
		var prix = $(this).find('input[name=prix]').val();
		var etat = $(this).find('input[name=etat]:checked').val();
			$(this).css('opacity', '0.3');
			$('.loader').show();
			$('#creer_menu').val('Patientez ...');
		$.ajax({
			url:"ajax/creermenu.php",
			method:"post",
			data:{nom:nom, prix:prix, etat:etat},
			success:function(data)
			{
					$('#resultat-menu').html(data).fadeIn('slow');
					$('#resultat-menu').delay(3000).fadeOut('slow');
					$('#creerMenu').css('opacity', '1');
					$('.loader').hide();
					$('.apercu-produits').fadeOut(0);
					$('#creer_menu').val('Créer le menu');
			}
		});
		
		return false;
		});

			$('#prolongerCarte').change(function(){ 
				var mois = $(this).val();
				var id_client = $('#id_client').val();
				var id_carte = $('#id_carte').val();
				if(mois > 0) {
				$.ajax({
					url:"ajax/carte_fidelite.php",
					method:"post",
					data:{action:'prolonger', mois:mois, id_client:id_client, id_carte:id_carte},
					success:function(data)
					{
						$('#resultat').html(data);
					}
				});
			}
			});

			function points(count, id_client, id_carte) {
				$.ajax({
					url:"ajax/carte_fidelite.php",
					method:"post",
					data:{action:'points', points:count, id_client:id_client, id_carte:id_carte},
				});
			}

			var count = $('#count').data('count');

			$('#pointsUp').on('click', function() { 
				var id_client = $('#id_client').val();
				var id_carte = $('#id_carte').val();
				if(count < 100) {
				count++;
				}
				$("#count").text(count);
				points(count, id_client, id_carte);
			});

			$('#pointsDown').on('click', function() { 
				var id_client = $('#id_client').val();
				var id_carte = $('#id_carte').val();
				if(count > 0) {
					count--;
					}
				$("#count").text(count);
				points(count, id_client, id_carte);
			});

			function supprimer_carte(id_client, id_carte) {
				$.ajax({
					url:"ajax/carte_fidelite.php",
					method:"post",
					data:{action:'supprimer', id_client:id_client, id_carte:id_carte},
					success:function()
					{
						location.reload();
					}
				});
			}

			function creer_carte(id_client, points, duree) {
				$.ajax({
					url:"ajax/carte_fidelite.php",
					method:"post",
					data:{action:'creer', id_client:id_client, points:points, duree:duree},
					success:function()
					{
						location.reload();
					}
				});
			}

			$('#creerCarte').on('click', function() {
				var id_client = $(this).data('client');
				var points = $('#points').val();
				var duree = $('#duree').val();
				creer_carte(id_client, points, duree);
			});
		
		$('#supprimerCarteFidelite').on('click', function() { 
			var id_client = $('#id_client').val();
			var id_carte = $('#id_carte').val();
			if(id_client > 0 && id_carte > 0) {
				if(confirm('Voulez-vous vraiment supprimer cette carte ?')) {
					supprimer_carte(id_client, id_carte);
				}
			}
		});

		function supprimer_menu(id_menu) {
			$.ajax({
				url:"ajax/supprimer_menu.php",
				method:"post",
				data:{action:'delete_menu', id_menu:id_menu},
			});
		}

		$('.supprimer-menu').on('click', function() { 
			var id_menu = $(this).data('id');
			if(confirm('Voulez-vous vraiment supprimer ce menu ?')) {
				supprimer_menu(id_menu);
				$(this).closest('tr').remove();
			}
		});

		$('.produit_quantite').on('click', function(){
		
			var produit = $(this).data('produit');
			var id_menu = $(this).data('menu');
			var quantite_saisie = $(this).parent().find('.quantite_saisie').val();
  
			$.ajax({
				url:"ajax/modifiermenu.php",
				method:"post",
				data: {action:'modif_quantite', produit:produit, quantite_saisie:quantite_saisie, id_menu:id_menu},
				success:function(data)
				{
					if(quantite_saisie == 0) {
					}
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
				}
			});

		});
		
		$('.supprimer_produit').on('click', function(){ 
			var produit = $(this).data('produit');
			var id_menu = $(this).data('menu');

			$(this).parent().fadeOut(0);

			$.ajax({
				url:"ajax/modifiermenu.php",
				method:"post",
				data: {action:'supprimer_produit', produit:produit, id_menu:id_menu},
				success:function(data)
				{
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
				}
			});
		});
	});

	