$(document).ready(
		function() {
			var temp_friends_artists = '';

			$(function() {
				$("#tabs").tabs();
			});

			$('#display_register').click(function() {

				$.ajax({
					url : "registerForm.php",
				}).done(function(msg) {
					$("#conteudo").html(msg);

					$("select[multiple]").asmSelect({
						addItemTarget : 'bottom',
						removeLabel : 'remover X'
					});

				});
			});

			$('#conteudo').delegate(
					'#submit_register',
					'click',
					function() {
						params = new Object();
						params.name = $('#register_name').val();
						params.login = 'http://www.ic.unicamp.br/MC536/2013/2/'
								+ $('#register_login').val();
						params.city = $('#register_city').val();
						params.friends = $('#register_friends').val();
						params.artists = $('#register_artists').val();

						$.ajax({
							type : 'POST',
							url : "submitRegisterForm.php",
							data : params,
						}).done(function(msg) {
							$("#conteudo").html(msg);

						});
					});

			$('#conteudo-2').delegate(
					'#button_save_edit',
					'click',
					function() {
						params = new Object();
						params.name = $('#edit_name').val();
						params.login = 'http://www.ic.unicamp.br/MC536/2013/2/'
								+ $('#edit_login').val();
						params.city = $('#edit_city').val();
						params.friends = $('#register_friends').val();
						params.artists = $('#register_artists').val();

						$.ajax({
							type : 'POST',
							url : "submitEditForm.php",
							data : params,
						}).done(function(msg) {
							$("#conteudo-2").html(msg);
						});
					});
			
			$('#button_list').click(function() {

				$.ajax({
					url : "listUsers.php",
				}).done(function(msg) {
					$("#conteudo").html(msg);

					$("#usersList").tablesorter({
						widthFixed : true,
						widgets : [ 'zebra' ]
					}).tablesorterPager({
						container : $("#pager")
					});

				});
			});

			loadUser = function(login) {
				$("#conteudo-2").hide();
				$("#conteudo-3").hide();
				$("#tab2_button").trigger('click');
				params = new Object();
				params.login = login;
				$.ajax({
					type : 'POST',
					url : "loadUser.php",
					data : params
				}).done(function(msg) {
					conteudo2 = msg.substring(0, msg.indexOf('||separator||'));
					conteudo3 = msg.substring(msg.indexOf('||separator||')+13);
					
					$("#conteudo-2").html(conteudo2);
					$("#conteudo-3").html(conteudo3);
					$("#conteudo-2").show();
					$("#conteudo-3").show();
				});

			};

			$('#conteudo-2').delegate('#button_cancel', 'click', function() {
				// trocar botoes sendo mostrados
				$('#button_cancel').hide();
				$('#button_save_edit').hide();
				$('#button_edit').show();

				// desabilitar edição dos campos de pessoa
				$('#edit_name').prop('disabled', true);
				$('#edit_city').prop('disabled', true);

				// desabilitar edição campo de amigos e campo de artistas
				$("#div-amigos").html(temp_friends_artists);

			});
			
			$("#file").on( "change", '.selector_of_element_to_watch', function() {
			      // Run if a descendant of '#file' that matches the selector
			      //    '.selector_of_element_to_watch' triggered the event
			    $("#submit_form").trigger('click');
			});
			
			rateArtist = function(e, login) {
				params = new Object();
				params.login = 'http://www.ic.unicamp.br/MC536/2013/2/'+login;
				params.artist = 'http://en.wikipedia.org/wiki/'+e.name;
				params.nota = e.value;
				$.ajax({
					type : 'POST',
					url : "rateArtist.php",
					data : params
				}).done(function(msg) {
					$("#notice_"+e.name).html(msg);
				});
			};
			
			removeLike = function(e, login, row) {
				params = new Object();
				params.login = 'http://www.ic.unicamp.br/MC536/2013/2/'+login;
				params.artist = 'http://en.wikipedia.org/wiki/'+e.id.substring(7);
				$.ajax({
					type : 'POST',
					url : "removeLike.php",
					data : params
				}).done(function(msg) {
					$("#row_"+row).hide();
				});
			};


			$('#conteudo-2').delegate('#button_edit', 'click', function() {
				// transformar botao editar em salvar e cancelar
				$("#div-amigos").hide();
				$('#button_edit').hide();
				$('#button_cancel').show();
				$('#button_save_edit').show();

				// habilitar campos de pessoa
				$('#edit_name').prop('disabled', false);
				$('#edit_city').prop('disabled', false);

				params = new Object();
				params.login = $('#edit_login').val();

				// habilitar campos de amigos e artistas
				$.ajax({
					type : 'POST',
					url : "getEditForms.php",
					data : params,
				}).done(function(msg) {
					temp_friends_artists = $("#div-amigos").html();
					$("#div-amigos").html(msg);

					$("select[multiple]").asmSelect({
						addItemTarget : 'bottom',
						sortable : true,
						removeLabel : 'X'
					});
					$("#div-amigos").show();
				});
			});
		});
