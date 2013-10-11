<?php
include_once "statistics.php";
include ("Stat.class.php");
include ("pDraw.class.php");
include ("pImage.class.php");
include ("pData.class.php");
include ("pScatter.class.php");

$stat = new statistics ();
$i = $_POST ['func'];

switch (10) {
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
		$res = $stat->s4 ();
		echo '<table>';
		echo '<tr align="left"><th>#</th><th>Artista</th><th>Likes</th></tr>';
		for($i = 0; $i < 10; $i ++) {
			$pos = $i+1;
			echo "<tr><td>{$pos}</td><td>{$res[$i]['Nome']}</td><td>{$res[$i]['Curtidas']}</td></tr>";
		}
		echo '</table>';
		break;		
	case 5 :
		$res = $stat->s5 ();
		echo '<table>';
		echo '<tr align="left"><th>#</th><th>Artista</th><th>Desvio</th></tr>';
		for($i = 0; $i < 10; $i ++) {
			$pos = $i+1;
			$desvio = number_format($res[$i]['Desvio'], 5, ',', ' ');
			echo "<tr><td>{$pos}</td><td>{$res[$i]['Nome']}</td><td>{$desvio}</td></tr>";
		}
		echo '</table>';
		break;	
	case 6 :
		$res = $stat->s6 ();
		echo '<table>';
		echo '<tr align="left"><th>#</th><th>Gênero</th><th>Likes</th></tr>';
		for($i = 0; $i < 5; $i ++) {
			$pos = $i+1;
			echo "<tr><td>{$pos}</td><td>{$res[$i]['Genero']}</td><td>{$res[$i]['Count']}</td></tr>";
		}
		echo '</table>';
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
		$myData->addPoints ( $res);
		
		
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
		echo "Distribuição Exponencial";
		break;
	case 10 :
		$res = $stat->s10 ();
		
		/* Create the pData object */
		$myData = new pData ();
		
		/* Create the X axis and the binded series */
		$myData->addPoints ( $res ['qtd'], "Probe 1" );
		$myData->setAxisXY ( 0, AXIS_X );
		$myData->setAxisPosition ( 0, AXIS_POSITION_BOTTOM );
		$myData->setAxisName(0,"Artistas Curtidos");
		
		
		/* Create the Y axis and the binded series */
		$myData->addPoints ( $res ['amount'], "Probe 2" );
		$myData->setSerieOnAxis ( "Probe 2", 1 );
		$myData->setAxisXY ( 1, AXIS_Y );
		$myData->setAxisName(1,"Nº de Pessoas");
		
		
		/* Create the 2nd scatter chart binding */
		$myData->setScatterSerie ( "Probe 1", "Probe 2", 1 );
		$myData->setScatterSerieDescription(0,"This year");
		
		/* Create the pChart object */
		$myPicture = new pImage ( 400, 400, $myData );
		
		/* Write the picture title */
		$myPicture->setFontProperties ( "Silkscreen.ttf", 6 );
		$myPicture->drawText ( 10, 13, "drawScatterLineChart() - Draw a scatter line chart", array (
				"R" => 255,
				"G" => 255,
				"B" => 255 
		) );
		
		/* Add a border to the picture */
		$myPicture->drawRectangle ( 0, 0, 399, 399, array (
				"R" => 0,
				"G" => 0,
				"B" => 0 
		) );
		
		$myPicture->setFontProperties ( "Fonts/tahoma.ttf", 8 );
		
		/* Set the graph area */
		$myPicture->setGraphArea ( 50, 50, 350, 350 );
		
		/* Create the Scatter chart object */
		$myScatter = new pScatter ( $myPicture, $myData );
		
		/* Draw the scale */
		$myScatter->drawScatterScale ();
		
		/* Turn on shadow computing */
		$myPicture->setShadow ( TRUE, array (
				"X" => 1,
				"Y" => 1,
				"R" => 0,
				"G" => 0,
				"B" => 0,
				"Alpha" => 10 
		) );
		
		/* Draw a scatter plot chart */
		$myScatter->drawScatterLineChart ();
		
		/* Draw the legend */
		$myScatter->drawScatterLegend ( 280, 380, array (
				"Mode" => LEGEND_HORIZONTAL,
				"Style" => LEGEND_NOBORDER 
		) );
		
		/* Render the picture (choose the best way) */
		$myPicture->autoOutput ( "pictures/example.drawScatterLineChart.png" );
		echo "Distribuição Gaussiana";
		break;
	case 11 :
		$res = $stat->s11 ();
		
		/* Create the pData object */
		$myData = new pData ();
		
		/* Create the X axis and the binded series */
		$myData->addPoints ( $res ['qtd'], "Probe 1" );
		$myData->setAxisXY ( 0, AXIS_X );
		$myData->setAxisPosition ( 0, AXIS_POSITION_BOTTOM );
		$myData->setAxisName(0,"Curtidas");
		
		/* Create the Y axis and the binded series */
		$myData->addPoints ( $res ['amount'], "Probe 2" );
		$myData->setAxisXY ( 1, AXIS_Y );
		$myData->setSerieOnAxis ( "Probe 2", 1 );
		$myData->setAxisName(1,"Nº de Artistas");
		
		/* Create the 2nd scatter chart binding */
		$myData->setScatterSerie ( "Probe 1", "Probe 2", 1 );
		
		/* Create the pChart object */
		$myPicture = new pImage ( 400, 400, $myData );
		
		/* Write the picture title */
		$myPicture->setFontProperties ( "Silkscreen.ttf", 6 );
		$myPicture->drawText ( 10, 13, "drawScatterLineChart() - Draw a scatter line chart", array (
				"R" => 255,
				"G" => 255,
				"B" => 255 
		) );
		
		/* Add a border to the picture */
		$myPicture->drawRectangle ( 0, 0, 399, 399, array (
				"R" => 0,
				"G" => 0,
				"B" => 0 
		) );
		
		$myPicture->setFontProperties ( "Fonts/tahoma.ttf", 8 );
		
		/* Set the graph area */
		$myPicture->setGraphArea ( 50, 50, 350, 350 );
		
		/* Create the Scatter chart object */
		$myScatter = new pScatter ( $myPicture, $myData );
		
		/* Draw the scale */
		$myScatter->drawScatterScale ();
		
		/* Turn on shadow computing */
		$myPicture->setShadow ( TRUE, array (
				"X" => 1,
				"Y" => 1,
				"R" => 0,
				"G" => 0,
				"B" => 0,
				"Alpha" => 10 
		) );
		
		/* Draw a scatter plot chart */
		$myScatter->drawScatterLineChart ();
		
		/* Draw the legend */
		$myScatter->drawScatterLegend ( 280, 380, array (
				"Mode" => LEGEND_HORIZONTAL,
				"Style" => LEGEND_NOBORDER 
		) );
		
		/* Render the picture (choose the best way) */
		$myPicture->autoOutput ( "pictures/example.drawScatterLineChart.png" );
		echo "Distribuição Exponencial";
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
		echo "Distribuição Exponencial";
		break;
}

?>
