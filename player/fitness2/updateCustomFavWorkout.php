<?php
include("../paypal_ipn/conn.php");

if(($_GET["user_id"] !="") && ($_GET["workout_id"] !="") && ($_GET["status"] !=""))
{
	$userid         = $_GET["user_id"];
	$workoutid      = $_GET["workout_id"];
	$status         = $_GET["status"];
	
	
	$checkRecord    = mysql_query("select * from  fitness_custom_fav_workouts where user_id='".$userid."' and workout_id='".$workoutid."'");
	$rows          = mysql_num_rows($checkRecord);
	
	if($rows > 0)
	{
		$selectStatus        = mysql_query("select fav_status from fitness_custom_fav_workouts where user_id='".$userid."' and workout_id='".$workoutid."'");
		$getStatus           = mysql_fetch_row($selectStatus);
		$favstatus           = $getStatus[0];   
		if($favstatus == 1)
		{
			$setStatus  = 0;
		}
		else
		{
			$setStatus  = 1;
			
		}
		
		$updateFavStatus  = mysql_query("update fitness_custom_fav_workouts set fav_status='".$setStatus."' where user_id='".$userid."' and workout_id='".$workoutid."'");
		
		
	}
	else
	{
	    if($status == 1)
		{
			$setStatus  = 0;
		}
		else
		{
			$setStatus  = 1;
			
		}
	    
		$insert_query   =  mysql_query("insert into fitness_custom_fav_workouts(`user_id`,`workout_id`,`fav_status`) values('".$userid."','".$workoutid."','".$setStatus."')");
	}
	
	echo $setStatus; 
}


?>