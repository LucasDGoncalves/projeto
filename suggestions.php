<?php

include_once "recomendations.php";

$querys = new recomendations ();

echo "Recomendações para o usuário {$_POST['login']}<br><br>";

for ($i=1;$i<=10;$i++){
	echo "banda recomendada{$i}<br>";
}


echo "<br><br><button onclick='$(\"#suggestions\").html(\"\")'>Fechar</button>";


?>