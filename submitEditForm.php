<?php

include_once "querys.php";

$querys = new querys();

var_dump($_POST);

$querys->updateUser($_POST['login'], $_POST['name'], $_POST['city']);

$userInfo = $querys->getCompleteUser ( $login );
$userInfo['amigos'];
$userInfo['artistas'];

$friends_to_add = array();

foreach ($_POST['friends'] as $newfriend){// apenas uma string com o login
	$found = false;
	foreach ($userInfo['amigos'] as $oldFriend){//array associativo com todas inormações
		if ($newFriend == $oldFriend['login']){
			unset($oldFriend);
			$found = true;
			break;
		}
	}
	if (!$found){
		$friends_to_add[] = $newFriend;
	}
}

foreach ($friends_to_add as $newFriend){
	$querys->addFriend($_POST['login'], $newFriend);
}
foreach ($userInfo['amigos'] as $removedFriend){
	$querys->deleteFriend($_POST['login'], $newFriend);
}

echo 'updated';


// $querys->addFriend($_POST['login'], each$friend);


// foreach ($_POST['artists'] as $artist_id){
// 	$querys->addLike($_POST['login'], $artist_id, 5);
// }

// echo "Usuário cadastrado com sucesso";
?>