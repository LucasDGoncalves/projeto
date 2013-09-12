<?php

include_once "querys.php";

$querys = new querys();

$querys->deleteLike($_POST['login'], $_POST['artist']);

echo "Removed";
?>