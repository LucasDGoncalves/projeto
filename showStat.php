<?php
include_once "statistics.php";
include ("Stat.class.php");
include ("pDraw.class.php");
include ("pImage.class.php");
include ("pData.class.php");

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
	
	case 8 :
		$res = $stat->s9 ();
		$quart = new Stat ();
		echo "quartile(25th, 50th, 75th percentile) = " . implode ( ",	", $quart->quartiles ( $res ) ) . "<br />";
		break;
	case 9 :
		/*gráfico*/
		break;
	case 10 :
		/*gráfico*/
		break;
	case 11 :
		/*gráfico*/
		break;
}
$res = $stat->s9 ();

/* Create your dataset object */
$myData = new pData();

/* Add data in your dataset */
$myData->addPoints($res);

/* Create a pChart object and associate your dataset */
$myPicture = new pImage(700,230,$myData);

/* Define the boundaries of the graph area */
$myPicture->setGraphArea(60,40,670,190);

/* Draw the scale, keep everything automatic */
$myPicture->drawScale();

/* Draw the scale, keep everything automatic */
$myPicture->drawSplineChart();

/* Build the PNG file and send it to the web browser */
$myPicture->Stroke();

?>
