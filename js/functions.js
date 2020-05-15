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

	$('.creermenu-ajouter-produit').on('click', function() {

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

		$('#modifierMenu').submit(function() {
			var nom = $(this).find('input[name=nom]').val();
			var prix = $(this).find('input[name=prix]').val();
			var etat = $(this).find('input[name=etat]:checked').val();
			var id_menu = $(this).find('#modifier_menu').data('menu_id');
		if(nom.length < 1 || prix < 1) {
			$('#resultat-menu').html("<h2 class=\"message-erreur\">Merci de compléter tous les champs</h2>");
		} else {
				$(this).css('opacity', '0.3');
				$('.loader').show();
				$('#modifier_menu').val('Patientez ...');
			$.ajax({
				url:"ajax/modifiermenu.php",
				method:"post",
				data:{action:'modifier_menu', nom:nom, prix:prix, etat:etat, id_menu:id_menu},
				success:function(data)
				{
						$('#resultat-menu').html(data).fadeIn('slow');
						$('#resultat-menu').delay(3000).fadeOut('slow');
						$('#modifierMenu').css('opacity', '1');
						$('.loader').hide();
						$('#modifier_menu').val('Modifier les informations du menu');
				}
			});
			 }
			
			return false;
			});

			$('.modifiermenu-ajouter-produit').on('click', function() {

				var id_produit = $(this).data('id_produit');
				var id_menu = $(this).data('id_menu');
		
				$.ajax({
					url:"ajax/modifiermenu.php",
					method:"post",
					data:{action:'ajouter_produit', id_produit:id_produit, id_menu:id_menu},
					success:function(data)
					{
						location.reload();
					}
				});
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

			$('#nom_client').autocomplete({
			 source: function( request, reponse ) {
			  $.ajax({
			   url: "ajax/clients_commandes.php",
			   type: 'post',
			   dataType: "json",
			   data: {
				recherche: request.term,action:1
			   },
			   success: function(data) {
				reponse(data);
			   }
			  });
			 },
			 select: function (event, ui) {
			  $(this).val(ui.item.label);
			  var userid = ui.item.value;
			  $('#user_id').val(ui.item.value);
		  
			  $.ajax({
			   url: 'ajax/clients_commandes.php',
			   type: 'post',
			   data: {userid:userid,action:2},
			   dataType: 'json',
			   success:function(reponse){
		   
				var len = reponse.length;
		  
				if(len > 0){
				 var id = reponse[0]['id'];
				 var tel_fixe = reponse[0]['tel_fixe'];
				 var gsm = reponse[0]['gsm'];
				 var email = reponse[0]['email'];
				 var rue = reponse[0]['rue'];
				 var numero = reponse[0]['numero'];
				 var code_postal = reponse[0]['code_postal'];
				 var ville = reponse[0]['ville'];
				 var pays = reponse[0]['pays'];
		  
				 $('#tel_fixe').val(tel_fixe);
				 $('#gsm').val(gsm);
				 $('#email').val(email);
				 $('#rue').val(rue);
				 $('#numero').val(numero);
				 $('#code_postal').val(code_postal);
				 $('#ville').val(ville);
				 $('#pays').val(pays);
		   
				}
			   }
			  });
		  
			  return false;
			 }
			});

			$('#rue').autocomplete({
				source: function( request, reponse ) {
				 $.ajax({
				  url: "ajax/clients_commandes.php",
				  type: 'post',
				  dataType: "json",
				  data: {
				   rue: request.term,action:3
				  },
				  success: function(data) {
				   reponse(data);
				  }
				 });
				},
				select: function (event, ui) {
				 $(this).val(ui.item.label);
				 var adresse = ui.item.value.split(',');
				 var id_adresse = adresse[0];
				 var rue = adresse[1];
				 $('#rue').val(rue);
			 
				 $.ajax({
				  url: 'ajax/clients_commandes.php',
				  type: 'post',
				  data: {id_adresse:id_adresse,action:4},
				  dataType: 'json',
				  success:function(reponse){
			  
				   var len = reponse.length;
			 
				   if(len > 0){
					var id = reponse[0]['id'];
					var code_postal = reponse[0]['code_postal'];
					var ville = reponse[0]['ville'];
					var pays = reponse[0]['pays'];
			 
					$('#code_postal').val(code_postal);
					$('#ville').val(ville);
					$('#pays').val(pays);
				   }
				  }
				 });
			 
				 return false;
				}
			   });

		   function disableinput(type, argument) {
			if(type == 'adresse') {
				$("#rue").prop("disabled", argument);
				$("#ville").prop("disabled", argument);
				$("#numero").prop("disabled", argument);
				$("#code_postal").prop("disabled", argument);
				$("#pays").prop("disabled", argument);
			}
			if(type == 'table') {
				$("#table").prop("disabled", argument);
			}
			if(type == 'contact') {
				$("#tel_fixe").prop("disabled", argument);
				$("#gsm").prop("disabled", argument);
				$("#email").prop("disabled", argument);
			}
		   }

		   $('#viderclient').on('click', function() { 
			$('#nom_client').val('');
			$('#user_id').val('');
			$('#tel_fixe').val('');
			$('#gsm').val('');
			$('#email').val('');
			$('#rue').val('');
			$('#numero').val('');
			$('#code_postal').val('');
			$('#ville').val('');
			$('#pays').val('');

		   });

		   $("input[name='type']").change(function(){
			if($(this).val() == 2) {
				disableinput('adresse', true);
				disableinput('table', false);
				disableinput('contact', true);
			}
			if($(this).val() == 1) {
				disableinput('adresse', false);
				disableinput('table', true);
				disableinput('contact', false);
			}
			if($(this).val() == 3) {
				disableinput('adresse', true);
				disableinput('table', true);
				disableinput('contact', false);
			}
		   });

		   $('.commandeAjouterMenu').on('click', function() {

			var id_menu = $(this).data('menu_id');
	
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data: {action:'ajouter_menu', id_menu:id_menu},
				success:function(data)
				{
					location.reload();
				}
			});
		});

		$('.commandeAjouterProduit').on('click', function() {

			var id_produit = $(this).data('produit_id');
	
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data: {action:'ajouter_produit', id_produit:id_produit},
				success:function(data)
				{
					location.reload();
				}
			});
		});

		   $('.menuCommandeQuantite').on('click', function(){
		
			var menu = $(this).data('produit');
			var quantite_saisie = $(this).parent().find('.quantite_saisie').val();
  
		
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data: {action:'menu_quantite', menu:menu, quantite_saisie:quantite_saisie},
				success:function(data)
				{
					if(quantite_saisie == 0) {
					$('.menuCommandeQuantite').parent().parent().fadeOut(0);
					}
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
				}
			});
		});

		$('.commandeProduitQuantite').on('click', function(){
		
			var id_produit = $(this).data('produit');
			var quantite_saisie = $(this).parent().find('.quantite_saisie').val();

		$.ajax({
			url:"ajax/creercommande.php",
			method:"post",
			data: {action:'produit_quantite', id_produit:id_produit, quantite_saisie:quantite_saisie},
			success:function(data)
			{
				if(quantite_saisie == 0) {
				$('.commandeProduitQuantite').parent().parent().fadeOut(0);
				}
				$('#resultat').html(data).fadeIn('slow');
				$('#resultat').delay(2000).fadeOut('slow');
			}
		});
	});
		
		$('.supprimerMenuCommande').on('click', function() {
			var id_menu = $(this).data('produit');
			$(this).parent().fadeOut(0);
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data:{action:'supprimer_menu', delete:id_menu},
				success:function(data)
				{
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
				}
			});
		});


		$('.commandeSupprimerProduit').on('click', function() {
			var produit = $(this).data('produit');
			$(this).parent().fadeOut(0);
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data:{action:'supprimer_produit', delete:produit},
				success:function(data)
				{
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
				}
			});
		});

		$('#creerCommande').submit(function() { 
		function message(type, message) { // type 1 = erreur, type 2 = validation
		switch(type) {
		case 1:
		type = 'erreur';
		break;
		case 2:
		type = 'confirmation';
		}
		$('#resultat-commande').html('<h2 class="message-'+type+'">'+message+'</h2>');
		}
		var check = null;
		var id_client = $(this).find('#user_id').val();
		var tel_fixe = $(this).find('#tel_fixe').val();
		var gsm = $(this).find('#gsm').val();
		var email = $(this).find('#email').val();
		var type_commande = $(this).find('input[name=type]:checked').val();
		var table = $(this).find('#table').val();
		var rue = $(this).find('#rue').val();
		var numero = $(this).find('#numero').val();
		var code_postal = $(this).find('#code_postal').val();
		var ville = $(this).find('#ville').val();
		var pays = $(this).find('#pays').val();
		if(type_commande == 1) {
		if(rue.length < 1 || numero.length < 1 || code_postal.length < 1 || ville.length < 1 || pays.length < 1 || gsm.length < 1 && tel_fixe.length < 1) {
		check = false;
		message(1, 'Merci de remplir tous les champs');
		}
		} else if(type_commande == 2 && table == 0) {
		check = false;
		message(1, 'Merci de choisir une table');
		} else if(type_commande == 3) {
		if(gsm.length < 1 && tel_fixe.length < 1) {
		check = false;
		message(1, 'Les coordonnées de contact sont vides ou incomplètes');
		}
		}
		if(check != false) {
		$(this).css('opacity', '0.3');
		$('.loader').show();
		$.ajax({
		url:"ajax/creercommande.php",
		method:"post",
		data:{action:'creer_commande', id_client:id_client, tel_fixe:tel_fixe, gsm:gsm, email:email, type_commande:type_commande, table:table, rue:rue, numero:numero, code_postal:code_postal, ville:ville, pays:pays},
		success:function(data)
		{
		$('#resultat-commande').html(data).fadeIn('slow');
		$('#creerCommande').css('opacity', '1');
		$('.loader').hide();
		}
		});
		}
		return false;
		});

		function cloturer_commande(id_commande) {
			$.ajax({
				url:"ajax/affichercommandes.php",
				method:"post",
				data:{action:'cloturer_commande', id_commande:id_commande},
			});
		}

		$(document).on('click', '.cloturer_commande', function() { 
			var id_commande = $(this).data('id_commande');
			
				cloturer_commande(id_commande);
				$(this).closest('tr').remove();
			
		});
		
	});

	