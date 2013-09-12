<?php

include_once "querys.php";

$querys = new querys();

$allUsers = $querys->getAllUsers();
$fieldFriends = "<select multiple='multiple' id='register_friends' title='Selecione seus amigos'>";
foreach ($allUsers as $each){
	$fieldFriends .= "<option value='{$each["login"]}'> {$each["name"]} </option>";
}
$fieldFriends .= "</select>";

$allArtists = $querys->getAllArtists();
$fieldArtists = "<select multiple='multiple' id='register_artists' title='Selecione seus artistas favoritos'>";
foreach ($allArtists as $each){
	$fieldArtists.= "<option value='{$each["name"]}'> {$each["name"]} </option>";
}
$fieldArtists .= "</select>";

echo "Nome: <input type='text' id='register_name' name='name'></br>";
echo "Username: http://www.ic.unicamp.br/MC536/2013/2/ <input type='text' id='register_login' name='username'></br>";
echo "Cidade Natal: <input type='text'  id='register_city' name='city'></br>";
echo "<div style='float:left; width:40%'>Amigos : {$fieldFriends}</div>";
echo "<div style='margin-left:50; float:left; width:40%''>Artistas : {$fieldArtists}</div></br>";

echo "<button id='submit_register'>Cadastrar</button>";

?>