<?php
include_once "banco.php";
class recomendations {
	private $con;
	function __Construct() {
		$this->con = new banco ();
	}
	function generoPop($login) {
		// cria cconecta ao banco
		$this->con->conecta ();
		
		$query = "select artista_genero.id_genero as genre from pessoa 
				join curtida on pessoa.login = curtida.login
				join artista on curtida.id_artista = artista.id
				join artista_genero on artista.id = artista_genero.id_artista
				join genero on artista_genero.id_genero = genero.id
				where pessoa.login like '{$login}'
				group by genero.id
				order by count(*) desc
				limit 5";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result1 [] = $rs ['genre'];
		}
		
		$result1 = implode(", " , $result1);
		$query = "select artista.nome_artistico as nome, count(*) as count from artista_genero 
				join artista on  artista_genero.id_artista = artista.id
				join curtida on curtida.id_artista = artista.id
				where artista_genero.id_genero in ({$result1})
				group by curtida.id_artista
				order by count desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		
		$this->con->fecha ();
		return $result;
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
	
		$query = "select artista.nome_artistico, (sum(curtida.nota)/5) as peso, count(*) from conhecimento
				join curtida on curtida.login = conhecimento.conhecido
				join artista on curtida.id_artista = artista.id
				where conhecimento.conhecedor = '{$login}'
				group by curtida.id_artista
				order by peso desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
	
		$this->con->fecha ();
		return $result;
	}
}
$rec = new recomendations ();
$rec->amigos ( 'lucasgoncalves' );

?>
