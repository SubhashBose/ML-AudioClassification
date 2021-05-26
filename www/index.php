<?php
/*Author: Subhash Bose
  Date: May 22, 2021
  Version: 1.0
  Description: Web interace for Machine learning Music genere classification project.
  The JS audio recording library Recorder.js has been used.
*/

if(isset($_FILES['audio_data'])){

$size = $_FILES['audio_data']['size']; 
$input = $_FILES['audio_data']['tmp_name'];
$output = $_FILES['audio_data']['name']; 

//move the file from temp name to local folder using $output name
move_uploaded_file($input, $output);
//sleep(5);
$res=shell_exec("./exec.sh www/".$output);
//print_r($res);
$res=json_decode($res, true);


echo "<p style='font-size:2rem;'>The most probable genre is <b>".strtoupper($res['genre'])."</b></p><br/>";
echo 'Probabilty chart for genres<br/><img src="data:image/png;base64, '.$res['prob_fig'].'"  style="width:100%;max-width:400px;"/>';

unlink($output);
die();
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Machine learning classifier for Music Genres</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="prefetch" href="loading.gif">
    <link rel="preload" href="loading.gif">
    <link rel="prefetch" href="recording.gif">
    <link rel="preload" href="recording.gif">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <style>
a {
  color: #337ab7;
}
p {
  margin-top: 1rem;
}
a:hover {
  color:#23527c;
}
a:visited {
  color: #8d75a3;
}

body {
	line-height: 1.5;
	font-family: sans-serif;
	word-wrap: break-word;
	overflow-wrap: break-word;
	color:black;
	margin:2em;
    /*background-color:#000;*/
}

h1 {
	/*text-decoration: underline red;*/
	text-decoration-thickness: 3px;
	text-underline-offset: 6px;
	font-size: 220%;
	font-weight: bold;
}

h2 {
	font-weight: bold;
	color: #005A9C;
	font-size: 140%;
	text-transform: uppercase;
}

red {
	color: red;
}

#loader{
    height:3rem;
}

#recordgif{
    height:7rem;
}

#container{
    width:100%;
    max-width:600px;
    text-align: center;
    background-color:#fff;
    /*height: 100vh;*/
    margin: auto;
}

#controls {
  display: flex;
  margin-top: 2rem;
  /*max-width: 28em;*/
  flex-direction: column;
  font-size:1.5rem;
} 

button {
  flex-grow: 1;
  height: 5.5rem;
  min-width: 2rem;
  border: none;
  border-radius: 0.15rem;
  background: #269d13;
  margin-left: 2px;
  box-shadow: inset 0 -0.15rem 0 rgba(0, 0, 0, 0.2);
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  color:#ffffff;
  font-weight: bold;
    font-size: 2rem;
    border-radius: 1000px;
}

.recordon{
    background: #e52e2e;
}

.hideuploader{
  display:none;
}

#output{
  font-size: 1.5rem;
}

button:hover, button:focus {
  outline: none;
  /*background: #c72d1c;*/
}

button::-moz-focus-inner {
  border: 0;
}

button:active {
  box-shadow: inset 0 1px 0 rgba(0, 0, 0, 0.2);
  line-height: 3rem;
}

button:disabled {
  pointer-events: none;
  background: #5e765a;
}
button:first-child {
  margin-left: 0;
}

audio {
  display: block;
  width: 100%;
  margin-top: 0.2rem;
}

li {
  margin-bottom: 1rem;
}

#formats {
  margin-top: 0.5rem;
  font-size: 80%;
}

</style>
  </head>
  <body>
  <div id="container">
  <h1>Music Genre Classification</h1>
  <p>Machine learning based classifier for Music Genres.</p>
    <div id="controls">
      <div><b>Mode:</b> &nbsp; &nbsp;
    <input type="radio" id="opt_record" name="upload_mode" value="record" checked>
    <label for="male">Record audio</label> &nbsp;&nbsp;
    <input type="radio" id="opt_upload" name="upload_mode" value="file">
    <label for="female">Uplod File</label><br>
    </div>
      <div style="with:100%">
    <input type="file" name="audiofile" id="audiofile" accept=".wav,.mp3" class="hide">
    </div><br/>
  	 <button id="recordButton">Start Recording</button>
    </div>
    <div id="formats">&nbsp;</div>
  	<br/>
  	<div id="output">
      <div style="text-align:left;">
      <br/>
      <b>Note:</b>
      <ul>
  <li>Please ensure the recorded sound is loud and noise free.</li>
  <li>Use external microphone for recoding. In most mobile deviced inbuilt microhones inputs are optimized for voices and so the musical notes will get supressed.</li>
     </ul>
    </div>
     </div>
  	<script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
  	<script src="app.js"></script>
  </div>
  </body>
</html>