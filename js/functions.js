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