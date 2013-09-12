<?php
include_once "querys.php";

$querys = new querys ();

$login = substr($_POST['login'], strlen('http://www.ic.unicamp.br/MC536/2013/2/'));
$userInfo = $querys->getCompleteUser ( $login );

$fieldFriends = "<table><tr><th>Nome</th><th>Cidade</th></tr>";
foreach ( $userInfo ['amigos'] as $each ) {
	$cidade_natal = utf8_encode ( $each ['cidade_natal'] );
	$fieldFriends .= "<tr><td><span onclick='loadUser(\"{$each['login']}\")'>{$each["nome"]}</span></td><td>{$cidade_natal}</td>";
}
$fieldFriends .= "</table>";

$fieldArtists = "<table><tr><th>Nome Art.</th><th>Nota</th></tr>";
foreach ( $userInfo ['artistas'] as $each ) {
	$fieldArtists .= "<tr><td>{$each["nome_artistico"]}</td><td>{$each["nota"]}</td>";
}
$fieldArtists .= "</table>";

echo "Nome: <input type='text' size='50' id='edit_name' name='name' disabled='disabled' value='{$userInfo['nome']}'></br>";
echo "Username: http://www.ic.unicamp.br/MC536/2013/2/ <input type='text' id='edit_login' name='username' disabled='disabled' value='{$login}'></br>";
$cidade_natal = utf8_encode ( $userInfo ['cidade_natal'] );
echo "Cidade Natal: <input type='text'  id='edit_city' name='city' disabled='disabled' value='{$cidade_natal}'></br>";
echo "<div id='combos'><div style='float:left'>Amigos : {$fieldFriends}</div>";
echo "<div style='margin-left:150; float:left'>Artistas : {$fieldArtists}</br></div></div></br>";

echo "<div id='div-edit-buttons' style='float:left'>
		<button id='button_edit'>Editar</button>
		<button id='button_cancel' hidden>Cancelar</button>
		<button id='button_save_edit' hidden>Salvar</button>
		</div>";
?>