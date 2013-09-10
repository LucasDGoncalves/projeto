<?php

include_once "querys.php";

$querys = new querys();

$login = substr($_POST['login'], strlen('http://www.ic.unicamp.br/MC536/2013/2/'));
$userInfo = $querys->getCompleteUser($login);

$fieldFriends = "<table><tr><th>Nome</th><th>Cidade</th></tr>";
foreach ($userInfo['amigos'] as $each){
	$fieldFriends .= "<tr><td>{$each["nome"]}</td><td>{$each["cidade_natal"]}</td>";
}
$fieldFriends .= "</table>";


$fieldArtists = "<table><tr><th>Nome Art.</th><th>Nota</th></tr>";
foreach ($userInfo['artistas'] as $each){
	$fieldArtists .= "<tr><td>{$each["nome_artistico"]}</td><td>{$each["nota"]}</td>";
}
$fieldArtists .= "</table>";

echo "Nome: <input type='text' size='50' id='edit_name' name='name' disabled='disabled' value='{$userInfo['nome']}'></br>";
echo "Username: http://www.ic.unicamp.br/MC536/2013/2/ <input type='text' id='edit_login' name='username' disabled='disabled' value='{$login}'></br>";
echo "Cidade Natal: <input type='text'  id='edit_city' name='city' disabled='disabled' value='{$userInfo['cidade_natal']}'></br>";
echo "<div id='combos'><div style='float:left'>Amigos : {$fieldFriends}</div>";
echo "<div style='margin-left:150; float:left'>Artistas : {$fieldArtists}</br></div></div></br>";

echo "<div id='div-edit-buttons' style='float:left'>
		<button id='button_edit'>Editar</button>
		<button id='button_cancel' hidden>Cancelar</button>
		<button id='button_save' hidden>Salvar</button>
		</div>";
?>