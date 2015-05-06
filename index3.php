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
var trackURL = 'test.mp3';

var remixer;
var player;
var track;
var remixed;

function init() {
    var contextFunction = window.webkitAudioContext || window.AudioContext;
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
                for (var i=0; i < track.analysis.beats.length; i++) {
                    remixed.push(track.analysis.beats[i])
                }
                $("#info").text("Remix complete!");

            }
        });
    
    }
				//window.location = "index3.php?length=" + remixed.length;

}
window.onload = init;

</script>
<div id='info'> </div>
<table id='navigate'>
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
//if(isset($_GET["length"])) 
//{
	//$length = $_GET["length"];
	for($i = 1; $i < 30; $i++)
	{
		echo "<tr>";
		for($j = 1; $j < 653 / 30; $j++)
		{	
			$id = $i*$j; // Give each square a unique ID
			echo '<td id="'.$id.'", style="background-color: ', rColor::generate(), '">', '</td>';
			//echo '<td id="id", onClick="player.play(0, remixed);"></td>'
		} 
		echo "</tr>";
	}	
//}

?>

<script>
var active = 0;
var origColor = $('#navigate tr td').eq(active).css("background-color");
var prevActive = active;

$('#navigate td').each(function(idx){$(this).html();});
rePosition();

$(document).keydown(function(e){
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
    $('.active').css("background-color", origColor);
	//$('.active').removeClass('active');
	//$('#navigate tr td').eq(prevActive).css("background-color", origColor);
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

// Allows swapping of squares
var lastClickedTD = null;

// When a square is clicked, do this:
$("td").click (function(event){
	event.target.style.border = "solid #0000FF";
	var id = event.target.id;
	player.play(0, remixed[id]);

	// Set up the keyboard controls after square is clicked
  document.addEventListener('keydown', function(e){
    active = $('td.active').removeClass('active');
    var x = active.index();
    var y = active.closest('tr').index();


		if (e.which == 39) {  // right arrow
           	id = id + 1;
            if (id > remixed.length - 1) {
                id = 0;
            }
            player.play(0, remixed[id]);

        }

        if (e.which == 37) {  // left arrow
            id = id - 1;
            if (id < 0) {
                id = remixed.length - 1;
            }
            player.play(0, remixed[id]);

        }

        if (e.which == 40) {  // down arrow
            id = id - 4;
            if (id < 0) {
                id = remixed.length - 1;
            }
            player.play(0, remixed[id]);

			
        }

        if (e.which == 38) {  // up arrow
            id = id + 4;
            if (id > remixed.length - 1) {
                id = 0;
            }

            player.play(0, remixed[id]);


        }
		active = $('tr').eq(y).find('td').eq(x).addClass('active');



});
	
	
/*
 		(function audioLoop (i) 
		{          
			setTimeout(function () 
			{
			player.play(0, remixed[id]); 
			id++; 

					  	
			  if (--i) audioLoop(i);     
		   }, 479)
		})(remixed.length); 

*/
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


<!--<td onClick="player.play(0, remixed);"
</td>
<td onClick="player.stop()">Stop!</td>-->
</body>
</html>
