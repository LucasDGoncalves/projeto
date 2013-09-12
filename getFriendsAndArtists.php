<?php

include_once "querys.php";

$querys = new querys();

$userInfo = $querys->getCompleteUser($login);

$fieldFriends = "<table><tr><th>Nome</th><th>Cidade</th></tr>";
foreach ($userInfo['amigos'] as $each){
	$fieldFriends .= "<tr><td><span onclick='loadUser(\"{$each['login']}\")'>{$each["nome"]}</span></td><td>{$each["cidade_natal"]}</td>";
}
$fieldFriends .= "</table>";


$fieldArtists = "<table><tr><th>Nome Art.</th><th>Nota</th></tr>";
foreach ($userInfo['artistas'] as $each){
	$fieldArtists .= "<tr><td>{$each["nome_artistico"]}</td><td>{$each["nota"]}</td>";
}
$fieldArtists .= "</table>";

echo "<div style='float:left'>Amigos : {$fieldFriends}</div>";
echo "<div style='margin-left:150; float:left'>Artistas : {$fieldArtists}</br></div>";

?>