<?php
include_once "querys.php";

$querys = new querys ();

$allUsers = $querys->getAllUsers ();

echo "<table id='usersList' width='60%'><thead><tr><th id='sort' width='50%'>Nome</th><th id='sort' width='30%'>Cidade</th><th></th></tr></thead><tbody>";
foreach ( $allUsers as $each ) {
	$cidade = utf8_encode ( $each ['city'] );
	echo "<tr><td><span id='user' onclick='loadUser(\"{$each['login']}\")'>{$each['name']}</span>   
	</td><td align='center'>{$cidade}</td>
	<td><button onclick='getSuggestions(\"{$each['login']}\")'>Recomendações</button></td></tr>";
}
echo "</tbody><tfoot><tr><td colspan='2'>";

echo "<div id='pager' class='pager'>
<img src='http://www.tablesorter.com/addons/pager/icons/first.png' class='first'/>
<img src='http://www.tablesorter.com/addons/pager/icons/prev.png' class='prev'/>
<input type='text' readonly='true' size='3' class='pagedisplay'/>
<img src='http://www.tablesorter.com/addons/pager/icons/next.png' class='next'/>
<img src='http://www.tablesorter.com/addons/pager/icons/last.png' class='last'/>
Items: <input type='text' class='pagesize' value='20' size='1'/>
</div></td></tr></tfoot></table>
		<div id='suggestions' style='float:right'>
		blablabla
		</div>";

?>