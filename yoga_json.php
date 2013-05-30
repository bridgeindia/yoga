<?php
ini_set( 'error_reporting', E_ALL ^ E_NOTICE );
ini_set( 'display_errors', '1' );
include("yogaClass.php");
$newObject  = new yoga();
$authToken  = "yogaCodex";

/*$_POST["registerUser"] = "yes";
$_POST["token"]  = "yogaCodex";
$_POST["fname"] ="lekha";
$_POST["lname"] = "philip";
$_POST["email"] = "lekbin@gmail.com";
$_POST["password"] = "123456";
$_POST["fb"]       = "0";*/

if(($_POST["registerUser"]=="yes")&& ($_POST["token"]==$authToken)&& ($_POST["fname"]!="")&&($_POST["lname"]!="")&&($_POST["email"]!="")&& ($_POST["password"]!="")&&($_POST["fb"]!=""))
{
  
    $newObject->registerUser($_POST["fname"],$_POST["lname"],$_POST["email"],$_POST["password"],$_POST["fb"]);
	
}

if(($_GET["login"] !="") && ($_GET["token"]==$authToken) && ($_GET["email"] !="")&& ($_GET["password"] !=""))
  {
    $newObject->userlogin($_GET["email"],$_GET["password"]);
  }
  
  
  if(($_GET["iphoneRegister"] !="") && ($_GET["token"]==$authToken) && ($_GET["user_id"] !="") && ($_GET["userid"] !=0))
  {
    $newObject->registerdevice($_GET["user_id"],$_GET["deviceuid"],$_GET["devicetoken"]);
  }
  
  
  
  if(($_GET["feedback"] !="") && ($_GET["token"]==$authToken) && ($_GET["user_id"] !="")&&($_GET["device"]) &&($_GET["email"]) &&($_GET["name"]) &&($_GET["message"]))
  {
    $newObject->sendFeedbackMail($_GET["user_id"],$_GET["device"],$_GET["name"],$_GET["email"],$_GET["message"]);
  }
  
  
  if(($_GET["listapps"] !="") && ($_GET["token"]==$authToken) && ($_GET["userid"] !=""))
  {
    if($_GET["lang"] == "")
	{
		$lang  =  1;
	}else
	{
		$lang = $_GET["lang"];
	}
	
	
    $newObject->listapps($_GET["userid"],$lang);
  }
  
   if(($_GET["yogavideos"] !="") && ($_GET["token"]==$authToken) && ($_GET["userid"] !="")&& ($_GET["workout_id"] !=""))
  {
    if($_GET["lang"] == "")
	{
		$lang  =  1;
	}else
	{
		$lang = $_GET["lang"];
	}
	
	
    $newObject->listvideo($_GET["userid"],$_GET["workout_id"],$lang);
  }
  
  if(($_GET["yogafilters"] !="") && ($_GET["token"]==$authToken))
  {
  	if($_GET["lang"] == "")
	{
		$lang  =  1;
	}else
	{
		$lang = $_GET["lang"];
	}
	
	$newObject->listFilters($lang);
  }
  
  
  
?>