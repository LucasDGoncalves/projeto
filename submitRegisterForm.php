<?php

include_once "querys.php";

$querys = new querys();

$querys->createUser($_POST['login'], $_POST['name'], $_POST['city']);

foreach ($_POST['friends'] as $friend_login){
	$querys->addFriend($_POST['login'], $friend_login);
}

foreach ($_POST['artists'] as $artist_id){
	$querys->addLike($_POST['login'], $artist_id, 5);
}

echo "Usuário cadastrado com sucesso";
?>