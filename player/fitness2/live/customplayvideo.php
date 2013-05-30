<html>
<head>

<script type='text/javascript' src='js/jquery-1.7.1.min.js'></script>
<script type='text/javascript' src='jwplayer/jwplayer.js'></script>

</head>
<body style="height:350px;">

<video id="fitnessvideo" width="320"  height="240"  preload="auto">
</video>

<script type="text/javascript">

$(document).ready(function(){

	
playVideos('<?php echo $_POST["siteurl"] ?>','<?php echo $_POST["videoStr"] ?>','<?php echo $_POST["repStr"] ?>',<?php echo $_POST["user_id"] ?>,<?php echo $_POST["workout_id"] ?>,<?php echo $_POST["upgrade"] ?>);
     
   });    
   
   
   
   function createXMLHttpRequest() {  
	        
	            	 var ua;  
	        
	            	 if(window.XMLHttpRequest) {  
	  
	            	 try {  
	    
	            	  ua = new XMLHttpRequest();  
	        
	
	            	 } catch(e) {  
	        
	
	            	  ua = false;  
	          
	            	 }  
	        
	            	 } else if(window.ActiveXObject) {  
	        
	            	  try {  
	        
	            	    ua = new ActiveXObject("Microsoft.XMLHTTP");  
	        
	       
	            	  } catch(e) {  
	        
	            	    ua = false;  
	                
	            	  }  
	                
	            	 }  
               
          
            	return ua;  
             
           
           } 

   
   function playVideos(mainsiteurl,movieStr,repStr,userid,workoutid,upgrade)
   {
     var mainUrl  = mainsiteurl;
   	var siteurl  =  mainsiteurl + '/public/js_old/old_files/web/';
   var repetition =  repStr.split(",");
   
//var files     = new Array('http://server.fitness4.me:82/testing/player/files/posters/lunge.mp4','http://server.fitness4.me:82/testing/player/files/1.mp4','http://server.fitness4.me:82/testing/player/files/posters/squat.mp4','http://server.fitness4.me:82/testing/player/files/5.mp4');
var files       = movieStr.split(",");

 var count = 0;	
 var reps = repetition[count];

_player = $("video#fitnessvideo");

 _media = $("video#fitnessvideo").get(0);

 var sources = _media.getElementsByTagName('source');
 var req = createXMLHttpRequest();  
 var movieLength ="";
 var curTime="";

jwplayer("fitnessvideo").setup({
flashplayer: "jwplayer/player.swf",
height:330,
width:620,
screencolor:'ffffff',
skin: 'glow/glow.xml',
file:siteurl+ files[count],
events: {
onComplete: function(event) {
	
	

	
if(reps == repetition[count])
	{
		var currentDuration = jwplayer().getDuration();
		req.open("GET","ajax_customworkoutUpdate.php?userid="+userid+"&workoutid="+workoutid+"&timeDone="+currentDuration,false);
	    req.send("userid="+userid+"&workoutid="+workoutid+"&timeDone="+currentDuration);
		 reps = 1;
	     count++;
	     
	    
	     if(files[count])
	     {
	     jwplayer().load(siteurl+files[count])
	     jwplayer().play();
	     }
	     else
	     {   
	     	  count =0;
	     	
			  if(upgrade==1)
			  window.location.href = mainUrl+"/user/membership/";
			  else
			  jwplayer().load(siteurl+files[count]);
		     
	     }
		
	    
	}
	else
	{
		
	  
			jwplayer().play();
			reps++;
		
	
	}
  
 
 },
 onReady: function(event){
 	
 	//jwplayer().getPlugin("controlbar").hide();
 },
 onPlay: function(event) {
 	

 //snd.play();
 
 },
 onPause: function(event) {
 
//snd.pause();
 
 },
 onBuffer: function(event) {
 
//snd.pause();
 
 },
 onTime:function(event) {
 movieLength = jwplayer().getDuration();
 curTime     = jwplayer().getPosition();
 if((repetition[count] != 1) && (reps < repetition[count]))
 {
 	
	 
	 // when movie gets near end, use a SEEK event to recycle
// must SEEK back to start BEFORE end of movie - otherwise it stops
if (curTime >= (movieLength - 0.1) && (reps != repetition[count]))
{
reps++;
jwplayer().seek(0.1);
//$("fitnessvideo").trigger("ended");
}
 }
 	
 }
 
 
 
 }
 
     });
   }
</script>

</body>
</html>
<script>
function showControls()
{
	 document.getElementById('fitnessvideo').controls= "controls";
	
}

function hideControls()
{
	 document.getElementById('fitnessvideo').controls= "";
	
}
</script>