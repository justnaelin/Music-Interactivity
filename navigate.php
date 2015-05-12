<!DOCTYPE html>
<html>
<head>
<body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src='remix.js'></script>
	<script type="text/javascript">
var trackID;
var trackURL;
var apiKey = 'CGKZP0UTLWFPUBKCI';

var remixer;
var player;
var track;
var remixed;

function pickFirstSong() {
	if(document.getElementById("1"))
	{	trackID = 'TRCYWPQ139279B3308';
		trackURL = 'test.mp3';
		document.write("Hi i made it in the if statement");
		document.write(trackID);
		document.write(trackURL);
		window.onload=init();
	}
}
function pickASong()
{	if(document.getElementById("2"))
	{	trackID = 'TRBIBEW13936EB37C9';
		trackURL = '1451_-_D.mp3';
		document.write("Hi i made it 2nd in the if statement");
		document.write(trackID);
		document.write(trackURL);
		window.onload=init();
	}
  //  document.getElementById("demo").innerHTML = "Paragraph changed.";
	//trackID = 
	
}
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
		player.play(0, remixed);
        });
    }
}
</script>
</head>

<body>

<h1>Select a song</h1>
<button id="1" type="button" onclick="pickFirstSong()">unknown</button>
<button id="2" type="button" onclick="pickASong()">Awolnation-Sail</button>
</body>
</html>
