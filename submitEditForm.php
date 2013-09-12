<?php

include_once "querys.php";

$querys = new querys();

$querys->updateUser($_POST['login'], $_POST['name'], $_POST['city']);

$login = substr($_POST['login'], strlen('http://www.ic.unicamp.br/MC536/2013/2/'));
$userInfo = $querys->getCompleteUser ( $login );

$friends_to_add = array();

foreach ($_POST['friends'] as $newFriend){// apenas uma string com o login
	$found = false;
	foreach ($userInfo['amigos'] as &$oldFriend){//array associativo com todas inormações
		if ($newFriend == $oldFriend['login']){
			$oldFriend['found'] = 1;
			$found = true;
			break;
		}
	}
	if (!$found){
		$friends_to_add[] = $newFriend;
	}
	else{
	}
}

foreach ($friends_to_add as $newFriend){
	$querys->addFriend($_POST['login'], $newFriend);
}
foreach ($userInfo['amigos'] as $removedFriend){
	if (!isset($removedFriend['found']))
	$querys->deleteFriend($_POST['login'], $removedFriend['login']);
}


// $querys->addFriend($_POST['login'], each$friend);


// foreach ($_POST['artists'] as $artist_id){
// 	$querys->addLike($_POST['login'], $artist_id, 5);
// }

// echo "Usuário cadastrado com sucesso";
?>