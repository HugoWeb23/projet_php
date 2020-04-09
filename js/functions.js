// Recherche employés

$(document).ready(function(){
	load_data();
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