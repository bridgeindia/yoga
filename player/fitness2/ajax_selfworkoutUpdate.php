<?php
include("../paypal_ipn/conn.php");

if($_GET["userid"] != "")
{
	$userid       = $_GET["userid"];
	$workoutid    = $_GET["workoutid"];
	$workoutTime  = $_GET["timeDone"];
	$currentDate  = date('Y-m-d h:m:s');
	
	//update the workouts done table
	
	//check for record
	
	$check_record  = mysql_query("select * from fitness_selfmade_workouts_done where user_id='".$userid."' and workout_id='".$workoutid."'");
	$rows          = mysql_num_rows($check_record);
	
	
	if($rows > 0)
	{
		//update the table with the time
		
		$updateWorkout = mysql_query("update fitness_selfmade_workouts_done set `workout_time`=`workout_time` + '".$workoutTime."',`workout_done_date`='".$currentDate."' where user_id='".$userid."' and workout_id='".$workoutid."'");
	}
	else 
	{
		//insert record
		$insertWorkoutTime = mysql_query("insert into fitness_selfmade_workouts_done(`user_id`,`workout_id`,`workout_time`,`workout_done_date`,`workout_done_status`) values('".$userid."','".$workoutid."','".$workoutTime."','".$currentDate."',1)");
	}
	
	
}

