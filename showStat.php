<?php
include_once "statistics.php";
include ("Stat.class.php");
include ("pDraw.class.php");
include ("pImage.class.php");
include ("pData.class.php");

$stat = new statistics ();
$i = $_POST ['func'];

switch ($i) {
	// List artist
	case 1 :
		$res = $stat->s1 ();
		echo "Rating Médio: ".$res ["media"].'<br>';
		echo "Desvio Padrão: ".$res ["desvio"];
		break;
	
	case 2 :
		$res = $stat->s2 ();
		echo '<table>';
		echo '<tr align="left"><th>#</th><th>Artista</th><th>Nota</th> <th>#</th><th>Artista</th><th>Nota</th></tr>';
		for($i = 0; $i < 10; $i++) {
			$pos = $i+1;
			$media = number_format($res[$i]['Media'], 2, ',', ' ');
			echo "<tr><td>{$pos}</td><td>{$res[$i]['Nome']}</td><td>{$media}</td>";
			$pos = $pos+10;
			$i2 = $pos-1;
			$media = number_format($res[$i2]['Media'], 2, ',', ' ');
			echo "<td>{$pos}</td><td>{$res[$i2]['Nome']}</td><td>{$media}</td></tr>";
		}
		echo '</table>';
		break;
	case 3 :
		$res = $stat->s3 ();
		echo '<table>';
		echo '<tr align="left"><th>#</th><th>Artista</th><th>Nota</th> <th>#</th><th>Artista</th><th>Nota</th></tr>';
		for($i = 0; $i < 10; $i++) {
			$pos = $i+1;
			$media = number_format($res[$i]['Media'], 2, ',', ' ');
			echo "<tr><td>{$pos}</td><td>{$res[$i]['Nome']}</td><td>{$media}</td>";
			$pos = $pos+10;
			$i2 = $pos-1;
			$media = number_format($res[$i2]['Media'], 2, ',', ' ');
			echo "<td>{$pos}</td><td>{$res[$i2]['Nome']}</td><td>{$media}</td></tr>";
		}
		echo '</table>';
		break;	
	case 4 :
		echo '<table>';
		echo '<tr align="left"><th>#</th><th>Artista</th><th>Likes</th></tr>';
		$res = $stat->s4 ();
		for($i = 0; $i < 10; $i ++) {
			$pos = $i+1;
			echo "<tr><td>{$pos}</td><td>{$res[$i]['Nome']}</td><td>{$res[$i]['Curtidas']}</td></tr>";
		}
		echo '</table>';
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
		echo "Popularidade(1º,2º,3º quartis) = " . implode ( ",	", $quart->quartiles ( $res ) ) . "<br />";
		break;
	case 9 :
		$res = $stat->s9 ();
		
		/* Create your dataset object */
		$myData = new pData ();
		
		/* Add data in your dataset */
		$myData->addPoints ( $res );
		
		/* Create a pChart object and associate your dataset */
		$myPicture = new pImage ( 700, 230, $myData );
		
		/* Define the boundaries of the graph area */
		$myPicture->setGraphArea ( 60, 40, 670, 190 );
		
		$myPicture->setFontProperties ( "Fonts/tahoma.ttf", 8 );
		
		/* Draw the scale, keep everything automatic */
		$myPicture->drawScale ( array (
				"LabelSkip" => 40 
		) );
		
		/* Draw the scale, keep everything automatic */
		$myPicture->drawSplineChart ();
		
		$myPicture->drawLineChart ();
		header ( "Content-Type: image/png" );
		$myPicture->Render ( null );
		echo "Distribuição de Exponencial";
		break;
	case 10 :
		/*gráfico*/
		break;
	case 11 :
		/*gráfico*/
		break;
	case 12 :
		$res = $stat->s12 ();
		
		/* Create your dataset object */
		$myData = new pData ();
		
		/* Add data in your dataset */
		$myData->addPoints ( $res );
		
		/* Create a pChart object and associate your dataset */
		$myPicture = new pImage ( 700, 230, $myData );
		
		/* Define the boundaries of the graph area */
		$myPicture->setGraphArea ( 60, 40, 670, 190 );
		
		$myPicture->setFontProperties ( "Fonts/tahoma.ttf", 8 );
		
		/* Draw the scale, keep everything automatic */
		$myPicture->drawScale ( array (
				"LabelSkip" => 40 
		) );
		
		/* Draw the scale, keep everything automatic */
		$myPicture->drawSplineChart ();
		
		$myPicture->drawLineChart ();
		header ( "Content-Type: image/png" );
		$myPicture->Render ( null );
		echo "Distribuição de Exponencial";
		break;
}

?>
