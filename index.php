<?php

// carrega arquivo da classe de conexao com o banco, configurar dados do banco l�
require "banco.php";

// importa dados da web
$con = new banco ();
$con->importData();


?> 