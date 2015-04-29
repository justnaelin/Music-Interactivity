<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="styles.css" type=text/css" >
</head>
<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src='remix.js'></script>
	<script type="text/javascript">

var apiKey = 'GWRU3T1ZZ0D0FDZRI';
var trackID = 'TRCYWPQ139279B3308';
var trackURL = 'test.mp3'

var remixer;
var player;
var track;
var remixed;

function init() {
    var contextFunction = window.AudioContext || window.webkitAudioContext;
    if (contextFunction === undefined) {
        $("#info").text("Sorry, this app needs advanced web audio. Your browser doesn't"
            + " support it. Try the latest version of Chrome?");
    } else {
        var context = new contextFunction();
        remixer = createJRemixer(context, $, apiKey);
        player = remixer.getPlayer();
        $("#info").text("Loading analysis data...");

        remixer.remixTrackById(trackID, trackURL, function(t, percent) {
            track = t;

            $("#info").text(percent + "% of the track loaded");
            if (percent == 100) {
                $("#info").text(percent + "% of the track loaded, remixing...");
            }

            if (track.status == 'ok') {
                remixed = new Array();
                // Do the remixing here!
                for (var i=0; i < track.analysis.beats.length; i++) {
                        remixed.push(track.analysis.beats[i])
                }
                $("#info").text("Remix complete!");
            }
        });
    }
}

window.onload = init;
</script>

<table>
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
$id = 0;
for($i = 1; $i < 31; $i++)
{
	echo "<tr>";
	for($j = 1; $j < 51; $j++)
	{	
		$id = $i*$j; // Give each square a unique ID
    	echo '<td id="'.$id.'", style="background-color: ', rColor::generate() , '">','</td>';
	} 
	echo "</tr>";
}
?>

<script>
// Allows swapping of squares
var lastClickedTD = null;
// When a square is clicked, do this:
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
		event.target.style.border = "";
		lastClickedTD.style.border = "";
		lastClickedTD = null;
	}	
})

</script>

</table>

<div id='info'> </div>
<!--<td onClick="player.play(0, remixed);"></td>
<td onClick="player.stop()">Stop!</td>-->
</body>
</html>
