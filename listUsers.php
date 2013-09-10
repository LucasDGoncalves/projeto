<?php 

include_once "querys.php";

$querys = new querys();

$allUsers = $querys->getAllUsers();

echo "<table id='usersList' width='60%'><thead><tr><th width='50%'>Nome</th><th width='50%'>Cidade</th></tr></thead><tbody>";
foreach ($allUsers as $each){
	echo "<tr><td><span onclick='loadUser(\"{$each['login']}\")'>{$each['name']}</span></td><td>{$each['city']}</td></tr>";	
} 
echo "</tbody><tfoot><tr><td colspan='2'>";

echo "<div id='pager' class='pager'>
<img src='http://www.tablesorter.com/addons/pager/icons/first.png' class='first'/>
<img src='http://www.tablesorter.com/addons/pager/icons/prev.png' class='prev'/>
<input type='text' readonly='true' size='3' class='pagedisplay'/>
<img src='http://www.tablesorter.com/addons/pager/icons/next.png' class='next'/>
<img src='http://www.tablesorter.com/addons/pager/icons/last.png' class='last'/>
Items: <input type='text' class='pagesize' value='20' size='1'/>
</div></td></tr></tfoot></table>";

?>