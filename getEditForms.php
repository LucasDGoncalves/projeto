<?php

include_once "querys.php";

$querys = new querys();

$login = $_POST['login'];

$unknownUsers = $querys->getUnKnownUsers($login);
$knownUsers = $querys->getKnownUsers($login);

$fieldFriends = "<select multiple='multiple' id='register_friends' title='Selecione seus amigos'>";
foreach ($knownUsers as $each){
	$fieldFriends .= "<option selected='selected' value='{$each["login"]}'> {$each["nome"]} </option>";
}
foreach ($unknownUsers as $each){
	$fieldFriends .= "<option value='{$each["login"]}'> {$each["nome"]} </option>";
}
$fieldFriends .= "</select>";

// $allArtists = $querys->getAllArtists();
// $fieldArtists = "<select multiple='multiple' id='register_artists' title='Selecione seus artistas favoritos'>";
// foreach ($allArtists as $each){
// 	$fieldArtists.= "<option value='{$each["id"]}'> {$each["name"]} </option>";
// }
// $fieldArtists .= "</select>";

// echo "<div style='float:left; width:40%'>Amigos : {$fieldFriends}</div>";
echo "Amigos : {$fieldFriends}";
// echo "<div style='margin-left:50; float:left; width:40%''>Artistas : {$fieldArtists}</div></br>";

?>