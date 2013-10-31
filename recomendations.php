<?php
include_once "banco.php";
class recomendations {
	private $con;
	private $stringArtists;
	private $stringArtistsArray;
	function __Construct($login) {
		$this->con = new banco ();
		$this->stringArtists = $this->getFavoriteArtistsIdString($login);
		$this->stringArtistsArray = explode(', ',$this->stringArtists);
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
				order by count desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$result1 = null;
		$genero = Array();
		$i=0;
		$soma=0;
		while ( $rs = mysql_fetch_assoc ($res)) {
			if ($i>=5 && $rs['count'] < 0.03*$soma){
				break;
			}
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
				$rs['valor'] = (1+log($rs['count'])) * ($genero[$j]['count']/$soma); 
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
		
		$query = "select similar.descricao as nome, artista.id as artista_id, sum(nota) as soma from curtida
				join artista_similar on curtida.id_artista = artista_similar.id_artista
				join similar on artista_similar.id_similar = similar.id
				left join artista on similar.descricao = artista.nome_artistico
				where curtida.login like '{$login}'
				group by artista_similar.id_similar
				order by soma desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$max = null;
		$result = array();
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			if ($rs["artista_id"] == NULL || !in_array($rs["artista_id"], $this->stringArtistsArray)){ 
				if ($max == null){
					$max = $rs['soma'];
				}
				$rs ['valor'] = $rs['soma']/$max;
				$result [] = $rs;
			}
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
		$result = null;
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
	
		$this->con->fecha ();
		return $result;
	}
	
	function newUserCase() {
		// cria cconecta ao banco
		$this->con->conecta ();
	
		$query = "select artista.id as artista_id, artista.nome_artistico as nome, count(*) as count from artista, curtida 
		where artista.id = curtida.id_artista GROUP BY curtida.id_artista ORDER BY count desc LIMIT 10;";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$result = null;
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
		$string = '0';
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$string .= ', '.$rs ['id'];
		}
	
		$this->con->fecha ();
	
		return $string;
	}
}
//$rec = new recomendations ();
//$rec->amigos ( 'lucasgoncalves' );

?>
