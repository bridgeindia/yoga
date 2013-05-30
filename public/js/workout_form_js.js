
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



function validateWorkoutForm() {
	

	
var homelist = "";

for(var i = 0; i < document.workoutForm.home.length; i++){
if(document.workoutForm.home[i].checked) {
homelist += document.workoutForm.home[i].value + ",";
}
}
document.workoutForm.home_equipments.value=homelist;

var naturelist = "";

for(var i = 0; i < document.workoutForm.nature.length; i++){
if(document.workoutForm.nature[i].checked) {
naturelist += document.workoutForm.nature[i].value + ",";
}
}
document.workoutForm.nature_equipments.value=naturelist;

var hotellist = "";

for(var i = 0; i < document.workoutForm.hotel.length; i++){
if(document.workoutForm.hotel[i].checked) {
hotellist += document.workoutForm.hotel[i].value + ",";
}
}
document.workoutForm.hotel_equipments.value=hotellist;

var officelist = "";

for(var i = 0; i < document.workoutForm.office.length; i++){
if(document.workoutForm.office[i].checked) {
officelist += document.workoutForm.office[i].value + ",";
}
}
document.workoutForm.office_equipments.value=officelist;


var musclelist = "";

for(var i = 0; i < document.workoutForm.secondary_muscle.length; i++){
if(document.workoutForm.secondary_muscle[i].selected) {
musclelist += document.workoutForm.secondary_muscle[i].value + ",";
}
}
document.workoutForm.sec_muscles.value=musclelist;




if(document.workoutForm.workout_name.value == "")
{
	alert("Please select a workout name")
	return false;
}
if(exerciselist == "")
{
	alert("Please select an exercise")
	return false;
}
if(document.workoutForm.primary_muscle.value == "")
{
	alert("Please select a primary muscle")
	return false;
}
if(musclelist == "")
{
	alert("Please select a secondary muscle")
	return false;
}

if(isNaN(document.workoutForm.timeframes.value))
{
	alert("Please enter a numeric value for the duration")
	return false;
}


return true;
}



function validateWorkForm()
{
	var exerciselist = "";

for(var i = 0; i < document.addWork.exercises.length; i++){
if(document.addWork.exercises[i].selected) {
exerciselist += document.addWork.exercises[i].value + ",";
}
}

document.addWork.exerciseslist.value=exerciselist;	


var filterlist = "";

for(var i = 0; i < document.addWork.filter.length; i++){
if(document.addWork.filter[i].checked) {
filterlist += document.addWork.filter[i].value + ",";
}
}

document.addWork.filterlist.value=filterlist;	
	
	if(document.addWork.work_name.value == "")
	{
		alert("Please enter a name for the workout")
		return false;
	}
	
	if(document.addWork.work_duration.value == "")
	{
		alert("Please enter a duration for the workout")
		return false;
	}
	
	if(document.addWork.exerciseslist.value == "")
	{
		alert("Please select an exercise")
		return false;
	}
	if(document.addWork.filterlist.value == "")
	{
		alert("Please select a Body Area")
		return false;
	}
	if(isNaN(document.addWork.work_duration.value))
		{
			alert("Please enter a numeric value for the duration")
			return false;
         }
         
         
return true;
}
function loadExercises(level,exercises,siteurl)
{
    var req = createXMLHttpRequest();  
	req.open("GET",siteurl+"/player/ajax_exercisefilter.php?exercises="+exercises+"&level="+level,false);
    req.send("exercises="+exercises+"&level="+level);
	
	if(req.readyState == 4){
	
	document.getElementById('ajaxExercises').innerHTML = req.responseText;
}
	
}

function ajax_getTime(siteurl)
{
	var req = createXMLHttpRequest();  
	
	var exerciselist = "";
var j=0;
for(var i = 0; i < document.addWork.exercises.length; i++){
if(document.addWork.exercises[i].selected) {
exerciselist += document.addWork.exercises[i].value + ",";
j=j+1;
}
}

var recoveryTime  = document.addWork.work_recovery_time.value;
var recoveryInterval = (j/parseInt(document.addWork.work_recovery_interval.value)) - 1;

var totalRecoveryTime = (parseInt(recoveryTime) * parseInt(recoveryInterval))  ;

var level      = document.addWork.work_level.value;

req.open("GET",siteurl+"/player/ajax_workoutUpdate.php?exercises="+exerciselist+"&recoveryTime="+totalRecoveryTime+"&level="+level,false);
req.send("exercises="+exerciselist+"&recoveryTime="+totalRecoveryTime+"&level="+level);

if(req.readyState == 4){
	
	document.getElementById('totaltime').innerHTML = req.responseText;
}
}