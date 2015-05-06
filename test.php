<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="styles.css" type=text/css" >
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>

<body>


<table id="navigate">


<?php
// Randomize grid colours
class rColor
{
    protected static $hue;
    protected static $initiated = false;

    protected static function initiate()
    {
        if (!rColor::$initiated)
        {
            rColor::$hue = mt_rand() / mt_getrandmax();
            rColor::$initiated = true;
        }
    }

    protected static function HSVtoRGB($hue, $saturation, $v)
    {
            $h_i = floor($hue * 6);
            $f   = $hue * 6 - $h_i;
            $p   = $v * (1 - $saturation);
            $q   = $v * (1 - $f * $saturation);
            $t   = $v * (1 - (1 - $f) * $saturation);
            $r   = 255;
            $g   = 255;
            $b   = 255;

        switch($h_i)
        {
            case 0: $r = $v ; $g = $t ; $b = $p;        break;
            case 1: $r = $q ; $g = $v ; $b = $p;        break;
            case 2: $r = $p ; $g = $v ; $b = $t;        break;
            case 3: $r = $p ; $g = $q ; $b = $v;        break;
            case 4: $r = $t ; $g = $p ; $b = $v;        break;
            case 5: $r = $v ; $g = $p ; $b = $q;        break;
        }

        return array(floor($r * 256),
                     floor($g * 256),
                     floor($b * 256));
    }

    protected static function padHEX($str)
    {
        $hexwidth = 2;

        if(strlen($str) > $hexwidth)
            return $str;
        else
            return str_pad($str, $hexwidth, '0', STR_PAD_LEFT);
    }

    public static function generate($hex = true, $saturation = 0.5, $value = 0.95)
    {
        rColor::initiate();

        $goldenRatio = 0.618033988749895;

        rColor::$hue += $goldenRatio;
        rColor::$hue = fmod(rColor::$hue, 1);

        if(!is_numeric($saturation))
            $saturation = 0.5;

        if(!is_numeric($value))
            $value = 0.95;

        $rgb = rColor::HSVtoRGB(rColor::$hue , $saturation, $value);

        if($hex)
            return "#" . rColor::padHex(dechex($rgb[0]))
                       . rColor::padHex(dechex($rgb[1]))
                       . rColor::padHex(dechex($rgb[2]));
        else
            return $rgb;
    }

}

// Setup grid
for($i = 1; $i < 31; $i++)
{
	echo "<tr>";
	for($j = 1; $j < 653 / 21; $j++)
	{	
		$id = $i*$j; // Give each square a unique ID
    	//echo '<td class="active" id="'.$id.'">','</td>';
echo '<td id="'.$id.'", style="background-color: ', rColor::generate(), '">', '</td>';
	} 
	echo "</tr>";
}
?>

</table>

<script>
var active = 0;
var origColor = $('#navigate tr td').eq(active).css("background-color");
var prevActive = null;

$('#navigate td').each(function(idx){$(this).html();});
rePosition();

$(document).keydown(function(e){
    $('#navigate tr td').eq(0).css("background-color", "#FFFFFF");
    reCalculate(e);
    rePosition();
    return false;
});
    
$('td').click(function(){
   active = $(this).closest('table').find('td').index(this);
   rePosition();
});


function reCalculate(e){
    var rows = $('#navigate tr').length;
    var columns = $('#navigate tr:eq(0) td').length;
    //alert(columns + 'x' + rows);
    
    if (e.keyCode == 37) { //move left or wrap
		prevActive = active;        
		active = (active>0)?active-1:active;
    }
    if (e.keyCode == 38) { // move up
		prevActive = active;        
		active = (active-columns>=0)?active-columns:active;
    }
    if (e.keyCode == 39) { // move right or wrap
		prevActive = active;       
		active = (active<(columns*rows)-1)?active+1:active;
    }
    if (e.keyCode == 40) { // move down
		prevActive = active;        
		active = (active+columns<=(rows*columns)-1)?active+columns:active;
    }
}

function rePosition(){
    //$('.active').removeClass('active');
	$('#navigate tr td').eq(prevActive).css("background-color", "#FFFFFF");
    //$('#navigate tr td').eq(active).addClass('active');
	//origColor = $('#navigate tr td').eq(active).css("background-color");
    $('#navigate tr td').eq(active).css("background-color", "#FF0066");
    scrollInView();
}

function scrollInView(){
    var target = $('#navigate tr td:eq('+active+')');
    if (target.length)
    {
        var top = target.offset().top;
        
        $('html,body').stop().animate({scrollTop: top-100}, 400);
        return false;
    }
}

/*
var active;
$("td").click (function(event){

	document.addEventListener('keydown', function(e){
		active = $('td.active').removeClass('active');
		var x = active.index();
		var y = active.closest('tr').index();
		if (e.keyCode == 37) { 
		   x--;

		}
		if (e.keyCode == 38) {
		    y--;

		}
		if (e.keyCode == 39) { 
		    x++

		}
		if (e.keyCode == 40) {
		    y++

		}


		active = $('tr').eq(y).find('td').eq(x).addClass('active');
		//document.getElementById('td').style.backgroundColor = "red";


	});*/


//});
</script>
</body>
</html>
