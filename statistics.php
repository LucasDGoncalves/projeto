<?php
include_once "banco.php";
class statistics {
	private $con;
	function __Construct() {
		$this->con = new banco ();
	}
	
	/* Média de desvio padrão para artista musicais */
	function s1() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT AVG(curtida.nota) as Media, STD(curtida.nota)  as Desvio
					FROM artista, curtida";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		$result [] = $rs;
		$this->con->fecha ();
		return $result;
	}
	
	/* Artistas com maior rating médio */
	function s2() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT artista.nome_artistico as Nome, COUNT(*) as Curtida, AVG(curtida.nota) as Media, STD(curtida.nota)  as Desvio
					FROM artista, curtida
					where curtida.id_artista = artista.id
					group by artista.id
					order by AVG(curtida.nota) desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Artistas com maior rating médio e mais que 2 curtidas */
	function s3() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT * from (
					SELECT artista.nome_artistico as Nome, COUNT(*) as Curtida, AVG(curtida.nota) as Media, STD(curtida.nota)  as Desvio
					FROM artista, curtida
					where curtida.id_artista = artista.id
					group by artista.id 
					order by AVG(curtida.nota) desc) as a
					where a.Curtida >=2";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
	}
	/* Artistas mais populares */
	function s4() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT artista.nome_artistico as Nome, COUNT(*) as Curtidas, AVG(curtida.nota) as Media, STD(curtida.nota) as Desvio  
					FROM artista, curtida
					where curtida.id_artista = artista.id
					group by artista.id
					order by COUNT(*) desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Artistas com maior variabilidade */
	function s5() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT * from (SELECT artista.nome_artistico as Nome, COUNT(*) as Curtidas, AVG(curtida.nota) as Media, STD(curtida.nota) as Desvio 
					FROM artista, curtida
					where curtida.id_artista = artista.id
					group by artista.id
					order by STD(curtida.nota) desc) as a
					where a.Curtidas >= 2";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Genero por popularidade */
	function s6() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT  genero.descricao as Genero, count(*) as Count
					FROM artista_genero, genero
					where genero.id = artista_genero.id_genero
					group by genero.id
					order by count(*) desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Conhecidos que compartilham curtidas */
	function s7() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT conhecedor, conhecido, SUM(if(a.id_artista = b.id_artista ,1,0)) as sum
					FROM conhecimento join curtida as a on conhecimento.conhecedor = a.login join curtida as b on conhecimento.conhecido = b.login
					group by conhecedor, conhecido
					order by sum desc";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Dados de popularidade para quartis */
	function s8() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Dados de popularidade para o gráfico 1 */
	function s9() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT artista.nome_artistico, COUNT(*), AVG(curtida.nota), STD(curtida.nota)  
					FROM artista, curtida
					where curtida.id_artista = artista.id
					group by artista.id";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Dados de curtidas para o gráfico 2 */
	function s10() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT a.qtd, count(*) as amount from ( SELECT login, COUNT( * ) as qtd
					FROM curtida GROUP BY login) as a GROUP BY a.qtd";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
	
	/* Dados de curtidas para o gráfico 3 */
	function s11() {
		// cria cconecta ao banco
		$this->con->conecta ();
		$query = "SELECT a.qtd, count(*) as amount from ( SELECT id_artista, COUNT( * ) as qtd
					FROM curtida GROUP BY id_artista) as a GROUP BY a.qtd";
		$res = mysql_query ( $query ) or die ( mysql_error () );
		while ( $rs = mysql_fetch_assoc ( $res ) ) {
			$result [] = $rs;
		}
		$this->con->fecha ();
		return $result;
	}
}
?>
