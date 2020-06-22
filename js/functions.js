$(document).ready(function(){

	// Menu responsive

	$('#burger').click(function(e) { 
		e.preventDefault();
		$(this).toggleClass('on');
		$('.menu').toggleClass('on');
		$('.fermermenu').toggleClass('on');
	});

	$('.fermermenu').click(function(e) { 
		e.preventDefault();
		$('#burger').toggleClass('on');
		$('.menu').toggleClass('on');
		$(this).toggleClass('on');
	});

	// Page connexion
	$('#connexion').submit(function() { 
	var email = $(this).find('#email').val();
	var mdp = $(this).find('#mdp').val();
	if(email.length == 0 || mdp.length == 0) {
	$('.message-erreur').remove();
	$('.connexion-nom').after('<h2 class="message-erreur">Merci de remplir tous les champs !</h2>');
	return false;
	}
	});
	// Recherche employés
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

	// Vérification de la disponibilité d'une adresse email

	$('.verif-email').on('keyup', function() { 
	var input = $(this);
	var email = $(this).val();
	if(email.length > 7) {
		$.ajax({
		url:"ajax/checkemail.php",
		method:"post",
		dataType:"json",
		data:{action:'email-personnel', email:email},
		success:function(data)
		{
		if(data.dispo == 0) {
		input.css('border', '2px solid red');
		} else if(data.dispo == 1) {
		input.css('border', '2px solid green');
		}
		}
		});
	}
	});

	// Recherche clients

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

	// Créer un compte client

	$('#creerclient').submit(function() { 
	var creerclient = $(this);
	var nom = $(creerclient).find('#nom').val();
	var prenom = $(creerclient).find('#prenom').val();
	var date_naissance = $(creerclient).find('#date_naissance').val();
	var email = $(creerclient).find('#email').val();
	var tel_fixe = $(creerclient).find('#telephone_fixe').val();
	var gsm = $(creerclient).find('#gsm').val();
	var rue = $(creerclient).find('#rue').val();
	var numero = $(creerclient).find('#numero').val();
	var ville = $(creerclient).find('#ville').val();
	var code_postal = $(creerclient).find('#code_postal').val();
	var pays = $(creerclient).find('#pays').val();
	var creer_carte = $(creerclient).find('#creercarte').is(':checked') ? 1 : 0;
	var points = $(creerclient).find('#points').val();
	var expiration = $(creerclient).find('#expiration').val();
	if(nom.length < 1 || prenom.length < 1 || date_naissance.length < 1 || email.length < 1 || rue.length < 1 || numero.length < 1 || code_postal.length < 1 || pays.length < 1 || points.length < 1 || expiration.length < 1 || tel_fixe.length < 1 && gsm.length < 1) {
	$('#messages').html('<h2 class="message-erreur">Merci de remplir tous les champs</h2>');
	$('html, body').animate({ scrollTop:0 }, 300);
	} else {
		$.ajax({
			url:"ajax/creerclient.php",
			method:"post",
			dataType:"json",
			data: {action:'creer_client', nom:nom, prenom:prenom, date_naissance:date_naissance, email:email, telephone_fixe:tel_fixe, gsm:gsm, rue:rue, numero:numero, ville:ville, code_postal:code_postal, pays:pays, creercarte:creer_carte, points:points, expire:expiration},
			success:function(data)
			{
			if(data.type == 'erreur') {
			$('#messages').html('<h2 class="message-erreur">'+data.message+'</h2>');
			$('html, body').animate({ scrollTop:0 }, 300);
			} else if(data.type == 'succes') {
			$('#messages').html('<h2 class="message-confirmation">'+data.message+'</h2>');
			creerclient.find('.infos-perso').find('input[type="text"], input[type="date"]').val('');
			$('html, body').animate({ scrollTop:0 }, 300);
			}
			}
		});
	}
	return false;
	});


	// Modifier un client

	$('#modifierclient').submit(function() { 
		var modifierclient = $(this);
		var id_client = $(modifierclient).data('id_client');
		var nom = $(modifierclient).find('#nom').val();
		var prenom = $(modifierclient).find('#prenom').val();
		var date_naissance = $(modifierclient).find('#date_naissance').val();
		var email_actuelle = $(modifierclient).find('#email').data('email_actuelle');
		var email = $(modifierclient).find('#email').val();
		var tel_fixe = $(modifierclient).find('#telephone_fixe').val();
		var gsm = $(modifierclient).find('#gsm').val();
		var rue = $(modifierclient).find('#rue').val();
		var numero = $(modifierclient).find('#numero').val();
		var ville = $(modifierclient).find('#ville').val();
		var code_postal = $(modifierclient).find('#code_postal').val();
		var pays = $(modifierclient).find('#pays').val();
		if(nom.length < 1 || prenom.length < 1 || date_naissance.length < 1 || email.length < 1 || rue.length < 1 || numero.length < 1 || code_postal.length < 1 || pays.length < 1 || tel_fixe.length < 1 && gsm.length < 1) {
		$('#messages').html('<h2 class="message-erreur">Merci de remplir tous les champs</h2>');
		$('html, body').animate({ scrollTop:0 }, 300);
		} else {
			$.ajax({
				url:"ajax/modifierclient.php",
				method:"post",
				dataType:"json",
				data: {action:'modifier_client', id_client:id_client, nom:nom, prenom:prenom, date_naissance:date_naissance, email_actuelle:email_actuelle, email:email, telephone_fixe:tel_fixe, gsm:gsm, rue:rue, numero:numero, ville:ville, code_postal:code_postal, pays:pays},
				success:function(data)
				{
				if(data.type == 'erreur') {
				$('#messages').html('<h2 class="message-erreur">'+data.message+'</h2>');
				$('html, body').animate({ scrollTop:0 }, 300);
				} else if(data.type == 'succes') {
				$('#messages').html('<h2 class="message-confirmation">'+data.message+'</h2>');
				$('html, body').animate({ scrollTop:0 }, 300);
				}
				}
			});
		}
		return false;
		});

		// Supprimer compte client

		$('#supprimerclient').click(function() {
			var id_client = $(this).data('client');
			if(confirm('Voulez-vous vraiment supprimer ce client ?')) {
				$.ajax({
					url:"ajax/modifierclient.php",
					method:"post",
					dataType:"json",
					data: {action:'supprimer_client', id_client:id_client},
					success:function(data)
					{
					if(data.type == 'succes') {
					alert(data.message);
					window.location = "gestionclients";
					}
					}
				});
			}
		});

		// Créer compte employé

		$('#creeremploye').submit(function() { 
			var creeremploye = $(this);
			var nom = $(creeremploye).find('#nom').val();
			var prenom = $(creeremploye).find('#prenom').val();
			var date_naissance = $(creeremploye).find('#date_naissance').val();
			var tel_fixe = $(creeremploye).find('#tel_fixe').val();
			var gsm = $(creeremploye).find('#gsm').val();
			var rue = $(creeremploye).find('#rue').val();
			var numero = $(creeremploye).find('#numero').val();
			var ville = $(creeremploye).find('#ville').val();
			var code_postal = $(creeremploye).find('#code_postal').val();
			var pays = $(creeremploye).find('#pays').val();
			var fonction = new Array();
			$('.employe-fonction:checked').each(function() {
			fonction.push($(this).val());
			});
			var email = $(creeremploye).find('#email').val();
			var password = $(creeremploye).find('#password').val();
			var confirm_password = $(creeremploye).find('#confirm_password').val();
		
			if(nom.length < 1 || prenom.length < 1 || date_naissance.length < 1 || email.length < 1 || rue.length < 1 || numero.length < 1 || code_postal.length < 1 || pays.length < 1 || password.length < 1 || confirm_password.length < 1 || tel_fixe.length < 1 && gsm.length < 1) {
			$('#messages').html('<h2 class="message-erreur">Merci de remplir tous les champs</h2>');
			$('html, body').animate({ scrollTop:0 }, 300);
			} else {
				$.ajax({
					url:"ajax/creeremploye.php",
					method:"post",
					dataType:"json",
					data: {action:'creer_employe', nom:nom, prenom:prenom, date_naissance:date_naissance, tel_fixe:tel_fixe, gsm:gsm, rue:rue, numero:numero, ville:ville, code_postal:code_postal, pays:pays, fonction:fonction, email:email, password:password, confirm_password:confirm_password},
					success:function(data)
					{
					if(data.type == 'erreur') {
					$('#messages').html('<h2 class="message-erreur">'+data.message+'</h2>');
					$('html, body').animate({ scrollTop:0 }, 300);
					} else if(data.type == 'succes') {
					$('#messages').html('<h2 class="message-confirmation">'+data.message+'</h2>');
					creeremploye.find('.infos-perso, .identifiants').find('input[type="text"], input[type="date"], input[type="password"], input[type="email"]').val('');
					creeremploye.find('.identifiants').find('.employe-fonction:checked').prop('checked', false);
					$('html, body').animate({ scrollTop:0 }, 300);
					}
					}
				});
			}
			return false;
			});

			// Modifier un compte employé

			$('#modifieremploye').submit(function() { 
				var modifieremploye = $(this);
				var id_personnel = $(modifieremploye).data('id_personnel');
				var nom = $(modifieremploye).find('#nom').val();
				var prenom = $(modifieremploye).find('#prenom').val();
				var date_naissance = $(modifieremploye).find('#date_naissance').val();
				var tel_fixe = $(modifieremploye).find('#tel_fixe').val();
				var gsm = $(modifieremploye).find('#gsm').val();
				var rue = $(modifieremploye).find('#rue').val();
				var numero = $(modifieremploye).find('#numero').val();
				var ville = $(modifieremploye).find('#ville').val();
				var code_postal = $(modifieremploye).find('#code_postal').val();
				var pays = $(modifieremploye).find('#pays').val();
				var fonction = new Array();
				$('.employe-fonction:checked').each(function() {
				fonction.push($(this).val());
				});
				var email = $(modifieremploye).find('#email').val();
				var email_actuelle = $(modifieremploye).find('#email').data('email');
				var password = $(modifieremploye).find('#password').val();
				var confirm_password = $(modifieremploye).find('#confirm_password').val();
			
				if(nom.length < 1 || prenom.length < 1 || date_naissance.length < 1 || email.length < 1 || rue.length < 1 || numero.length < 1 || code_postal.length < 1 || pays.length < 1 || tel_fixe.length < 1 && gsm.length < 1) {
				$('#messages').html('<h2 class="message-erreur">Merci de remplir tous les champs</h2>');
				$('html, body').animate({ scrollTop:0 }, 300);
				} else {
					$.ajax({
						url:"ajax/modifieremploye.php",
						method:"post",
						dataType:"json",
						data: {action:'modifier_employe', id_personnel:id_personnel, nom:nom, prenom:prenom, date_naissance:date_naissance, tel_fixe:tel_fixe, gsm:gsm, rue:rue, numero:numero, ville:ville, code_postal:code_postal, pays:pays, fonction:fonction, email:email, email_actuelle:email_actuelle, password:password, confirm_password:confirm_password},
						success:function(data)
						{
						if(data.type == 'erreur') {
						$('#messages').html('<h2 class="message-erreur">'+data.message+'</h2>');
						$('html, body').animate({ scrollTop:0 }, 300);
						} else if(data.type == 'succes') {
						$('#messages').html('<h2 class="message-confirmation">'+data.message+'</h2>');
						modifieremploye.find('input[type="password"]').val('');
						$('html, body').animate({ scrollTop:0 }, 300);
						}
						}
					});
				}
				return false;
				});
	
		$('.creermenu-ajouter-produit').on('click', function() {
			
			var button = $(this);
			var id_produit = $(button).data('id');
			var nom = $(button).closest('.details-produit').find('span').eq(0).text();
			var prix = $(button).closest('.details-produit').find('span').eq(1).text();
	
			$.ajax({
				url:"ajax/modifierquantite.php",
				method:"post",
				data: {id_produit:id_produit},
				success:function(data)
				{
					ajouterProduit('produit', 'produit', 0, 0, 'validerQuantite', 'supprimer', id_produit, nom, prix);
					success(button);
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
					$('html, body').animate({ scrollTop:0 }, 300);
					} else if(data.type == 'succes') {
					$('#resultat-menu').html('<h2 class="message-confirmation">'+data.message+'</h2>').fadeIn('slow');
					$('.apercu-produits').remove();
					$('html, body').animate({ scrollTop:0 }, 300);
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
			$('#resultat-menu').html('<h2 class="message-erreur">Merci de compléter tous les champs</h2>');
		} else {
				$(this).css('opacity', '0.3');
				$('.loader').show();
				$('#modifier_menu').val('Patientez ...');
			$.ajax({
				url:"ajax/modifiermenu.php",
				method:"post",
				dataType:"json",
				data:{action:'modifier_menu', nom:nom, prix:prix, etat:etat, id_menu:id_menu},
				success:function(data)
				{
					if(data.type == 'erreur') {
					$('#resultat-menu').html('<h2 class="message-erreur">'+data.message+'</h2>');
					$('html, body').animate({ scrollTop:0 }, 300);
					} else if(data.type == 'succes') {
						$('#resultat-menu').html('<h2 class="message-confirmation">'+data.message+'</h2>').fadeIn('slow');
						$('html, body').animate({ scrollTop:0 }, 300);
						$('#resultat-menu').delay(3000).fadeOut('slow');
						$('#modifierMenu').css('opacity', '1');
						$('.loader').hide();
						$('#modifier_menu').val('Modifier le menu');
						$('#prix_menu span').text(prix+ ' €');
						diffMenu();
					}
				}
			});
			 }
			
			return false;
			});

			$('.modifiermenu-ajouter-produit').on('click', function() {

				var button = $(this);
				var id_produit = $(button).data('id_produit');
				var id_menu = $(button).data('id_menu');
				var nom = $(button).closest('.details-produit').find('span').eq(0).text();
				var prix = $(button).closest('.details-produit').find('span').eq(1).text();
		
				$.ajax({
					url:"ajax/modifiermenu.php",
					method:"post",
					data:{action:'ajouter_produit', id_produit:id_produit, id_menu:id_menu},
					success:function(data)
					{
						ajouterProduit('produit', 'produit', 'menu', id_menu, 'produit_quantite', 'supprimer_produit', id_produit, nom, prix);
						success(button);
						diffMenu();
					}
				});
			});

			$('.identifiants').on('change', '#prolongerCarte', function(){ 
				var mois = $(this).val();
				var id_client = $(this).closest('.carte-fidelite').find('#supprimerCarteFidelite').data('id_client');
				var id_carte = $(this).closest('.carte-fidelite').find('#supprimerCarteFidelite').data('id_carte');
				if(mois > 0) {
				$.ajax({
					url:"ajax/carte_fidelite.php",
					method:"post",
					dataType:"json",
					data:{action:'prolonger', mois:mois, id_client:id_client, id_carte:id_carte},
					success:function(data)
					{
						$('#resultat').html(data.message);
						$('#expiration span').text(data.date);

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

			

			$('.identifiants').on('click', '#pointsUp', function() { 
				var id_client = $(this).closest('.carte-fidelite').find('#supprimerCarteFidelite').data('id_client');
				var id_carte = $(this).closest('.carte-fidelite').find('#supprimerCarteFidelite').data('id_carte');
				var count = $('#count').text();
				if(count < 100) {
				count++;
				}
				$("#count").text(count);
				points(count, id_client, id_carte);
			});

			$('.identifiants').on('click', '#pointsDown', function() { 
				var id_client = $(this).closest('.carte-fidelite').find('#supprimerCarteFidelite').data('id_client');
				var id_carte = $(this).closest('.carte-fidelite').find('#supprimerCarteFidelite').data('id_carte');
				var count = $('#count').text();
				if(count > 0) {
					count--;
					}
				$("#count").text(count);
				points(count, id_client, id_carte);
			});

			function supprimer_carte(supprimer, id_client, id_carte) {
				$.ajax({
					url:"ajax/carte_fidelite.php",
					method:"post",
					data:{action:'supprimer', id_client:id_client, id_carte:id_carte},
					success:function()
					{
					supprimer.closest('.carte-fidelite').remove();
					$('.identifiants h2').after('<div class="creer-carte"><p>Ce client ne possède pas de carte de fidélité</p><h3>Créer une carte :</h3><label for="points">Points :</label><input type="text" id="points" value="0"><label for="duree">Durée de validité</label><select id="duree"><option value="1">1 mois</option><option value="2">2 mois</option><option value="3">3 mois</option><option value="4">4 mois</option><option value="5">5 mois</option><option value="6" selected>6 mois</option><option value="12">1 an</option><option value="24">2 ans</option></select><input type="button" id="creerCarte" data-client="'+id_client+'" value="Créer la carte"></div>');
					}
				});
			}

			function creer_carte(creercarte, id_client, points, duree) {
				$.ajax({
					url:"ajax/carte_fidelite.php",
					method:"post",
					dataType:"json",
					data:{action:'creer', id_client:id_client, points:points, duree:duree},
					success:function(data)
					{
					creercarte.closest('.creer-carte').remove();
					$('.identifiants h2').after('<div class="carte-fidelite"><div id="resultat"></div><p>Numéro : '+data.n_carte+'</p><p>Points : <span id="count" data-count="'+data.points+'">'+data.points+'</span> <a href="#" id="pointsUp">+</a> <a href="#" id="pointsDown">-</a></p><p id="expiration">Date d\'expiration : <span>'+data.expiration+'</span></p><p><label for="prolongerCarte">Prolonger :</label> <select name="mois" id="prolongerCarte"><option value="0">Non</option><option value="1">1 mois</option><option value="2">2 mois</option><option value="3">3 mois</option><option value="6">6 mois</option><option value="12">1 an</option><option value="24">2 ans</option></select><p><label for="supprimerCarteFidelite">Supprimer :</label><input type="button" data-id_client="'+data.id_client+'" data-id_carte="'+data.n_carte+'" id="supprimerCarteFidelite" value="Supprimer"></div>');
					}
				});
			}

			$('.identifiants').on('click', '#creerCarte', function() {
				var creercarte = $(this);
				var id_client = $(this).data('client');
				var points = $('#points').val();
				var duree = $('#duree').val();
				creer_carte(creercarte, id_client, points, duree);
			});
		
		$('.identifiants').on('click', '#supprimerCarteFidelite', function() { 
			var supprimer = $(this);
			var id_client = supprimer.data('id_client');
			var id_carte = supprimer.data('id_carte');
			if(id_client > 0 && id_carte > 0) {
				if(confirm('Voulez-vous vraiment supprimer cette carte ?')) {
					supprimer_carte(supprimer, id_client, id_carte);
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
				$('<div class="'+type+'"><div class="libelle">'+nom+'<div class="quantite">Quantité : <input type="text" class="quantite_saisie" value="1"></div></div><div class="prix" data-prix="'+prix+'">'+prix+' €</div><input type="button" class="boutton-quantite '+up+'" data-'+data+'="'+id+'" '+(dataSup.length < 0 ? '' : 'data-'+dataSup+'="'+dataSupValue+'"')+' value="Valider quantité"><input type="button" class="boutton-supprimer-produit '+supp+'" data-'+data+'="'+id+'" '+(dataSup.length < 0 ? '' : 'data-'+dataSup+'="'+dataSupValue+'"')+' value="Supprimer"></div>').insertBefore('.afficher-total');
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

		   function success(click) {
			if(click.hasClass('click')) {
			return false;
			} else {
			click.toggleClass('click');
			setTimeout(function() {
			click.toggleClass('click');
			}, 1500);
			}
		   }

		   $('.commandeAjouterMenu').on('click', function() {

			var button = $(this);
			var id_menu = $(button).data('menu_id');
			var nom = $(button).closest('.details-produit').find('span').eq(0).text();
			var prix = $(button).closest('.details-produit').find('span').eq(1).text();
	
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data: {action:'ajouter_menu', id_menu:id_menu},
				success:function(data)
				{
				ajouterProduit('menu', 'produit', 0, 0, 'menuCommandeQuantite', 'supprimerMenuCommande', id_menu, nom, prix);
				success(button);
				}
			});
		});

		$('.commandeAjouterProduit').on('click', function() {

			var button = $(this);
			var id_produit = $(button).data('produit_id');
			var nom = $(button).closest('.details-produit').find('span').eq(0).text();
			var prix = $(button).closest('.details-produit').find('span').eq(1).text();
	
			$.ajax({
				url:"ajax/creercommande.php",
				method:"post",
				data: {action:'ajouter_produit', id_produit:id_produit},
				success:function(data)
				{
					ajouterProduit('produit', 'produit', 0, 0,'commandeProduitQuantite', 'commandeSupprimerProduit', id_produit, nom, prix);
					success(button);
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
		$('html, body').animate({ scrollTop: 0 }, 300);
		}
		var check = null;
		var commande = $(this);
		var id_client = $(commande).find('#user_id').val();
		var tel_fixe = $(commande).find('#tel_fixe').val();
		var gsm = $(commande).find('#gsm').val();
		var email = $(commande).find('#e-mail').val();
		var type_commande = $(commande).find('input[name=type]:checked').val();
		var table = $(commande).find('#table').val();
		var rue = $(commande).find('#rue').val();
		var numero = $(commande).find('#numero').val();
		var code_postal = $(commande).find('#code_postal').val();
		var ville = $(commande).find('#ville').val();
		var pays = $(commande).find('#pays').val();
		var commentaire = $(commande).find('#commentaire').val();
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
		$(commande).css('opacity', '0.3');
		$('.loader').show();
		$.ajax({
		url:"ajax/creercommande.php",
		method:"post",
		dataType:"json",
		data:{action:'creer_commande', id_client:id_client, tel_fixe:tel_fixe, gsm:gsm, email:email, type_commande:type_commande, table:table, rue:rue, numero:numero, code_postal:code_postal, ville:ville, pays:pays, commentaire:commentaire},
		success:function(data)
		{
		if(data.type == 'succes') {
		message(2, data.message);
		$('.apercu-produits, .apercu-menus').remove();
		totalCommande();
		$(commande).find('input[type="text"], textarea').val('');
		$()
		} else if(data.type == 'erreur') {
		message(1, data.message);
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
		

		$('#liste_commandes').on('click', '.cloturer_commande', function() { 
			var id_commande = $(this).data('id_commande');
			
				cloturer_commande(id_commande);
				$(this).parent().parent().remove();
				var x = $('#liste_commandes').find('.contenu_commande').html();
				if(x == undefined) {
				$('#liste_commandes').html("Il n'y a aucune commande pour le moment");
				}
			
		});

		$('#commandes_inactives').on('click', '.retablir_commande', function() { 
			var id_commande = $(this).data('id_commande');
			var commande = (this);
			$.ajax({
				url:"ajax/commandes_inactives.php",
				method:"post",
				dataType:"json",
				data:{action:'retablir_commande', id_commande:id_commande},
				success:function(data) {
				if(data.type == 'erreur') {
				alert(data.message);
				} else if(data.type == 'succes') {
				$(commande).parent().parent().remove();
				}
				}
			});
			
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
			$('html, body').animate({ scrollTop:0 }, 300);
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
			message(2, data.message);
			} else if(data.type == 'erreur') {
			message(1, data.message);
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

			var button = $(this);
			var id_menu = $(button).data('menu');
			var id_commande = $(button).data('commande');
			var nom = $(button).closest('.details-produit').find('span').eq(0).text();
			var prix = $(button).closest('.details-produit').find('span').eq(1).text();

			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data: {action:'ajouter_menu', id_menu:id_menu, id_commande:id_commande},
				success:function(data)
				{
					ajouterProduit('menu', 'menu', 'commande', id_commande, 'commandeMenuQuantite', 'commandeSupprimerMenu', id_menu, nom, prix);
					success(button);
				}
			});
		});

		$('.modifCommandeAjouterProduit').on('click', function() {

			var button = $(this);
			var id_produit = $(button).data('produit');
			var id_commande = $(button).data('commande');
			var nom = $(button).closest('.details-produit').find('span').eq(0).text();
			var prix = $(button).closest('.details-produit').find('span').eq(1).text();
			$.ajax({
				url:"ajax/modifiercommande.php",
				method:"post",
				data: {action:'ajouter_produit', id_produit:id_produit, id_commande:id_commande},
				success:function(data)
				{
				ajouterProduit('produit', 'produit', 'commande', id_commande, 'modifProduitQuantite', 'supprimerProduit', id_produit, nom, prix);
				success(button);
				}
			});
		});
		var id = 1;
		$(document).on('click', '#creer_categorie', function() { 
		$('table').append('<tbody><tr><td data-label="Nom"><input class="test" type="text"></td><td data-label="Description"><textarea class="test2"></textarea></td><td data-label="Actions"><input type="button" value="Créer" class="ok"> <input type="button" value="Annuler" class="annuler"></td></tr><tbody>');
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
				$('table').append('<tbody><tr><td data-label="Nom">'+nom+'</td><td data-label="Description">'+description+'</td><td data-label="Actions"><a class="editer" data-id="'+data+'" href="#">Éditer</a> - <a data-id="'+data+'" class="supprimer_categorie" href="#">Supprimer</a></td></tr></tbody>');
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
		var boutton = $(this).closest('tr').find('input[type="button"]').eq(0).val();
		if(boutton == 'Créer') {
		$(this).closest('tr').remove();
		} else if(boutton == 'Modifier') {
		$(this).closest('tr').html('<td data-label="Nom">'+nom+'</td><td data-label="Description">'+description+'</td><td data-label="Actions"><a class="editer" data-id="'+id_categorie+'" href="#">Éditer</a> - <a data-id="'+id_categorie+'" class="supprimer_categorie" href="#">Supprimer</a></td>');
		}
		 });

		 $(document).on('click', '.editer', function() {
			var id_categorie = $(this).data('id');
			var nom = $(this).closest('tr').find('td').eq(0).text();
			var description = $(this).closest('tr').find('td').eq(1).text();
			$(this).closest('tr').html('<td data-label="Nom"><input type="text" class="test" value="'+nom+'"></td><td data-label="Description"><textarea class="test2">'+description+'</textarea></td><td data-label="Actions"><input type="button" data-id="'+id_categorie+'" value="Modifier" class="modifier"> <input type="button" data-id="'+id_categorie+'" value="Annuler" class="annuler"></td>');
			
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
				$('.modifier').closest('tr').html('<td data-label="Nom">'+nom+'</td><td data-label="Description">'+description+'</td><td data-label="Actions"><a class="editer" data-id="'+id_categorie+'" href="#">Éditer</a> - <a data-id="'+id_categorie+'" class="supprimer_categorie" href="#">Supprimer</a></td>');
				} else if(data.type == 'erreur') {
				alert(data.message);
				$('.modifier').parent().parent().css("background", "red");
				}
				}
		 });
		
	});

	$('#liste_livraisons').on('click', '.prendre_commande', function() { 
	var id_livraison = $(this).data('id_livraison');
	var button = $(this);

	$.ajax({
		url:"ajax/afficherlivraisons.php",
		method:"post",
		dataType:"json",
		data:{action:'prendre_commande', id_livraison:id_livraison},
		success:function(data) {
		if(data.type == 'succes') {
		var id_livraison = button.data('id_livraison');
		var id_commande = button.data('id_commande');
		var commande_livraison = button.closest('.contenu_commande').find('.commande_livraison').html();
		var commande_contact = button.closest('.contenu_commande').find('.commande_contact').html();
		$('<div class="contenu_commande"><div class="titre_commande">Commande n° '+id_commande+' <button class="btn-vert terminer_livraison" data-id_livraison="'+id_livraison+'">Livraison effectuée</button></div><button class="btn-gris-transparent livraison-details-produits" data-id_commande="'+id_commande+'">Détails commande</button><div class="commande_produits"><div class="commande_livraison">'+commande_livraison+'</div><div class="commande_contact">'+commande_contact+'</div></div></div>').insertBefore('.attentelivraisons');
		button.closest('.contenu_commande').remove();
		var x = $('#liste_livraisons').find('.contenu_commande').html();
		if(x == undefined) {
		$('#liste_livraisons').html("Il n'y a aucune livraison en attente pour le moment");
		}
		} else if(data.type == 'erreur') {
		alert(data.message);
		button.closest('.contenu_commande').remove();
		}
		}
	});
	
	});

	$(document).on('click', '.livraison-details-produits', function() {
	var button = $(this);
	var id_commande = button.data('id_commande');
	if(button.val() == 1) {
	button.closest('.contenu_commande').find('.commande_produits').find('.produit').remove();
	button.val(0).html('Détails commande');
	} else {
		$.ajax({
			url:"ajax/afficherlivraisons.php",
			method:"post",
			data:{action:'details_commande', id_commande:id_commande},
			success:function(data) {
			button.closest('.contenu_commande').find('.commande_produits').prepend(data);
			button.val(1).html('Masquer les détails');
			
			}
		});
	}
	});

	$(document).on('click', '.terminer_livraison', function() { 
	var livraison = $(this);
	var id_livraison = $(this).data('id_livraison');
	$.ajax({
		url:"ajax/afficherlivraisons.php",
		method:"post",
		dataType:"json",
		data:{action:'cloturer_livraison', id_livraison:id_livraison},
		success:function(data) {
		if(data.type == 'erreur') {
		alert(data.message);
		} else if(data.type == 'succes') {
		livraison.closest('.contenu_commande').remove();
		}
		}
	});
	});

	$('#commandes_inactives').on('click', '.infos_commande', function() { 
		var id_commande = $(this).data('id_commande');
		var commande = (this);
		$.ajax({
			url:"ajax/commandes_inactives.php",
			method:"post",
			dataType:"json",
			data:{action:'details_commande', id_commande:id_commande},
			success:function(data) {
			alert('Date commande : '+data.date_commande+'\nLivraison prise en charge par '+data.livreur+' à '+data.date_debut+'\nLivrée à '+data.date_fin+'\nDurée de la livraison : '+data.duree_livraison);
			}
		});
		
	});

	$('.nom-fonction').click(function() { 
	var permissions = $(this).parent().find('.permissions');
	if(permissions.css('display') == 'none') {
	$('.fonction').each(function() { 
	$(this).find('.permissions').fadeOut(0);
	});
	permissions.fadeIn(0);
	} else if(permissions.css('display') == 'block') {
	permissions.fadeOut(0);
	}
	});

	$('.permissions input[type="checkbox"]').click(function() {
	var check = $(this);
	var id_permission = check.data('id');
	var type_permission = check.data('type');
	$(check).is(':checked') ? x = 1 : x = 0;
	$.ajax({
		url:"ajax/permissions.php",
		method:"post",
		dataType:"json",
		data:{action:'modif_permissions', id_permission:id_permission, type:type_permission, valeur:x},
		success:function(data) {
		if(data.type == 'erreur') {
		alert(data.message);
		} else if(data.type == 'succes') {
		var img = $(check).closest('.permission').find('img').html();
		if(img == undefined) {
		check.after('<img class="succes-image" src="images/succes.svg">');
		} else {
		$(check).closest('.permission').find('img').fadeIn(0);
		}
		check.parent().find('img').delay(3000).fadeOut(200);
		}
		}
	});
	});

	

	$('.nouv-fonction').click(function() { 
	$('table').append('<tbody><tr><td data-label="Nom"><input type="text"></td><td data-label="Actions"><button class="creer-fonction">Créer</button> - <button class="annuler-fonction">Annuler</button></td></tr></tbody>');
	});

	$('table').on('click', '.creer-fonction', function() { 
	var boutton = $(this);
	var nom = $(boutton).closest('tr').find('input').val();
	$.ajax({
		url:"ajax/gestionfonctions.php",
		method:"post",
		dataType:"json",
		data:{action:'creer_fonction', nom:nom},
		success:function(data) {
		if(data.type == 'succes') {
		$(boutton).closest('tr').html('<td data-label="Nom">'+nom+'</td><td data-label="Actions"><button class="editerfonction" data-id="'+data.id_fonction+'">Éditer</button> - <button class="supprimer-fonction">Supprimer</button></td>');
		} else if(data.type == 'erreur') {
		alert(data.message);
		}
		}
	});
	});

	$('table').on('click', '.editerfonction', function() { 
	var boutton = $(this);
	var id_fonction = boutton.data('id');
	var nom = boutton.closest('tr').find('td').eq(0).text();
	boutton.closest('tr').html('<td data-label="Nom"><input type="text" value="'+nom+'"></td><td data-label="Actions"><button class="validerfonction" data-id="'+id_fonction+'">Valider</button> - <button class="annuler-fonction">Annuler</button></td>');
	});

	$('table').on('click', '.validerfonction', function() { 
	var boutton = $(this);
	var id_fonction = boutton.data('id');
	var nom = boutton.closest('tr').find('input').val();
	$.ajax({
		url:"ajax/gestionfonctions.php",
		method:"post",
		dataType:"json",
		data:{action:'editer_fonction', nom:nom, id_fonction:id_fonction},
		success:function(data) {
		if(data.type == 'succes') {
		boutton.closest('tr').html('<td data-label="Nom">'+nom+'</td><td data-label="Actions"><button class="editerfonction" data-id="'+id_fonction+'">Éditer</button> - <button class="supprimer-fonction" data-id="'+id_fonction+'">Supprimer</button></td>');
		} else if(data.type == 'erreur') {
		alert(data.message);
		}
		}
	});
	});

	$('table').on('click', '.supprimerfonction', function() { 
	var boutton = $(this);
	var id_fonction = boutton.data('id');
	$.ajax({
		url:"ajax/gestionfonctions.php",
		method:"post",
		dataType:"json",
		data:{action:'supprimer_fonction', id_fonction:id_fonction},
		success:function(data) {
		if(data.type == 'succes') {
		boutton.closest('tbody').remove();
		} else if(data.type == 'erreur') {
		alert(data.message);
		}
		}
	});
	});

	$('table').on('click', '.annuler-fonction', function() { 
	var boutton = $(this).closest('tr').find('button').eq(0).text();
	var nom = $(this).closest('tr').find('input').eq(0).val();
	var id_fonction = $(this).data('id');
	if(boutton == 'Créer') {
	$(this).closest('tbody').remove();
	} else if(boutton == 'Valider') {
	$(this).closest('tr').html('<td data-label="Nom">'+nom+'</td><td data-label="Actions"><button class="editerfonction" data-id="'+id_fonction+'">Éditer</button> - <button class="supprimer-fonction" data-id="'+id_fonction+'">Supprimer</button></td>');
	}
	});

});

	