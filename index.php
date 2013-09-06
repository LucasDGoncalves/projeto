<?php

require "querys.php";

// importa dados da web
$querys = new querys();
echo $querys->addArtist('http://en.wikipedia.org/wiki/Supertramp');
?> 