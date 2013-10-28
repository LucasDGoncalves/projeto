<?php

include_once "recomendations.php";

$recoms = new recomendations ($_POST['login']);

echo "Recomendações para o usuário {$_POST['login']}<br><br>";

$vetor_pontos = array();

$generos = $recoms->generoPop($_POST['login']);
foreach ($generos as $genero){
	foreach ($genero['artistas'] as $artista){
		if (!isset ($vetor_pontos[$artista['artista_id']])){
			$vetor_pontos[$artista['artista_id']]['nome'] = $artista['nome']; 
			$vetor_pontos[$artista['artista_id']]['pontos'] = $artista['valor'] * 0.6;
		}else{
			$vetor_pontos[$artista['artista_id']]['pontos'] += $artista['valor'] * 0.6;
		}
	}
} 

$friends = $recoms->amigos($_POST['login']);

foreach ($friends as $artista){
	if (!isset ($vetor_pontos[$artista['artista_id']])){
			$vetor_pontos[$artista['artista_id']]['nome'] = $artista['nome'];
			$vetor_pontos[$artista['artista_id']]['pontos'] = $artista['peso'];
	}else{
		$vetor_pontos[$artista['artista_id']]['pontos'] += $artista['peso'];
	}
}

$top10 = array();
for ($i=0;$i<10;$i++){
	$top10[$i]['valor'] = 0;	
}

foreach ($vetor_pontos as $key => $artista){
	if ($artista['pontos'] > $top10[9]['valor']){
		$artista['id'] = $key;
		$top10 = insereArtista($artista, $top10);
	}	
} 


// echo"<pre>";
// 		var_dump($top10);
// 		echo"</pre>";
		
echo "<br>";
for ($i=0;$i<=9;$i++){
	echo $i+1 .": ".$top10[$i]['nome']."</br>";
}


echo "<br><br><button onclick='$(\"#suggestions\").html(\"\")'>Fechar</button>";

function insereArtista($artista, $top10){

	$check = 9;
	
	while ($check>=0 && $artista['pontos'] > $top10[$check]['valor']){
		$temp = $top10[$check];
		$top10[$check] = null;
		$top10[$check]['valor'] = $artista['pontos'];
		$top10[$check]['id'] = $artista['id'];
		$top10[$check]['nome'] = $artista['nome'];
		if ($check < 9){
			$top10[$check + 1] = $temp;
		}
		$check--;
	}
	
	return $top10;
}


?>