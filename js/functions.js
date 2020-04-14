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

	$('.ok').on('click', function(){
		
			var produit = $(this).data('produit');
			var quantite = $(this).data('quantite');
			var quantite_saisie = $(this).parent().find('.quantite').find('.quantite_saisie').val();
  
		
			$.ajax({
				url:"ajax/modifierquantite.php",
				method:"post",
				data: 'produit=' + produit + '&prod_quantite=' + quantite + '&quantite_saisie=' + quantite_saisie,
				success:function(data)
				{
					$('#resultat').html(data);
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
					$('#result').html(data);
				}
			});
			

		});
	});

	