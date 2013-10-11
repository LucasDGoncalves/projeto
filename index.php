<?php
include_once "banco.php";
include_once "querys.php";

echo "<meta http-equiv='content-type' content='text/html;charset=utf-8' />";
echo "<title>Social Book</title>";
echo "<link rel='stylesheet' href='css/jquery-ui.css' />";
echo "<script type='text/javascript' src='js/jquery-2.0.3.js'></script>";
echo "<script src='js/jquery-ui.js'></script>";
echo "<script type='text/javascript' src='js/index.js'></script>";
echo "<link rel='stylesheet' href='css/index.css' />";
echo "<script type='text/javascript' src='js/jquery.asmselect.js'></script>";
echo "<link rel='stylesheet' href='css/jquery.asmselect.css' />";
echo "<script type='text/javascript' src='js/jquery.tablesorter.js'></script>";
echo "<script type='text/javascript' src='js/jquery.tablesorter.pager.js'></script>";

$querys = new querys ();

if (isset ( $_POST ['submit'] ) && $_POST ['submit'] == 'Login') {
	// tentativa de logar, ver senha para decidir se dá acesso ou não
	$user = $querys->getUser ( $_POST ['username'] );
	
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
	<li><a id='tab1_button' href='#tabs-1'>Home</a></li>
	<li><a id='tab2_button' href='#tabs-2'>Usuário</a></li>
	<li><a id='tab3_button' href='#tabs-3'>Estatísticas</a></li>
	</ul>";
	
	echo "<div id='tabs-1' style='height:88%'>";
	echo "<button id='display_register'>Cadastrar Usuário</button>";
	echo "<button id='button_list'>Listar usuários</button>";
	echo "<div id='conteudo'></div> </div>";
	
	echo "<div id='tabs-2' style='height:88%'>";
	// <div id='header-2'>
	// <ul>
	// <li><a id='profile1_button' href='#conteudo-2'>Perfil</a></li>
	// <li><a id='profile2_button' href='#conteudo-3'>Curtidas</a></li>
	// </ul>
	// </div>
	echo "<div id='conteudo-2' style='float:left; widht:50%'> Busque um usuário para seu perfil aparecer nesta aba</div>
			<div id='conteudo-3' style='float:right; margin-right:100'></div>
		</div>";
	
	echo "<div id='tabs-3' style='height:88%'>";
	echo "<div id='conteudo-4' style='float:left; widht:50%'></div>
			<select id='showStat'>
			<option selected='selected'> Selecione a estatística desejada </option> 
			<option value=1>1: Média e Desvio Padrão dos ratings para artistas musicais</option>
			<option value=2>2: 20 artistas com o maior rating médio</option>
			<option value=3>3: 20 artistas com o maior rating médio curtidos por pelo menos duas pessoas</option>
			<option value=4>4: 10 artistas musicais mais populares</option>
			<option value=5>5: 10 artistas com o maior variabilidade de ratings</option>
			<option value=6>6</option>
			<option value=7>7</option>
			<option value=8>8</option>
			<option value=9>9</option>
			<option value=10>10</option>
			<option value=11>11</option>
			<option value=12>12</option>
			<option value=13>13</option>
			<option value=14>14</option></select>
			<div id='conteudo-5' style='float:right; margin-right:100'></div>
		</div>";
	
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