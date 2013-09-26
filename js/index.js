$(document).ready(
		function() {
			var temp_friends_artists = '';

			$(function() {
				$("#tabs").tabs();
			});

			displayRegister = function() {

				$.ajax({
					url : "registerForm.php",
				}).done(function(msg) {
					$("#conteudo").html(msg);

					$("select[multiple]").asmSelect({
						addItemTarget : 'bottom',
						removeLabel : 'remover X'
					});

				});
			};
			
			$('#display_register').click(function(){
				displayRegister();
				});
			
	

			$('#conteudo').delegate(
					'#submit_register',
					'click',
					function() {
						params = new Object();
						params.name = $('#register_name').val();
						params.login = $('#register_login').val();
						params.city = $('#register_city').val();
						params.friends = $('#register_friends').val();
						params.artists = $('#register_artists').val();

						$.ajax({
							type : 'POST',
							url : "submitRegisterForm.php",
							data : params,
						}).done(function(msg) {
							alert(msg);
							if(msg == 'Usuário cadastrado com sucesso' || msg == 'Este login já esta cadastrado no banco' ){
							loadUser(params.login);
							displayRegister();
							}
						});
					});

			$('#conteudo-2').delegate(
					'#button_save_edit',
					'click',
					function() {
						params = new Object();
						params.name = $('#edit_name').val();
						params.login = $('#edit_login').val();
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
			
			$('#conteudo-3').delegate('#add_artist_like', 'click', function() {
				params = new Object();
				params.artist= $("#new_artist_name").val();
				params.login = $("#edit_login").val();
				params.rating = $("#new_artist_rating").val();
				$.ajax({
					type : 'POST',
					url : "newArtistLike.php",
					data : params
				}).done(function(msg) {
					row = Math.floor((Math.random()*1000)+1);
					var string_line = '<tr id=row_'+row+'><td>'+params.artist+'</td><td><select name="'+$("#new_artist_name").val()+'" onchange="rateArtist(this, \''+$("#edit_login").val()+'\')">';
						for (var j=1;j<=5;j++){
							string_line += '<option value="'+j+'" ';
							if (j == $("#new_artist_rating").val()){
								string_line += 'selected="selected"'; 
							}
							string_line += '>'+j+'</option>';
						}
					string_line += '</td>';
					string_line += '<td><span id=\'remove_'+$("#new_artist_name").val()+'\' onclick="removeLike(this, \''+$("#edit_login").val()+'\', '+row+')">X</span></td></tr>';	
					$("#artists-liked tr:last").after(string_line);
					// resetar linha de adicao
					$("#new_artist").html('http://en.wikipedia.org/wiki/<input type=\'text\' id=\'new_artist_name\' size=\'15\' placeholder=\'uri do artista\'/>' 
							+ '<select id=\'new_artist_rating\' title=\'Nota\'><option value=1>1</option><option value=2>2</option><option value=3>3</option>'
							+ '<option value=4>4</option><option value=5>5</option></select><button id=\'add_artist_like\'>Add</button>');
				});

			});
			
			rateArtist = function(e, login) {
				params = new Object();
				params.login = login;
				params.artist = e.name;
				params.nota = e.value;
				$.ajax({
					type : 'POST',
					url : "rateArtist.php",
					data : params
				}).done(function(msg) {
					alert(msg);
				});
			};
			
			removeLike = function(e, login, row) {
				params = new Object();
				params.login = login;
				params.artist = e.id.substring(7);
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
