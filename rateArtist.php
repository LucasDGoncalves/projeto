<?php

include_once "querys.php";

$querys = new querys();

$querys->updateLike($_POST['login'], $_POST['artist'], $_POST['nota']);

echo "OK";
?>