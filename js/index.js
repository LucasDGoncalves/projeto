$(document).ready(function() {

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
				animate : true,
				highlight : true,
				sortable : true,
				removeLabel : 'remover X',
				highlightRemovedLabel : 'Removido',
				highlightAddedLabel : 'Adicionado'
			});

		});
	});

	$('#conteudo').delegate('#submit_register', 'click', function() {
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
			$("#conteudo").html(msg);

		});
	});
});