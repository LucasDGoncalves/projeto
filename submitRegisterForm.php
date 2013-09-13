<?php
include_once "querys.php";

$querys = new querys ();

$sucesso = $querys->createUser ( $_POST ['login'], $_POST ['name'], $_POST ['city'] );

$msg = "Este login já esta cadastrado no banco";
if (strlen ( trim ( $_POST ['name'] ) ) == 0) {
	$msg = "Nome não pode ser vazio";
	$sucesso = false;
}
if (strlen ( trim ( $_POST ['login'] ) ) == 0) {
	$msg = "Login não pode ser vazio";
	$sucesso = false;
}

if ($sucesso) {
	foreach ( $_POST ['friends'] as $friend_login ) {
		$querys->addFriend ( $_POST ['login'], $friend_login );
	}
	
	echo "Usuário cadastrado com sucesso";
} else {
	echo $msg;
}

?>