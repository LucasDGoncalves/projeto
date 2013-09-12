<?php

include_once "querys.php";

$querys = new querys();

$result = $querys->addLike($_POST['login'], $_POST['artist'], $_POST['rating']);

if (!$result){
	echo "Artista já cadastrado para esta pessoa";	
}
else{ 
	echo "added";
}
?>