<?php

include_once "querys.php";

$querys = new querys();

$id = 0;
if ($_POST['artist_id']>=1000){
	//artista inexistente no banco	
	$id = $querys->addNewArtist($_POST['artist_name']);
}
else{
	$id = $_POST['artist_id'];
}

$result = $querys->addLikeId($_POST['login'], $id, 5);

if (!$result){
	echo "Artista já cadastrado para esta pessoa";
}
else{
	echo "Artista adicionado";
}

?>