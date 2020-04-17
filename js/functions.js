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

	$('.ajouterProduit').on('click', function() {

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
				$('#creerMenu').val('');
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
		
		$('#supprimerCarteFidelite').on('click', function() { 
			var id_client = $('#id_client').val();
			var id_carte = $('#id_carte').val();
			if(id_client > 0 && id_carte > 0) {
				if(confirm('Voulez-vous vraiment supprimer cette carte ?')) {
					supprimer_carte(id_client, id_carte);
				}
			}
		});
	});

	