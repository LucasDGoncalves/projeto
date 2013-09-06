<?php
class banco {
	public $servidor;
	public $usuario;
	public $database;
	public $password;
	public $cn;
	
	function __Construct() {
		$this->servidor = "localhost:8889";
		$this->usuario = "root";
		$this->database = "mc536";
		$this->password = "root";
	}
	
	function conecta() {
		$this->cn = mysql_connect ( $this->servidor, $this->usuario, $this->password ) or die ( "Falha na conexão com o banco de dados!" );
		mysql_select_db ( $this->database, $this->cn );
	}
	
	function fecha() {
		mysql_close ( $this->cn );
	}
}
?>