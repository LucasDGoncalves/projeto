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
						params.name = $('#register_name').val();
						params.login = 'http://www.ic.unicamp.br/MC536/2013/2/'
								+ $('#register_login').val();
						params.city = $('#register_city').val();
						params.friends = $('#register_friends').val();
						params.artists = $('#register_artists').val();

						$.ajax({
							type : 'POST',
							url : "submitEditForm.php",
							data : params,
						}).done(function(msg) {
							$("#conteudo").html(msg);

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
				$("#tab2_button").trigger('click');
				params = new Object();
				params.login = login;
				$.ajax({
					type : 'POST',
					url : "loadUser.php",
					data : params
				}).done(function(msg) {
					$("#conteudo-2").html(msg);

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
				$("#combos").html(temp_friends_artists);

			});

			$('#conteudo-2').delegate('#button_edit', 'click', function() {
				// transformar botao editar em salvar e cancelar
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
					temp_friends_artists = $("#combos").html();
					$("#combos").html(msg);

					$("select[multiple]").asmSelect({
						addItemTarget : 'bottom',
						sortable : true,
						removeLabel : 'remover X'
					});

				});
			});
		});
