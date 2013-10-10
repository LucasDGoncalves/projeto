<?php
include_once "statistics.php";
$stat = new statistics ();
$case = $_POST ['statistic_id'];

switch ($i) {
	// List artist
	case 1 :
		$res = $stat->s1 ();
		echo "Rating Médio: " + $res ["Media"];
		echo "Desvio Padrão: " + $res ["Desvio"];
		break;
	
	case 2 :
		$res = $stat->s2 ();
		for($i = 0; $i < 20; $i ++) {
			echo $res [$i] ['Nome'];
		}
		
		break;
	case 3 :
		$res = $stat->s3 ();
		for($i = 0; $i < 20; $i ++) {
			echo $res [$i] ['Nome'];
		}
		break;
	
	case 4 :
		$res = $stat->s4 ();
		for($i = 0; $i < 10; $i ++) {
			echo $res [$i] ['Nome'];
		}
		break;
	
	case 5 :
		$res = $stat->s5 ();
		for($i = 0; $i < 10; $i ++) {
			echo $res [$i] ['Nome'];
		}
		break;
	case 6 :
		$res = $stat->s6 ();
		for($i = 0; $i < 5; $i ++) {
			echo $res [$i] ['Nome'];
		}
		break;
	case 7 :
		$res = $stat->s7 ();
		for($i = 0; $i < 5; $i ++) {
			echo $res [$i] ['conhecedor'];
			echo $res [$i] ['conhecido'];
		}
		break;
}

?>
