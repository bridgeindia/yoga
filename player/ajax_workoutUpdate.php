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
	
	$check_record  = mysql_query("select * from fitness_user_workouts_done where user_id='".$userid."' and workout_id='".$workoutid."'");
	$rows          = mysql_num_rows($check_record);
	
	
	if($rows > 0)
	{
		//update the table with the time
		
		$updateWorkout = mysql_query("update fitness_user_workouts_done set `workout_time`=`workout_time` + '".$workoutTime."',`workout_done_date`='".$currentDate."' where user_id='".$userid."' and workout_id='".$workoutid."'");
	}
	else 
	{
		//insert record
		$insertWorkoutTime = mysql_query("insert into fitness_user_workouts_done(`user_id`,`workout_id`,`workout_time`,`workout_done_date`,`workout_done_status`) values('".$userid."','".$workoutid."','".$workoutTime."','".$currentDate."',1)");
	}
	
	
}

if($_GET["exercises"] != "")
{
	$timeframe = "";
	$level          = $_GET["level"];
	$recovery       = $_GET["recoveryTime"];
	
	
	$exerciseArray  = explode(",",rtrim($_GET["exercises"],","));
	foreach($exerciseArray as $exercise)
	{
		$selectTimeFrame  = mysql_query("select workout_timeframe from fitness_exercise_general where workout_id='".$exercise."'");
		$getTimeframe     = mysql_fetch_row($selectTimeFrame);
		
		$selectReps    = mysql_query("select repetitions from fitness_exercise_repetition where workout_id='".$exercise."' and exercise_level_id='".$level."'");
		$getReps       = mysql_fetch_row($selectReps);
		
		$selectOtherside = mysql_query("select otherside from fitness_exercise_general where workout_id='".$exercise."'");
		$getOtherside    = mysql_fetch_row($selectOtherside);
		
		if($getOtherside[0]==1)
		{
			$timeframe        = $timeframe + (($getTimeframe[0] * $getReps[0])* 2);
		}
		else
		{
			$timeframe        = $timeframe + ($getTimeframe[0] * $getReps[0]);
		}
		
		
	}
	$startTime      = 5 * (count($exerciseArray));
	$totalWorkoutTime     = round(($timeframe + $recovery + $startTime) /60,2);
	echo $totalWorkoutTime;
}