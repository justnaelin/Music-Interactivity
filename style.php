<?php
header("Content-type: text/css");
function getRandomColour() {
	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
	$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
	return $color;
}
?>

td {
width: 50px;
height: 50px;
background-color: <?php echo getRandomColour(); ?>;
}

