<?php
include_once "banco.php";
include_once "DataMining.php";
class querys {
	private $con;
	function __Construct() {
		$this->con = new banco ();
	}
	function importData() {
		// cria cconecta ao banco
		$this->con->conecta ();
		
		// XMLs fonte para inserção de dados no banco
		$xml ['Person'] = 'http://www.ic.unicamp.br/~santanch/teaching/db/xml/person-20130906-0808.xml';
		$xml ['Knows'] = 'http://www.ic.unicamp.br/~santanch/teaching/db/xml/knows-20130906-0835.xml';
 		$xml ['LikesMusic'] = 'http://www.ic.unicamp.br/~santanch/teaching/db/xml/likesMusic-20130906-0852.xml';
		
// 		// XML de Pessoas
// 		// carrega o xml
// 		if (! $loaded = simplexml_load_file ( $xml ['Person'] )) {
// 			// caso nao consiga abrir exibe mensagem de erro
// 			die ( 'unable to load Person XML file' );
// 		} else {
// 			// itera em cada registro de pessoa no XML
// 			foreach ( $loaded->Person as $element ) {
// 				// conversão do nome da cidade, estava ficando com caracteres quebrados
// 				$cidade = utf8_decode ( $element->attributes ()->hometown );
// 				// cria a query de inserção
// 				$query = "INSERT into pessoa (login, nome, cidade_natal) VALUES ('{$element->attributes()->uri}', '{$element->attributes()->name}', '{$cidade}')";
				
// 				// executa a query ou falha e exibe a mensagem de erro do MySQL
// 				$res = mysql_query ( $query ) or die ( "Person - " . mysql_error () );
// 			}
// 		}
		
// 		echo "Pessoas inseridas com sucesso! <br/><br/>";
		
// 		// XML de Knows
// 		// carrega o xml
// 		if (! $loaded = simplexml_load_file ( $xml ['Knows'] )) {
// 			// caso nao consiga abrir exibe mensagem de erro
// 			die ( 'unable to load Knows XML file' );
// 		} else {
// 			// itera em cada registro de knows no XML
// 			foreach ( $loaded->Knows as $element ) {
// 				// cria a query de inserção
// 				$query = "INSERT into conhecimento (conhecedor, conhecido) VALUES ('{$element->attributes()->person}', '{$element->attributes()->colleague}')";
				
// 				// executa a query ou falha e exibe a mensagem de erro do MySQL
// 				$res = mysql_query ( $query ) or die ( "Knows - " . mysql_error () );
// 			}
// 		}
		
// 		echo "Knows inseridas com sucesso! <br/><br/>";
		
		// XML de LikesMusic
		// carrega o xml
		if (! $loaded = simplexml_load_file ( $xml ['LikesMusic'] )) {
			// caso nao consiga abrir exibe mensagem de erro
			die ( 'unable to load LikesMusic XML file' );
		} else {
			$array_artistas = array ();
			$id = 1;
			// carrega usando XPath apenas artistas que há no XML de likes
			$artistas = $loaded->xpath ( "//@bandUri" );
			// armazena todos artistas num vetor usando como chave o "nome" para evitar repetição (na verdade, usa-se o link da wikipedia no momento)
			foreach ( $artistas as $element ) {
				if (! isset ( $array_artistas ["{$element->bandUri}"] )) {
					$array_artistas ["{$element->bandUri}"] = $id ++;
				}
			}
			
			// montagem da query de inserção de cada chave única de nome de artista na tabela artista.
			// O id está sendo gerado aqui apenas para facilitar a manipulação da relação de like->banda, id é auto_increment na verdade
			$query = "INSERT into artista (id, nome_artistico) VALUES ";
			$query_array = array ();
			foreach ( $array_artistas as $name => $t_id ) {
				$name = str_replace ( "'", "\'", $name );
				array_push ( $query_array, "({$t_id}, '{$name}')" );
			}
			$query .= implode ( ", ", $query_array );
			
			// executa query de inserção de artistas
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			echo "Artistas inseridos com sucesso! <br/><br/>";
			
			// itera em cada registro de Likes no XML
			foreach ( $loaded->LikesMusic as $like ) {
				// busca no vetor pela chave do nome da banda para obter o id, que foi o mesmo que foi cadastrado no banco
				// como a inserção de artistas no array e no banco foi feita na mesma ordem o id é o mesmo que seria caso tivesse sido deixado para o MySQL gerar no auto_increment
				$id_banda = $array_artistas ["{$like->attributes()->bandUri}"];
				// monta a query de inserção de like
				$person = str_replace('http://www.ic.unicamp.br/MC536/2013/2/', '', $like->attributes()->person);
				$query2 = "INSERT into curtida (login, id_artista, nota) VALUES ('{$person}', {$id_banda}, '{$like->attributes()->rating}')";
				
				// executa a query ou falha e exibe a mensagem de erro do MySQL
				$res = mysql_query ( $query2 ) or die ( "Likes - " . mysql_error () );
			}
		}
		echo "LikesMusics inseridos com sucesso! <br/><br/>";
		
		$this->con->fecha ();
	}
	
	// Cria novo usuário na rede
	function createUser($user_login, $user_name, $user_city) {
		$this->con->conecta ();
		$sucesso = false;
		$query = "SELECT * from pessoa where login like '{$user_login}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT into pessoa (login, nome, cidade_natal) VALUES ('{$user_login}', '{$user_name}', '{$user_city}')";
			
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			$sucesso = true;
		} else {
			$sucesso = false;
		}
		
		$this->con->fecha ();
		
		return $sucesso;
	}
	
	// Atualiza usuário na rede
	function updateUser($user_login, $user_name, $user_city) {
		$this->con->conecta ();
		
		$query = "UPDATE pessoa SET nome = '{$user_name}',  cidade_natal = '{$user_cit}' where login like '{$user_login}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		$this->con->fecha ();
	}
	
	// Remove usuário na rede
	function deleteUser($user_login, $user_name, $user_city) {
		$this->con->conecta ();
		
		$query = "DELETE FROM pessoa where login like '{$user_login}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		$this->con->fecha ();
	}
	
	// Cria um curtir
	// Retorna false se a banda já foi curtida
	function addLike($user_login, $artist_uri, $rating) {
		$artist_id = $this->addArtist ( $artist_uri );
		$this->con->conecta ();
		
		$query = "SELECT * from curtida where login = '{$user_login}' and id_artista = '{$artist_id}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT into curtida (login, id_artista, nota) VALUES ('{$user_login}', '{$artist_id}', '{$rating}')";
			
			$res = mysql_query ( $query ) or die ( mysql_error () );
		} else {
			$res = false;
		}
		return $res;
	}
	
	// Remove curtir
	function deleteLike($user_login, $artist_uri) {
		$artist_id = $this->addArtist ( $artist_uri );
		$this->con->conecta ();
		
		$query = "DELETE from curtida where login = '{$user_login}' and id_artista = '{$artist_id}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		return $res;
	}
	
	// Atualiza curtir
	function updateLike($user_login, $artist_uri, $rating) {
		$artist_id = $this->addArtist ( $artist_uri );
		$this->con->conecta ();
		
		$query = "UPDATE curtida set nota={$rating} WHERE login ='{$user_login}' and id_artista = '{$artist_id}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		return $res;
	}
	
	// Adiciona amigo
	function addFriend($user, $friend) {
		// cria cconecta ao banco
		$this->con->conecta ();
		
		$query = "INSERT into conhecimento (conhecedor, conhecido) VALUES ('{$user}', '{$friend}')";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		$this->con->fecha ();
	}
	
	// Remove amigo
	function deleteFriend($user, $friend) {
		// cria cconecta ao banco
		$this->con->conecta ();
		
		$query = "DELETE from conhecimento where conhecedor like '{$user}' AND conhecido like '{$friend}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		$this->con->fecha ();
	}
	
	// Retorna Usuário
	function getUser($user) {
		$this->con->conecta ();
		
		$query = "SELECT * FROM pessoa where login like '{$user}' ";
		
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		$rs = mysql_fetch_assoc ( $res );
		
		$this->con->fecha ();
		
		return $rs;
	}
	
	// Retorna Usuário Completo
	function getCompleteUser($user) {
		$querys = new querys ();
		
		$userData = $querys->getUser ( $user );
		$userData ['amigos'] = array ();
		$userData ['artistas'] = array ();
		
		// traz amigos
		$userData ['amigos'] = $querys->getKnownUsers ( $user );
		
		// traz artistas favoritos
		$userData ['artistas'] = $querys->getFavoriteArtists ( $user );
		
		return $userData;
	}
	
	// Adiciona artista se o ainda não exite no BD
	// Retorna o id do artista
	function addArtist($uri) {
		$this->con->conecta ();
		
		$query = "SELECT id from artista where nome_artistico like '{$uri}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT into artista (nome_artistico) VALUES ('{$uri}')";
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			$query = "SELECT id from artista where nome_artistico like '{$uri}'";
			$res = mysql_query ( $query ) or die ( mysql_error () );
		}
		
		$row = mysql_fetch_array ( $res );
		$this->con->fecha ();
		return $row ['id'];
	}
	
	// Atualiza artista
	function updateArtist($id, $name, $origin) {
		$this->con->conecta ();
		
		$query = "UPDATE artista SET nome_artistico = '{$name}', pais = '{$origin}' where id = {$id}";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$this->con->fecha ();
	}
	
	// Retorna todos os artistas cadastrados
	function getAllArtists() {
		$this->con->conecta ();
		
		$query = "SELECT * FROM artista";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$allArtists = array ();
		$i = 0;
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$allArtists [$i] ['id'] = $rs ['id'];
			$allArtists [$i ++] ['name'] = $rs ['nome_artistico'];
		}
		
		$this->con->fecha ();
		
		return $allArtists;
	}
	
	// Retorna os artistas favoritos do user
	function getFavoriteArtists($user) {
		$this->con->conecta ();
		
		$query = "SELECT nome_artistico, nota, id FROM artista, curtida WHERE login like '{$user}' AND id_artista=artista.id ";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$favoriteArtists = array ();
		$i = 0;
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$favoriteArtists [$i] ['id'] = $rs ['id'];
			$favoriteArtists [$i] ['nota'] = $rs ['nota'];
			$favoriteArtists [$i ++] ['nome_artistico'] = $rs ['nome_artistico'];
		}
		
		$this->con->fecha ();
		
		return $favoriteArtists;
	}
	
	// Retorna todos os usuários da rede
	function getAllUsers() {
		$this->con->conecta ();
		
		$query = "SELECT * FROM pessoa";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$allUsers = array ();
		$i = 0;
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$allUsers [$i] ['login'] = $rs ['login'];
			$allUsers [$i] ['city'] = $rs ['cidade_natal'];
			$allUsers [$i ++] ['name'] = $rs ['nome'];
		}
		
		$this->con->fecha ();
		
		return $allUsers;
	}
	
	// Retorna todos os usuários da rede não conhecido pelo o $user_login
	function getUnKnownUsers($user_login) {
		$this->con->conecta ();
		
		$query = "SELECT * FROM pessoa where login <> '{$user_login}' and login not in (select conhecido from conhecimento where conhecedor = '{$user_login}')";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$unknown = array ();
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$unknown [] = $rs;
		}
		$this->con->fecha ();
		
		return $unknown;
	}
	
	// Retorna os amigos de $user_login
	function getKnownUsers($user_login) {
		$this->con->conecta ();
		
		$query = "SELECT * FROM pessoa where login <> '{$user_login}' and login in (select conhecido from conhecimento where conhecedor = '{$user_login}')";
		
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$known = array ();
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$known [] = $rs;
		}
		$this->con->fecha ();
		
		return $known;
	}
	
	// Se cidade existe retorna id da cidade, se não insere e retorna o id
	function addCity($city) {
		$fb = new DataMining ();
		$result = $fb->getLocationInfo ( $city );
		$this->con->conecta ();
		
		$query = "SELECT * FROM cidade WHERE nome like '{$result['city']}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT INTO cidade (nome,id_estado) VALUES ('{$result['city']}',{$this->addState($result['state'])}) ";
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			$query = "SELECT * FROM cidade WHERE nome like '{$result['city']}'";
			$res = mysql_query ( $query ) or die ( mysql_error () );
		}
		$rs = mysql_fetch_assoc ( $res );
		return $rs ['id'];
	}
	
	// Se estado existe retorna id da cidade, se não insere e retorna o id
	function addState($state) {
		$fb = new DataMining ();
		$result = $fb->getLocationInfo ( $state );
		$this->con->conecta ();
		
		$query = "SELECT * FROM estado WHERE nome like '{$result['state']}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT INTO estado (nome,id_pais) VALUES ('{$result['state']}',{$this->addCountry($result['country'])}) ";
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			$query = "SELECT * FROM estado WHERE nome like '{$result['state']}'";
			$res = mysql_query ( $query ) or die ( mysql_error () );
		}
		$rs = mysql_fetch_assoc ( $res );
		return $rs ['id'];
	}
	
	// Se pais existe retorna id do pais, se não insere e retorna o id
	function addCountry($country) {
		$fb = new DataMining ();
		$result = $fb->getLocationInfo ( $country );
		$this->con->conecta ();
		
		$query = "SELECT * FROM pais WHERE nome like '{$result['country']}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT INTO pais (nome) VALUES ('{$result['country']}') ";
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			$query = "SELECT * FROM pais WHERE nome like '{$result['country']}'";
			$res = mysql_query ( $query ) or die ( mysql_error () );
		}
		$rs = mysql_fetch_assoc ( $res );
		return $rs ['id'];
	}
	
	// Adiciona gênero, se não existir, retorna o id
	function addGenero($genre) {
		$this->con->conecta ();
		
		$query = "SELECT * FROM genero WHERE descricao like '{$genre}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT INTO genero (descricao) VALUES ('{$genre}') ";
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			$query = "SELECT * FROM genero WHERE descricao like '{$genre}'";
			$res = mysql_query ( $query ) or die ( mysql_error () );
		}
		$rs = mysql_fetch_assoc ( $res );
		return $rs ['id'];
	}
	
	// Adiciona gênero, se não existir, retorna o id
	function addSimilar($similar) {
		$this->con->conecta ();
		
		$query = "SELECT * FROM similar WHERE descricao like '{$similar}'";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		if (mysql_num_rows ( $res ) == 0) {
			$query = "INSERT INTO similar (descricao) VALUES ('{$similar}') ";
			$res = mysql_query ( $query ) or die ( mysql_error () );
			
			$query = "SELECT * FROM similar WHERE descricao like '{$similar}'";
			$res = mysql_query ( $query ) or die ( mysql_error () );
		}
		$rs = mysql_fetch_assoc ( $res );
		return $rs ['id'];
	}
	

	// Rotina para atualizar todos os artistas
	function updateArtists() {
		$fb = new DataMining ();
		$result = $this->getAllArtists ();
		foreach ( $result as $artist ) {
			$r = $fb->searchLastFMArtist ( $artist ['name'] );
			
			if (isset ( $r ['name'] )) {
				$this->updateArtist ( $artist ['id'], mysql_real_escape_string ( $r ['name'] ), $r ['placeformed'] );
// 				foreach ( $r ['similar'] ['artist'] as $similar ) {
// 					$similar_id = $this->addSimilar ( mysql_real_escape_string ( $similar ['name'] ) );
// 					$query = "SELECT * FROM artista_similar WHERE id_artista = {$artist['id']} and id_similar = {$similar_id}";
// 					$res = mysql_query ( $query ) or die ( mysql_error () );
// 					if (mysql_num_rows ( $res ) == 0) {
// 						$query = "INSERT INTO artista_similar (id_artista,id_similar) VALUES ({$artist['id']},{$similar_id}) ";
// 						mysql_query ( $query ) or die ( mysql_error () );
// 					}
// 				}

				$r = $fb->searchFreeBaseGenre ( $r ['name'] );
				foreach ( $r ['genre'] as $genre ) {
					$genre_id = $this->addGenero ( $genre );
					$query = "SELECT * FROM artista_genero WHERE id_artista = {$artist['id']} and id_genero = {$genre_id}";
					$res = mysql_query ( $query ) or die ( mysql_error () );
					
					if (mysql_num_rows ( $res ) == 0) {
						$query = "INSERT INTO artista_genero (id_artista,id_genero) VALUES ({$artist['id']},{$genre_id}) ";
						mysql_query ( $query ) or die ( mysql_error () );
					}
				}
			}
		}
	}
}
?>
