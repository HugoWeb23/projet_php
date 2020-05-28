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
		var nom = $(this).closest('.details-produit').find('span').eq(0).text();
		var prix = $(this).closest('.details-produit').find('span').eq(1).text();

		$.ajax({
			url:"ajax/modifierquantite.php",
			method:"post",
			data: {id_produit:id_produit},
			success:function(data)
			{
				ajouterProduit('produit', 'produit', 0, 0, 'validerQuantite', 'supprimer', id_produit, nom, prix);
			}
		});
	});

	$('.menu-apercu').on('click', '.validerQuantite', function(){
		
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
					totalCommande();
				}
			});
		});	
	 

		$('.menu-apercu').on('click', '.supprimer', function() {
			var id_produit = $(this).data('produit');
			$.ajax({
				url:"ajax/modifierquantite.php",
				method:"post",
				data:{delete:id_produit}
			});
			$(this).parent().remove();
			totalCommande();
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
			dataType: "json",
			data:{nom:nom, prix:prix, etat:etat},
			success:function(data)
			{
					if(data.type == 'erreur') {
					$('#resultat-menu').html('<h2 class="message-erreur">'+data.message+'</h2>').fadeIn('slow');
					} else if(data.type == 'succes') {
					$('#resultat-menu').html('<h2 class="message-confirmation">'+data.message+'</h2>').fadeIn('slow');
					$('.apercu-menus').remove();
					}
					$('#resultat-menu').delay(3000).fadeOut('slow');
					$('#creerMenu').css('opacity', '1');
					$('.loader').hide();
					$('#creerMenu').find('input[type="text"], input[type="number"]').val('');
					$('#creer_menu').val('Créer le menu');
					totalCommande();
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
						$('#prix_menu span').text(prix+ ' €');
						diffMenu();
				}
			});
			 }
			
			return false;
			});

			$('.modifiermenu-ajouter-produit').on('click', function() {

				var id_produit = $(this).data('id_produit');
				var id_menu = $(this).data('id_menu');
				var nom = $(this).closest('.details-produit').find('span').eq(0).text();
				var prix = $(this).closest('.details-produit').find('span').eq(1).text();
		
				$.ajax({
					url:"ajax/modifiermenu.php",
					method:"post",
					data:{action:'ajouter_produit', id_produit:id_produit, id_menu:id_menu},
					success:function(data)
					{
						ajouterProduit('produit', 'produit', 'menu', id_menu, 'produit_quantite', 'supprimer_produit', id_produit, nom, prix);
						diffMenu();
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

		$('.menu-apercu').on('click', '.produit_quantite', function(){
		
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
					totalCommande();
					diffMenu();
				}
			});
		});
		
		$('.menu-apercu').on('click', '.supprimer_produit', function(){ 
			var produit = $(this).data('produit');
			var id_menu = $(this).data('menu');

			$.ajax({
				url:"ajax/modifiermenu.php",
				method:"post",
				data: {action:'supprimer_produit', produit:produit, id_menu:id_menu}
			});
			$(this).parent().remove();
			totalCommande();
			diffMenu();
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
			$('#creerCommande, #modifierCommande').find("input[type='text']").each(function() {
				$(this).val('');
			});

		   });

		   $("input[name='type']").each(function(){
			if($("input[id=2]").prop('checked') == true){ 
			disableinput('adresse', true);
			disableinput('table', false);
			disableinput('contact', true);
			}
			if($("input[id=1]").prop('checked') == true){ 
				disableinput('adresse', false);
				disableinput('table', true);
				disableinput('contact', false);
				$('#table').val('0');
			}
			if($("input[id=3]").prop('checked') == true){ 
				disableinput('adresse', true);
				disableinput('table', true);
				disableinput('contact', false);
				$('#table').val('0');
			}
		
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
				$('#table').val('0');
			}
			if($(this).val() == 3) {
				disableinput('adresse', true);
				disableinput('table', true);
				disableinput('contact', false);
				$('#table').val('0');
			}
		   });

		   function ajouterProduit(type, data, dataSup, dataSupValue, up, supp, id, nom, prix) {
			switch(type) {
			case 'produit':
			type = 'apercu-produits';
			break;
			case 'menu':
			type = 'apercu-menus';
			break;
			}

			var compteur_id = [];
			$('.'+type+'').each(function() { 
			var id_i = $(this).find('.'+up+'').data(''+data+'');
			compteur_id.push(id_i);
			});

			if(compteur_id.includes(id) == false) {
				$('<div class="'+type+'"><div class="libelle">'+nom+'<div class="quantite">Quantité : <input type="text" class="quantite_saisie" value="1"></div><input type="button" class="'+up+'" data-'+data+'="'+id+'" '+(dataSup.length < 0 ? '' : 'data-'+dataSup+'="'+dataSupValue+'"')+' value="Valider quantité"></div><div class="prix" data-prix="'+prix+'">'+prix+' €</div><input type="button" class="'+supp+'" data-'+data+'="'+id+'" '+(dataSup.length < 0 ? '' : 'data-'+dataSup+'="'+dataSupValue+'"')+' value="supprimer"></div>').insertBefore('.afficher-total');
				} else {
				$('.'+type+'').each(function() { 
				if($(this).find('.'+up+'').data(''+data+'') == id) {
				var num =+ $(this).find('.quantite_saisie').val() + 1;
				if(num <= 30 ) {
				$(this).find('.quantite_saisie').val(num);
				}
				}
				});
				}
				totalCommande();
		   }

		   $('.commandeAjouterMenu').on('click', function() {

			var id_menu = $(this).data('menu_id');
			var nom = $(this).closest('.details-produit').find('span').eq(0).text();
			var prix = $(this).closest('.details-produit').find('span').eq(1).text();
	
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data: {action:'ajouter_menu', id_menu:id_menu},
				success:function(data)
				{
				ajouterProduit('menu', 'produit', 0, 0, 'menuCommandeQuantite', 'supprimerMenuCommande', id_menu, nom, prix);
				}
			});
		});

		$('.commandeAjouterProduit').on('click', function() {

			var id_produit = $(this).data('produit_id');
			var nom = $(this).closest('.details-produit').find('span').eq(0).text();
			var prix = $(this).closest('.details-produit').find('span').eq(1).text();
	
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data: {action:'ajouter_produit', id_produit:id_produit},
				success:function(data)
				{
					ajouterProduit('produit', 'produit', 0, 0,'commandeProduitQuantite', 'commandeSupprimerProduit', id_produit, nom, prix);
				}
			});
		});

		   $('.menu-apercu').on('click', '.menuCommandeQuantite', function(){
		
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
					totalCommande();
				}
			});
		});

		$('.menu-apercu').on('click', '.commandeProduitQuantite', function(){
		
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
				totalCommande();
			}
		});
	});
		
		$('.menu-apercu').on('click', '.supprimerMenuCommande', function() {
			var id_menu = $(this).data('produit');
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data:{action:'supprimer_menu', delete:id_menu}
			});
			$(this).parent().remove();
			totalCommande();
		});


		$('.menu-apercu').on('click', '.commandeSupprimerProduit', function() {
			var produit = $(this).data('produit');
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data:{action:'supprimer_produit', delete:produit}
			});
			$(this).parent().remove();
			totalCommande();
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
		var commentaire = $(this).find('#commentaire').val();
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
		dataType:"json",
		data:{action:'creer_commande', id_client:id_client, tel_fixe:tel_fixe, gsm:gsm, email:email, type_commande:type_commande, table:table, rue:rue, numero:numero, code_postal:code_postal, ville:ville, pays:pays, commentaire:commentaire},
		success:function(data)
		{
		if(data.type == 'succes') {
		$('#resultat-commande').html('<h2 class="message-confirmation">'+data.message+'</h2>').fadeIn('slow');
		$('.apercu-produits, .apercu-menus').remove();
		totalCommande();
		} else if(data.type == 'erreur') {
		$('#resultat-commande').html('<h2 class="message-erreur">'+data.message+'</h2>').fadeIn('slow');
		}
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

		$(document).on('click', '.cloturer_commande', function() { 
			var id_commande = $(this).data('id_commande');
			$(this).parent().parent().remove();

		});

		$(document).on('click', '.commande_etat', function() { 
			var id_commande = $(this).data('id_commande');
			var id_produit = $(this).data('id_produit');
			$(this).parent().append('<font color="green">Prêt !</font>');
			$(this).remove();
			$.ajax({
				url:"ajax/affichercommandes.php",
				method:"post",
				data:{action:'etat_produit', id_commande:id_commande, id_produit:id_produit},
			});
			
		});

		$(document).on('click', '.commande_etat_menu', function() { 
			var id_commande = $(this).data('id_commande');
			var id_menu = $(this).data('id_menu');
			$(this).parent().append('<font color="green">Prêt !</font>');
			$(this).remove();
			$.ajax({
				url:"ajax/affichercommandes.php",
				method:"post",
				data:{action:'etat_menu', id_commande:id_commande, id_menu:id_menu},
			});
			
		});

		$('#modifierCommande').submit(function() { 
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
			var id_commande = $(this).data('id_commande');
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
			var commentaire = $(this).find('#commentaire').val();
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
			url:"ajax/modifiercommande.php",
			method:"post",
			dataType:"json",
			data:{action:'modifier_commande', id_commande:id_commande, id_client:id_client, tel_fixe:tel_fixe, gsm:gsm, email:email, type_commande:type_commande, table:table, rue:rue, numero:numero, code_postal:code_postal, ville:ville, pays:pays, commentaire:commentaire},
			success:function(data)
			{
			if(data.type == 'succes') {
			$('#resultat-commande').html('<h2 class="message-confirmation">'+data.message+'</h2>').fadeIn('slow');
			} else if(data.type == 'erreur') {
			$('#resultat-commande').html('<h2 class="message-erreur">'+data.message+'</h2>').fadeIn('slow');
			}
			$('#modifierCommande').css('opacity', '1');
			$('.loader').hide();
			}
			});
			}
			return false;
			});

			function totalCommande() {
			var total_commande = 0;
			var quantite = 0;
			$('.prix').each(function() {
			var prix = $(this).data('prix');
			var quantite = $(this).parent().find('.quantite_saisie').val();
			total_produit = (prix * quantite);
			total_commande += total_produit;
			});
			$('.total span').text(total_commande+' €');
			}

			function diffMenu() {
			var prix_menu = $('#prix_menu span').text().replace(/[^\d]/g, "");
			var total_produits = $('.total span').text().replace(/[^\d]/g, "");
			var diff = total_produits - prix_menu;
			$('#diff span').text(Math.abs(diff)+ ' €');
			}

		$('.menu-apercu').on('click', '.commandeMenuQuantite', function() {
			var id_menu = $(this).data('menu');
			var id_commande = $(this).data('commande');
			var quantite = $(this).parent().find('.quantite_saisie').val();
			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data:{action:'commande_quantite_menu', id_menu:id_menu, id_commande:id_commande, quantite:quantite},
				success:function(data)
				{
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
					totalCommande();
				}
			});
		});

		$('.menu-apercu').on('click', '.commandeSupprimerMenu', function() {
			var id_menu = $(this).data('menu');
			var id_commande = $(this).data('commande');
			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data:{action:'commande_supprimer_menu', id_menu:id_menu, id_commande:id_commande}
			});
			$(this).parent().remove();
			totalCommande();
		});

		$('.menu-apercu').on('click', '.modifProduitQuantite', function() {
			var id_produit = $(this).data('produit');
			var id_commande = $(this).data('commande');
			var quantite = $(this).parent().find('.quantite_saisie').val();
			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data:{action:'commande_quantite_produit', id_produit:id_produit, id_commande:id_commande, quantite:quantite},
				success:function(data)
				{
					$('#resultat').html(data).fadeIn('slow');
					$('#resultat').delay(2000).fadeOut('slow');
					totalCommande();
				}
			});
		});

		$('.menu-apercu').on('click', '.supprimerProduit', function() {
			var id_produit = $(this).data('produit');
			var id_commande = $(this).data('commande');
			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data:{action:'commande_supprimer_produit', id_produit:id_produit, id_commande:id_commande}
			});
			$(this).parent().remove();
			totalCommande();
		});

		$('.modifCommandeAjouterMenu').on('click', function() {
			var id_menu = $(this).data('menu');
			var id_commande = $(this).data('commande');
			var nom = $(this).closest('.details-produit').find('span').eq(0).text();
			var prix = $(this).closest('.details-produit').find('span').eq(1).text();

			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data: {action:'ajouter_menu', id_menu:id_menu, id_commande:id_commande},
				success:function(data)
				{
					ajouterProduit('menu', 'menu', 'commande', id_commande, 'commandeMenuQuantite', 'commandeSupprimerMenu', id_menu, nom, prix);
				}
			});
		});

		$('.modifCommandeAjouterProduit').on('click', function() {
			var id_produit = $(this).data('produit');
			var id_commande = $(this).data('commande');
			var nom = $(this).closest('.details-produit').find('span').eq(0).text();
			var prix = $(this).closest('.details-produit').find('span').eq(1).text();
			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data: {action:'ajouter_produit', id_produit:id_produit, id_commande:id_commande},
				success:function(data)
				{
				ajouterProduit('produit', 'produit', 'commande', id_commande, 'modifProduitQuantite', 'supprimerProduit', id_produit, nom, prix);
				}
			});
		});
		var id = 1;
		$(document).on('click', '#creer_categorie', function() { 
		$('table').append('<tr><td><input class="test" type="text"></td><td><textarea class="test2"></textarea></td><td><input type="button" value="Créer" class="ok"> <input type="button" value="Annuler" class="annuler"></td></tr>');
		id++;	
		return false;
		});
		$(document).on('click', '.ok', function() {
		var nom = $(this).parent().parent().find('.test').val();
		var description = $(this).parent().parent().find('.test2').val();
		if(nom.length == 0 || description.length == 0) {
			alert('Certains champs sont vides !');
		} else {
			$.ajax({
				url:"ajax/gestioncategories.php",
				method:"post",
				data:{action:'creer', nom:nom, description:description},
				success:function(data) {
					$('table').append('<tr><td>'+nom+'</td><td>'+description+'</td><td><a class="editer" data-id="'+data+'" href="#">Éditer</a> - <a data-id="'+data+'" class="supprimer_categorie" href="#">Supprimer</a></td></tr>');
				}
			});
			$(this).closest('tr').remove();
			
		}
		 });

		 $(document).on('click', '.supprimer_categorie', function() {
			id_categorie = $(this).data('id');
			$(this).closest('tr').remove();
			$.ajax({
			url:"ajax/gestioncategories.php",
			method:"post",
			dataType:"json",
			data:{action:'supprimer', id_categorie:id_categorie},
			success:function(data) {
			if(data.type == 'succes'){
			} else if(data.type == 'erreur') {
			alert(data.message);
			}
			}
			});
		 });

		 $(document).on('click', '.annuler', function() {
		var id_categorie = $(this).data('id');
		var nom = $(this).closest('tr').find('input[type="text"]').val();
		var description = $(this).closest('tr').find('td').eq(1).text();
		if(nom.length == 0 || description.length == 0) {
		$(this).closest('tr').remove();
		}
		$(this).closest('tr').html('<td>'+nom+'</td><td>'+description+'</td><td><a class="editer" data-id="'+id_categorie+'" href="#">Éditer</a> - <a data-id="'+id_categorie+'" class="supprimer_categorie" href="#">Supprimer</a></td>');
		 });

		 $(document).on('click', '.editer', function() {
			var id_categorie = $(this).data('id');
			var nom = $(this).closest('tr').find('td').eq(0).text();
			var description = $(this).closest('tr').find('td').eq(1).text();
			$(this).closest('tr').html('<td><input type="text" class="test" value="'+nom+'"></td><td><textarea class="test2">'+description+'</textarea></td><td><input type="button" data-id="'+id_categorie+'" value="Modifier" class="modifier"> <input type="button" data-id="'+id_categorie+'" value="Annuler" class="annuler"></td>');
			
		 });

		 $(document).on('click', '.modifier', function() {
			var id_categorie = $(this).data('id');
			var nom = $(this).closest('tr').find('input[type="text"]').val();
			var description = $(this).closest('tr').find('textarea').val();

			$.ajax({
				url:"ajax/gestioncategories.php",
				method:"post",
				dataType:"json",
				data:{action:'modifier', id_categorie:id_categorie, nom:nom, description:description},
				success:function(data) {
				if(data.type == 'succes') {
				$('.modifier').closest('tr').html('<td>'+nom+'</td><td>'+description+'</td><td><a class="editer" data-id="'+id_categorie+'" href="#">Éditer</a> - <a data-id="'+id_categorie+'" class="supprimer_categorie" href="#">Supprimer</a></td>');
				} else if(data.type == 'erreur') {
				alert(data.message);
				$('.modifier').parent().parent().css("background", "red");
				}
				}
		 });
		
	});
});

	