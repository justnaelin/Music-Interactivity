<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="styles.css" type=text/css" >
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</head>

<body>
<table>
<?php
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

for($i = 1; $i < 31; $i++)
{

	echo "<tr>";
	for($j = 1; $j < 51; $j++)
	{	
		$id = $i*$j;
    	echo '<td id="'.$id.'", style="background-color: ', rColor::generate() , '">','</td>';
	} 
	echo "</tr>";
}
?>

<script>
var lastClickedTD = null;
$("td").click (function(event){
	event.target.style.border = "solid #0000FF";
	if(event.target == lastClickedTD)
	{
		event.target.style.border = "";
		lastClickedTD = null;
	}
	else if(lastClickedTD == null)
	{	
		lastClickedTD = event.target;
	}
	else
	{
		var tempcolor;
		var tempID;
		tempcolor = event.target.style.backgroundColor;
		tempID = event.target.id;
		event.target.style.backgroundColor = lastClickedTD.style.backgroundColor;
		event.target.id = lastClickedTD.id;
		lastClickedTD.style.backgroundColor = tempcolor;
		lastClickedTD.id = tempID;
		event.target.style.border = "solid #0000FF";
		lastClickedTD.style.border = "";
		lastClickedTD = null;
	}
		
	
})
</script>
</table>
</body>
</html>
