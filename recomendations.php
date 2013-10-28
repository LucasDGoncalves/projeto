<?php
include_once "banco.php";
class recomendations {
	private $con;
	private $stringArtists;
	function __Construct($login) {
		$this->con = new banco ();
		$this->stringArtists = $this->getFavoriteArtistsIdString($login);
	}
	function generoPop($login) {
		// cria cconecta ao banco
		$this->con->conecta ();
		
		$query = "select artista_genero.id_genero as genre, count(*) as count from pessoa 
				join curtida on pessoa.login = curtida.login
				join artista on curtida.id_artista = artista.id
				join artista_genero on artista.id = artista_genero.id_artista
				join genero on artista_genero.id_genero = genero.id
				where pessoa.login like '{$login}'
				group by genero.id
				order by count desc
				limit 5";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$result1 = null;
		$genero = Array();
		$i=0;
		$soma=0;
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$genero[$i]['id'] = $rs['genre'];
			$genero[$i]['count'] = $rs['count'];
			$soma += $rs['count'];
			$i++;
		}
		
		for ($j=0;$j<$i;$j++){
			
			$query = "select artista.id as artista_id,  artista.nome_artistico as nome, count(*) as count from artista_genero 
					join artista on  artista_genero.id_artista = artista.id
					join curtida on curtida.id_artista = artista.id
					where artista_genero.id_genero = {$genero[$j]['id']}
					AND artista.id not in ({$this->stringArtists})
					group by curtida.id_artista
					order by count desc";
			$result = array();
			$res = mysql_query ( $query ) or die ( mysql_error () );
			while ( $rs = mysql_fetch_assoc ( $res ) ) {
				$rs['valor'] = $rs['count'] * ($genero[$j]['count']/$soma); 
				$result [] = $rs;
			}
			$genero [$j]['artistas'] = $result;
		}
		
		$this->con->fecha ();
		return $genero;
	}
	
	function similar($login) {
		// cria cconecta ao banco
		$this->con->conecta ();
		
		$query = "select similar.descricao as nome, count(*) as count from curtida
				join artista_similar on curtida.id_artista = artista_similar.id_artista
				join similar on artista_similar.id_similar = similar.id
				where curtida.login like '{$login}'
				group by artista_similar.id_similar
				order by count desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		
		$this->con->fecha ();
		return $result;
	}
	
	function amigos($login) {
		// cria cconecta ao banco
		$this->con->conecta ();
	
		$query = "select artista.id as artista_id, artista.nome_artistico as nome, (sum(curtida.nota)/5) as peso, count(*) as count from conhecimento
				join curtida on curtida.login = conhecimento.conhecido
				join artista on curtida.id_artista = artista.id
				where conhecimento.conhecedor = '{$login}'
				AND artista.id not in ({$this->stringArtists})
				group by curtida.id_artista
				order by peso desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
	
		$this->con->fecha ();
		return $result;
	}
	
	function getFavoriteArtistsIdString($user) {
		$this->con->conecta ();
	
		$query = "SELECT id FROM artista, curtida WHERE login like '{$user}' AND id_artista=artista.id ";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$favoriteArtists = array ();
		$i = 0;
		$string = null;
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			if ($string == null){
				$string = $rs ['id'];
			}else{
				$string .= ', '.$rs ['id'];
			}
		}
	
		$this->con->fecha ();
	
		return $string;
	}
}
//$rec = new recomendations ();
//$rec->amigos ( 'lucasgoncalves' );

?>
