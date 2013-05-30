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
		   
		   
function setCustomFavStatus(siteurl,userid,workoutid,status,elementId)
{
	var req = createXMLHttpRequest(); 
	req.open("GET",siteurl+"/player/updateCustomFavWorkout.php?user_id="+userid+"&workout_id="+workoutid+"&status="+status,false);
    req.send("user_id="+userid+"&workout_id="+workoutid+"&status="+status); 
	
	if(req.readyState == 4)
	{
		if(req.responseText == 1)
		{
			document.getElementById(elementId).className = "favorite";
		}
		else
		{
			document.getElementById(elementId).className = "smiley";
		}
	}
	
}

	
	
function setCustomSingleFavStatus(siteurl,userid,workoutid,status,elementId)
{
	var req = createXMLHttpRequest(); 
	req.open("GET",siteurl+"/player/updateCustomFavWorkout.php?user_id="+userid+"&workout_id="+workoutid+"&status="+status,false);
    req.send("user_id="+userid+"&workout_id="+workoutid+"&status="+status); 
	
	if(req.readyState == 4)
	{
		if(req.responseText == 1)
		{
			document.getElementById(elementId).className = "favorite";
		}
		else
		{
			document.getElementById(elementId).className = "smiley";
		}
	}
	
}		   
		   
		   
function setFavStatus(siteurl,userid,workoutid,status,elementId)
{
	var req = createXMLHttpRequest(); 
	req.open("GET",siteurl+"/player/updateFavWorkout.php?user_id="+userid+"&workout_id="+workoutid+"&status="+status,false);
    req.send("user_id="+userid+"&workout_id="+workoutid+"&status="+status); 
	
	if(req.readyState == 4)
	{
		if(req.responseText == 1)
		{
			document.getElementById(elementId).className = "favorite";
		}
		else
		{
			document.getElementById(elementId).className = "smiley";
		}
	}
	
}


function setSingleFavStatus(siteurl,userid,workoutid,status,elementId)
{
	var req = createXMLHttpRequest(); 
	req.open("GET",siteurl+"/player/updateFavWorkout.php?user_id="+userid+"&workout_id="+workoutid+"&status="+status,false);
    req.send("user_id="+userid+"&workout_id="+workoutid+"&status="+status); 
	
	if(req.readyState == 4)
	{
		if(req.responseText == 1)
		{
			document.getElementById(elementId).className = "favorite";
		}
		else
		{
			document.getElementById(elementId).className = "smiley";
		}
	}
	
}

function checkLoginForm()
{alert(document.loginform.user_username.value)
	if((document.loginform.user_username.value == "")|| (document.loginform.user_username.value == "username"))
	{
		alert("Please enter a username")
		return false;
	}
	
	if(document.loginform.user_password.value == "")
	{
		alert("Please enter a password")
		return false;
	}
	
	return true;
}


function validateRegister()
{
	var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
	
	
	if((document.registerUser.user_first_name.value == "") || (document.registerUser.user_first_name.value == "First Name") || (document.registerUser.user_first_name.value == "Vorname"))
	{
		alert(document.registerUser.fnameerror.value)
		return false;
	}
	
	if((document.registerUser.user_email.value == "") || (document.registerUser.user_email.value == "Email") || (document.registerUser.user_email.value == "E-Mail"))
	{
		alert(document.registerUser.emailerror.value)
		return false;
	}
	
	if(!(emailPattern.test(document.registerUser.user_email.value)))
	{
		alert(document.registerUser.emailvaliderror.value)
		return false;
	}
	
	if((document.registerUser.user_username.value == "")|| (document.registerUser.user_username.value == "Username") || (document.registerUser.user_username.value == "Benutzername"))
	{
		alert(document.registerUser.usernameerror.value)
		return false;
	}
	
	if((document.registerUser.user_password.value == "")|| (document.registerUser.user_password.value == "Password"))
	{
		alert(document.registerUser.passworderror.value)
		return false;
	}
	
	
	if(document.registerUser.user_password.value.length <6)
	{
		alert(document.registerUser.passwordlngtherror.value)
		return false;
	}
	if(document.registerUser.terms.checked == false)
	{
	    alert(document.registerUser.termserror.value)
		return false;
	}
	
	return true;
}







function validateSettings()
{
	
	var targetlist = "";

for(var i = 0; i < document.userSettings.targets.length; i++){
if(document.userSettings.targets[i].checked) {
targetlist += document.userSettings.targets[i].value + ",";
}
}

document.userSettings.user_targets.value=targetlist;


var interestlist = "";

for(var i = 0; i < document.userSettings.interests.length; i++){
if(document.userSettings.interests[i].checked) {
interestlist += document.userSettings.interests[i].value + ",";
}
}
document.userSettings.user_interests.value=interestlist;

var terms = "";

for(var i = 0; i < document.userSettings.terms.length; i++){
if(document.userSettings.terms[i].checked) {
terms += document.userSettings.terms[i].value + ",";
}
}

	if(document.userSettings.user_fname.value == "")
	{
		alert(document.userSettings.fnameerror.value)
		return false;
	}
	if(document.userSettings.user_email.value == "")
	{
		alert(document.userSettings.emailvaliderror.value)
		return false;
	}
	if(document.userSettings.address1.value == "")
	{
		alert(document.userSettings.addresserror.value)
		return false;
	}
	if(document.userSettings.address2.value == "")
	{
		alert(document.userSettings.addresserror.value)
		return false;
	}
	if(document.userSettings.city.value == "")
	{
		alert(document.userSettings.cityerror.value)
		return false;
	}
	if(document.userSettings.country.value == "")
	{
		alert(document.userSettings.countryerror.value)
		return false;
	}
	if(document.userSettings.zipcode.value == "")
	{
		alert(document.userSettings.ziperror.value)
		return false;
	}
	if((document.userSettings.zipcode.value != "") && (document.userSettings.zipcode.value.length > 8))
	{
		alert(document.userSettings.zipvaliderror.value)
		return false;
	}
	
	if(document.userSettings.user_gender.value == "")
	{
		alert(document.userSettings.gendererror.value)
		return false;
	}
	if(document.userSettings.user_dob.value == "")
	{
		alert(document.userSettings.doberror.value)
		return false;
	}
	if(!(document.userSettings.terms.checked))
	{
		alert(document.userSettings.termserror.value)
		return false;
	}
	
	return true;
}

function validateFilters()
{
	var musclelist = "";

for(var i = 0; i < document.workoutFilter.muscles.length; i++){
if(document.workoutFilter.muscles[i].checked) {
musclelist += document.workoutFilter.muscles[i].value + ",";
}
}
if(musclelist == "")
{
	alert("Please select a filter")
	return false;
}
document.workoutFilter.user_muscles.value=musclelist;



return true;
}

function validateForgotLogin()
{
	if(document.forgotLogin.forgot_email.value == "")
	{
		alert("Please enter a email")
		return false;
	}
	
	return true;
	
}


function validateContactForm()
{
	if(document.contactform.name.value == "")
	{
		alert("Please enter your name")
		return false;
	}
	if(document.contactform.email.value == "")
	{
		alert("Please enter your email")
		return false;
	}
	if(document.contactform.message.value == "")
	{
		alert("Please enter a message")
		return false;
	}
	
	return true;
}

function validateTerminateForm()
{
	if(document.terminateForm.terminate_reason.value == "")
	{
		alert("Please enter a reason for terminating.")
		return false;
	}
	return true;
}