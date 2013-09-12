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

$fieldArtists = "<table id='artists-liked'><tr><th>Nome Art.</th><th>Nota</th><th colspan='2'></th></tr>";
$row=0;
foreach ( $userInfo ['artistas'] as $each ) {
	$artName = substr($each["nome_artistico"], strlen('http://en.wikipedia.org/wiki/'));
	$fieldArtists .= "<tr id='row_{$row}'><td>{$each["nome_artistico"]}</td><td>
	<select name='{$artName}' onchange=\"rateArtist(this, '{$login}')\">";
	for ($i=1;$i<=5;$i++){
		$fieldArtists .= "<option value='{$i}' ";
		if ($i==$each["nota"]){
			$fieldArtists .= "selected='selected'";
		}
		$fieldArtists .= ">{$i}</option>";
	}
	$fieldArtists .="</select>
			<td><span id='remove_{$artName}' onclick=\"removeLike(this, '{$login}', {$row})\">X</span></td>
			<td><span id='notice_{$artName}'>&nbsp;</span></td></td>";
	$row++;
}
$fieldArtists .= "</table>";

// echo"<div id='conteudo-2'>";
echo "Nome: <input type='text' size='50' id='edit_name' name='register_name' disabled='disabled' value='{$userInfo['nome']}'></br>";
echo "Username: http://www.ic.unicamp.br/MC536/2013/2/ <input type='text' id='edit_login' name='register_login' disabled='disabled' value='{$login}'></br>";
$cidade_natal = utf8_encode ( $userInfo ['cidade_natal'] );
echo "Cidade Natal: <input type='text' id='edit_city' name='register_city' disabled='disabled' value='{$cidade_natal}'></br>";
echo "<div id='combos'><div id='div-amigos' style='float:left; margin-top:50'>Amigos : {$fieldFriends}</div><br>";
echo "<div id='div-edit-buttons' style='float:right'>
		<button id='button_edit'>Editar</button>
		<button id='button_cancel' hidden>Cancelar</button>
		<button id='button_save_edit' hidden>Salvar</button>
		</div></div>";
// echo "</div><div id='conteudo-3'>";
echo "||separator||<div style='margin-left:150; float:right'>Artistas : {$fieldArtists}</br><div id='new_artist' style='clear:both'>
		http://en.wikipedia.org/wiki/<input type='text' id='new_artist_name' size='15' placeholder='uri do artista'/>
			<select id='new_artist_rating' title='Nota'><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option></select>	
<button id='add_artist_like'>Add</button></div></div>";


?>