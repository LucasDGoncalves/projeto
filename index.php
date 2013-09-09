<?php
include_once "banco.php";
include_once "querys.php";

echo "<meta charset='utf-8' />";
echo "<title>Social Book</title>";
echo "<link rel='stylesheet' href='css/jquery-ui.css' />";
echo "<script type='text/javascript' src='js/jquery-2.0.3.js'></script>";
echo "<script src='js/jquery-ui.js'></script>";
echo "<script type='text/javascript' src='js/index.js'></script>";
echo "<link rel='stylesheet' href='css/index.css' />";
echo "<script type='text/javascript' src='js/jquery.asmselect.js'></script>";
echo "<link rel='stylesheet' href='css/jquery.asmselect.css' />";

$querys = new querys ();

if (isset ( $_POST ['submit'] ) && $_POST ['submit'] == 'Login') {
	// tentativa de logar, ver senha para decidir se dá acesso ou não
	$res = $querys->getUser ( $_POST ['username'] );
	$user = mysql_fetch_array ( $res );
	
	if (strpos ( $user ['nome'], $_POST ['password'] ) !== false) {
		$_SESSION ['logado'] = true;
		$_SESSION ['username'] = $_POST ['username'];
		$_SESSION ['name'] = $user ['nome'];
		$_SESSION ['city'] = $user ['cidade_natal'];
	} else {
		echo "Usuário ou senha incorretos</br>";
	}
}

if (isset ( $_SESSION ['logado'] ) && $_SESSION ['logado']) {
	// mostrar tela inicial do sistema
	echo "<div id='tabs'>";
	echo "<ul>
	<li><a href='#tabs-1'>Home</a></li>
	<li><a href='#tabs-2'>Amigos</a></li>
	<li><a href='#tabs-3'>Artistas</a></li>
	</ul>";
	
	echo "<div id='tabs-1'>";
	echo "<button id='display_register'>Cadastrar Usuário</button>";
	echo "<div id='conteudo'></div> </div>";
	
	echo "<div id='tabs-2'>  <p>Amigos</p>  </div>";
	echo "<div id='tabs-3'>  <p>Artistas</p>  </div>";
	echo "</div>";
} else {
	// mostrar tela de login
	echo "<form action='index.php' method='POST'>
			Usuário: <input type='text' name='username'>
			Senha: <input type='password' name='password'>
			<input type='submit' value='Login' name='submit'>
			</form>";
}

?>