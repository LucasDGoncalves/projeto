<?php

include_once "querys.php";

$querys = new querys();

$sucesso = $querys->createUser($_POST['login'], $_POST['name'], $_POST['city']);

if ($sucesso){
foreach ($_POST['friends'] as $friend_login){
	$querys->addFriend($_POST['login'], $friend_login);
}

foreach ($_POST['artists'] as $artist_name){
	$querys->addLike($_POST['login'], $artist_name, 5);
}

echo "Usuário cadastrado com sucesso";
}
else{
	echo "Este login já esta cadastrado no banco";
}

?>