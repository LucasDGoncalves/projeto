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
		
		$query = "select artista_genero.id_genero as genero from pessoa 
				join curtida on pessoa.login = curtida.login
				join artista on curtida.id_artista = artista.id
				join artista_genero on artista.id = artista_genero.id_artista
				join genero on artista_genero.id_genero = genero.id
				where pessoa.login like '{$login}'
				group by artista_genero.id_genero
				order by count(*) desc
				limit 5";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs ['genero'];
		}
		
		$query = "select artista.nome_artistico as nome, count(*) as count from artista_genero 
				join artista on  artista_genero.id_artista = artista.id
				join curtida on curtida.id_artista = artista.id
				where artista_genero.id_genero in {$result}
				group by curtida.id_artista
				order by count(*) desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs [];
		}
		
		$this->con->fecha ();
		return $result;
	}
	
	function similar($login) {
		// cria cconecta ao banco
		$this->con->conecta ();
		
		$query = "select *, count(*) from curtida
				join artista_similar on curtida.id_artista = artista_similar.id_artista
				where curtida.login like '{login}'
				group by artista_similar.id_similar
				order by count(*) desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs [];
		}
		
		$this->con->fecha ();
		return $result;
	}
	
	function amigos($login) {
		// cria cconecta ao banco
		$this->con->conecta ();
	
		$query = "select id_artista, (sum(curtida.nota)/5) as peso, count(*) from conhecimento
				join curtida on curtida.login = conhecimento.conhecido
				where conhecimento.conhecedor = 'lucasgoncalves'
				group by curtida.id_artista
				order by peso desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs [];
		}
	
		$this->con->fecha ();
		return $result;
	}
}
$rec = new recomendations ();
$rec->generoPop ( 'lucasgoncalves' );

?>