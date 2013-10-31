<?php

include_once "recomendations.php";

$recoms = new recomendations ($_POST['login']);

echo "Recomendações para o usuário {$_POST['login']}<br><br>";

$vetor_pontos = array();

$generos = $recoms->generoPop($_POST['login']);

// echo "<pre>";
// var_dump($generos);
// echo "</pre>";

foreach ($generos as $genero){
	foreach ($genero['artistas'] as $artista){
		if (!isset ($vetor_pontos[$artista['artista_id']])){
			$vetor_pontos[$artista['artista_id']]['nome'] = $artista['nome']; 
			$vetor_pontos[$artista['artista_id']]['pontos'] = $artista['valor'];
		}else{
			$vetor_pontos[$artista['artista_id']]['pontos'] += $artista['valor'];
		}
	}
} 

$friends = $recoms->amigos($_POST['login']);
if ($friends != null){
	foreach ($friends as $artista){
		if (!isset ($vetor_pontos[$artista['artista_id']])){
				$vetor_pontos[$artista['artista_id']]['nome'] = $artista['nome'];
				$vetor_pontos[$artista['artista_id']]['pontos'] = $artista['peso'];
		}else{
			$vetor_pontos[$artista['artista_id']]['pontos'] += $artista['peso'];
		}
	}
}

if (!empty($generos)){
	$similares = $recoms->similar($_POST['login']);
	$id_generator = 1000;
	foreach ($similares as $artista){
		if ($artista['artista_id'] == NULL){
			$artista['artista_id'] = $id_generator++; 
		}
		if (!isset ($vetor_pontos[$artista['artista_id']])){
			$vetor_pontos[$artista['artista_id']]['nome'] = $artista['nome'];
			$vetor_pontos[$artista['artista_id']]['pontos'] = $artista['valor']*2;
		}else{
			$vetor_pontos[$artista['artista_id']]['pontos'] += $artista['valor']*2;
		}
	}
// echo"<pre>";
// 		var_dump($vetor_pontos);
// 		echo"</pre>";
}

if (empty($generos) || ($friends == null)){
	$artistas = $recoms->newUserCase();
	$pts = 1;
	foreach ($artistas as $artista){
		$vetor_pontos[$artista['artista_id']]['nome'] = $artista['nome'];
		$vetor_pontos[$artista['artista_id']]['pontos'] = $pts;
		$pts -= 0.1;
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



		
echo "<br>";
for ($i=1;$i<=10;$i++){
	echo "<div id='art-{$top10[$i-1]['id']}'>".$i .": ".$top10[$i-1]['nome']."<span style='position:absolute;right:100px;'> <button onclick='like_suggested(\"{$_POST['login']}\",{$top10[$i-1]['id']})'>Curtir</button></span></div></br>";
}


echo "<br><br><button onclick='$(\"#suggestions2\").html(\"\"); $(\"#liked\").show()'>Fechar</button>";

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