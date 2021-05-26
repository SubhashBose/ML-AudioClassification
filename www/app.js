//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var input; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordButton = document.getElementById("recordButton");
var output=document.getElementById("output")

recordButton.onclick=startRecording;

document.getElementById("opt_record").onclick=function(){
	recordButton.onclick=startRecording;
	recordButton.innerHTML="Start Recording"	
	document.getElementById("audiofile").classList.add("hide");
}
document.getElementById("opt_upload").onclick=function(){
	recordButton.onclick=uploadfile;
	recordButton.innerHTML="Upload"
	document.getElementById("audiofile").classList.remove("hide");
}

function recordon(state){
    if (state){
        recordButton.innerHTML="Stop Recording"
        recordButton.classList.toggle("recordon")
        recordButton.onclick=stopRecording;
        output.innerHTML="<img src='recording.gif' id='recordgif'/>"
		document.getElementById("opt_record").disabled=true
		document.getElementById("opt_upload").disabled=true
    }
    else{
        recordButton.innerHTML="Start Recording"
        recordButton.classList.toggle("recordon")
        recordButton.onclick=startRecording;
        output.innerHTML='';
		document.getElementById("opt_record").disabled=false
		document.getElementById("opt_upload").disabled=false
    }
}

var start_time=0

function startRecording() {
	console.log("start");

	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/
    
    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia() 
	*/

	//recordButton.disabled = true;
    recordon(true)

	/*
    	We're using the standard promise based getUserMedia() 
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		start_time=new Date().getTime()/1000
		console.log("Microphone access success");

		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device
		*/
		audioContext = new AudioContext();

		//update the format 
		document.getElementById("formats").innerHTML="Recording Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

		/*  assign to gumStream for later use  */
		gumStream = stream;
		
		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);

		/* 
			Create the Recorder object and configure to record mono sound (1 channel)
			Recording 2 channels  will double the file size
		*/
		rec = new Recorder(input,{numChannels:1})

		//start the recording process
		rec.record()

		console.log("Recording started");

	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	//recordButton.disabled = false;
        recordon(false)
	});
}


function stopRecording() {
	console.log("stop");

	//disable the stop button, enable the record too allow for new recordings
    recordon(false)
	
	//tell the recorder to stop the recording
	rec.stop();

	//stop microphone access
	gumStream.getAudioTracks()[0].stop();

	if (new Date().getTime()/1000 -start_time <=10.5){
		output.innerHTML="Recording length is too small. Please record longer than 10 seconds."
		return
	}
	//create the wav blob and pass it on to createDownloadLink
	rec.exportWAV(submitaudio);
}

function submitaudio(blob){
    recordButton.disabled = true;
    loader_im='<img src="loading.gif" id="loader"/> '
    output.innerHTML=loader_im+'Please wait: Uploading the audio... <span id="percentload"></span>'
    var filename = new Date().toISOString()+'.wav'
    var xhr=new XMLHttpRequest();
		  xhr.onload=function(e) {
		      if(this.readyState === 4 && this.status == 200) {
		          //console.log("Server returned: ",e.target.responseText);
                  output.innerHTML=e.target.responseText
		      }
              else
                output.innerHTML='Failed: Some error occured during submission';
                
            recordButton.disabled = false;
		  };
          xhr.onerror = function() { // only triggers if the request couldn't be made at all
            output.innerHTML='Failed: Some error occured during submission';
          };
          xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var percentComplete = Math.floor(e.loaded / e.total*100);
                if (percentComplete<100)
                    document.getElementById("percentload").innerHTML=percentComplete+"%";
                else
                    output.innerHTML = loader_im+'Please wait: Analyzing the audio...'
                // ...
              } else {
                // Unable to compute progress information since the total size is unknown
                output.innerHTML = loader_im+'Please wait: Analyzing the audio... '
              }
          };
		  var fd=new FormData();
		  fd.append("audio_data",blob, filename);
		  xhr.open("POST","",true);
		  xhr.send(fd);
}


function uploadfile(){
	file=document.getElementById("audiofile").files[0]
	if (file instanceof Blob){
		submitaudio(file)
		document.getElementById("audiofile").value=''
	}
	else
		alert("Select a file first.")
}