<!DOCTYPE html>
<!-- 
Team Members: Ana Perez & Naelin Aquino
Date: May 13 2015
Class: CST 205
Description: This is a music interactive web application. It allows the user to choose a song
and do different things with its beat such as play the song every two beats, three beats. etc.
It also allows the user to trail through the entire song. 
-->
<html>

<head>
	<link rel="stylesheet" href="styles.css" type=text/css" >

</head>
<h1>Music Interaction</h1>
<h2>Instructions:</h2>
<p1>1. Select a Song<br></p1>
<p2>2. Click a square to start playing song at that index<br></p2>
<p3>3. Use the arrow keys to trail through the song<br></p3>
<p4>4. Press + to increment the beat<br></p4>
<p5>5. Press - to decrement the beat<br></p5>
<p6>6. Press * to auto play<br></p6>
<br>

<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src='remix.js'></script>
	<script type="text/javascript">


// variables for track analysis using the echonest api
var apiKey = 'CGKZP0UTLWFPUBKCI';
var trackID;
var trackURL;
var remixer;
var player;
var track;
var remixed;

$(function(){ //first button  song: ignition/do you
$('#button').click(function()
	{	
		trackID = 'TRCYWPQ139279B3308';
		trackURL = 'test1.mp3';
		$('#song').text("Ignition / Do You - Phoebe Ryan");
		init();
	});
}); 
$(function() { //second button song: idk the name
	$('#button1').click( function()
	{
		trackID = 'TRBIBEW13936EB37C9';
		trackURL = '1451_-_D.mp3';
		$('#song').text("8-bit song");
		init();
	});
}); 
$(function(){ //third button song: Sail by Awolnation
 	$('#button2').click( function()
	{	
		trackID = 'TRWHQOK13B357AB74A';
		$('#song').text("Sail - Awolnation");
		trackURL = 'Sail.mp3';
		init();
	});
});

$(function(){ //fourth button song: Madness by Muse
	$('#button3').click(function()
	{	
		trackID = 'TRHIUST13B5695A3E4';
		$('#song').text("Madness- Muse");
		trackURL = 'Madness.mp3';
		init();
	});
}); 
$(function(){ //fith button song: Scary Monsters and Nice Sprites by SKRILLEX
	$('#button4').click(function()
	{	
		trackID = 'TRECVNM13B542388C0';
		$('#song').text("Scary Monsters and Nice Sprites - SKRILLEX");
		trackURL = 'Scary Monsters And Nice Sprites.mp3';
		init();
	});
});

// Loads the song, analyzes it, and puts it into an array
function init() {
    var contextFunction = window.AudioContext;
    if (contextFunction === undefined) {
        $("#info").text("Sorry, this app needs advanced web audio. Your browser doesn't"
            + " support it. Try the latest version of Chrome?");
    } else {
        var context = new contextFunction();
        remixer = createJRemixer(context, $, apiKey);
        player = remixer.getPlayer();
		player2 = remixer.getPlayer();
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
				// Gets the length of the array holding the song to be used in PHP  	
				//window.location = "index3.php?length=" + 700;

            }
        });
    }			
}


</script>
<div id='song'> </div>
<div id='info'> </div>
<!-- Buttons for remixer -->
<div id='up' class="up">+</div>
<div id='down' class="down">-</div>
<div id='auto' class="auto">*</div>
<!-- Buttons for song choices -->
<button id="button" class="button">Song 1</button>
<button id="button1" class="button1">Song 2</button>
<button id="button2" class="button2">Song 3</button> 
<button id="button3" class="button3">Song 4</button>
<button id="button4" class="button4">Song 5</button>


<div id='count'></div>
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
if(isset($_GET["length"])) 
{
	$length = $_GET["length"];
	for($i = 1; $i < 30; $i++)
	{
		echo "<tr>";
		for($j = 1; $j < $length / 20; $j++)
		{
			$id++;	
			 // Give each square a unique ID		
			echo '<td id="'.$id.'", style="background-color: ', rColor::generate(), '">','</td>';

		} 
		echo "</tr>";
	}	
}
else
{
	for($i = 1; $i < 30; $i++)
	{
		echo "<tr>";
		for($j = 1; $j < 700 / 20; $j++)
		{
			$id++;	
			 // Give each square a unique ID		
			echo '<td id="'.$id.'", style="background-color: ', rColor::generate(), '">','</td>';

		} 
		echo "</tr>";
	}	
}


?>

<script>

var active = 0;
var origColor = $('#navigate tr td').eq(active).css("background-color");
var prevActive = active;
var counter = 1;
var autoFlag = 2;

$('#navigate td').each(function(idx){$(this).html();});

// When the user presses a key
$(document).keydown(function(e){
    reCalculate(e);
    $('#navigate tr td').eq(active).css("background-color", "#FF0066");
    scrollInView();
    return false;
});

// Displays Skip beat by: 1
$('#count').text("Skip beat by: " + counter);

// Remixer buttons
$('#up').click(function()
{
	counter++;
	$('#count').text("Skip beat by: " + counter);
});

$('#down').click(function()
{
	counter--;
	$('#count').text("Skip beat by: " + counter);
});
// Randomizes the beat
$('#auto').click(function()
{
		setInterval(function() 
		{
			var x = 1 + Math.floor(Math.random() * 4); // 1 to 4
		 	var i = Math.floor(Math.random() * 4); // 0 to 4
			counter = 0;
			if(x >= 2)	
			{	
				counter = counter + i;	
			}
			else
			{
				counter = counter - i;
			}	
			$('#count').text("Skip beat by: " + counter);
		},
		1100);
});

// Highlights the active square
function reCalculate(e){
    var rows = $('#navigate tr').length;
    var columns = $('#navigate tr:eq(0) td').length;

    
    if (e.keyCode == 37) { //move left or wrap

			active = active - 1;
            if (active < 0) {
                active = remixed.length - 1;
            }
            player.play(0, remixed[active]);

    }
    if (e.keyCode == 38) { // move up
			
			active = active - columns;
			//active = active + 1;
            if (active > remixed.length - 1){
                active = 0;
            }

           player.play(0, remixed[active]);
    }
    if (e.keyCode == 39) { // move right or wrap

	        active = active + 1;
            if (active > remixed.length - 1) {
                active = 0;
            }
           player.play(0, remixed[active]);

    }
    if (e.keyCode == 40) { // move down

            active = active + columns;
            if (active < 0) {
                active = remixed.length - 1;
            }
           player.play(0, remixed[active]);

    }
}

// Checks for boundaries
function scrollInView(){
    var target = $('#navigate tr td:eq('+active+')');
    if (target.length)
    {
        var top = target.offset().top;
        
        $('html,body').stop().animate({scrollTop: top-100}, 400);
        return false;
    }
}

var lastClickedTD = null;
var playerCounter = 0;
// When a square is clicked, do this:
$("td").click (function(event){	
	event.target.style.border = "solid #0000FF";
	var id = event.target.id;
  	active = $(this).closest('table').find('td').index(this);
	var origColor = $('td').eq(active).css("background-color");
	playerCounter++;

	if(playerCounter % 2 > 0) {
	 		(function audioLoop (i) 
			{          
				setTimeout(function () 
				{
					$('td').eq(id - counter).css("background-color", origColor);
					origColor = $('td').eq(id).css("background-color");
					$('td').eq(id).css("background-color", "#FF0066");

					player.play(0, remixed[id]); 
					id = parseInt(id);
					id += counter;
			  		
					if(playerCounter % 2 > 0) audioLoop(i);

			   }, 480/*parseInt(remixed[id].track.audio_summary.duration) * 1.7*/)
			})(remixed.length); 
	}	
	// Allows swapping
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
</body>
</html>
